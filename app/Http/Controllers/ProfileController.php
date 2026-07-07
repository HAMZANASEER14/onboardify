<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreProfileRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\Team;
use Illuminate\Support\Facades\Storage;
use App\Repositories\Contracts\ProfileRepositoryInterface;

class ProfileController extends Controller
{
    public function __construct(protected ProfileRepositoryInterface $profiles)
    {
    }

    public function create(Request $request)
    {
        $user = auth()->user();
        $profile = $user->profile;
        
        // 1. Determine if user is invited based on DB (most reliable) or Session/URL
        $isInvited = in_array($user->role, ['employee', 'client']) || $user->team_id !== null;
        
        $team = null;
        $adminProfile = null;
        $teamData = null;
        $companyCode = null;

        if ($isInvited) {
            // Fetch team from DB using the team_id set during registration
            $team = $user->team; 
            
            if ($team) {
                $adminProfile = $team->owner->profile;
                $companyCode = $team->invite_code;

                $teamData = [
                    'company_name' => $team->name,
                    'industry'     => $adminProfile?->industry ?? '',
                    'domain'       => $adminProfile?->domain ?? '',
                    'location'     => $adminProfile?->location ?? '',
                    'phone'        => $adminProfile?->phone ?? '',
                    'business_type'=> $adminProfile?->business_type ?? '',
                    'company_code' => $companyCode,
                ];
            }
        } else {
            // Fallback for Admins who haven't created a team yet
            $companyCode = $request->query('company_code') ?: session('pending_company_code');
            if ($companyCode) {
                $team = $this->profiles->findTeamByInviteCode($companyCode);
                if ($team) {
                    $adminProfile = $team->owner->profile;
                    // ... (same logic as above if needed for admins joining via link)
                }
            }
        }

        // Split registered name into first and last
        $nameParts = explode(' ', $user->name, 2);

        // Build pre-fill array
        $prefill = [
            'first_name'    => $nameParts[0] ?? '',
            'last_name'     => $nameParts[1] ?? '',
            'email'         => $user->email,
            'company_name'  => $teamData['company_name'] ?? '',
            'domain'        => $teamData['domain'] ?? '',
            'location'      => $teamData['location'] ?? '',
            'phone'         => $teamData['phone'] ?? '',
            'business_type' => $teamData['business_type'] ?? ($profile?->business_type ?? ''),
            'industry'      => $teamData['industry'] ?? '',
        ];

        // Only redirect to use-case if NOT an invited user and business type is missing
        if (!$isInvited && empty($prefill['business_type'])) {
            return redirect('/onboarding/use-case')
                ->withErrors(['business_type' => 'Please select a business type first.']);
        }

        $userRole = $user->role ?? 'admin';
        $availableRoles = $isInvited ? ['employee', 'client'] : ['admin'];

        return view('profile.create', compact(
            'profile',
            'userRole',
            'companyCode',
            'availableRoles',
            'prefill',
            'team',
            'teamData',    // 👈 ADDED: Needed for the read-only view
            'isInvited'    // 👈 ADDED: Needed to toggle the UI
        ));
    }

    public function store(StoreProfileRequest $request)
    {
        $validated = $request->validated();
        $user = auth()->user();

        // ✅ SECURITY FIX: Trust the database role/team_id over the form input
        $isInvitedUser = in_array($user->role, ['employee', 'client']) || $user->team_id !== null;

        $team = null;

        if ($isInvitedUser) {
            // ── Employee / Client Flow ──
            $team = $user->team; // Already assigned in AuthRepository
            
            if (!$team) {
                return back()->withErrors(['team' => 'No team associated with your account.'])->withInput();
            }

            // Force company fields from admin's profile (Security Step)
            $adminProfile = $team->owner->profile;
            $validated['company_name']  = $team->name;
            $validated['domain']        = $adminProfile?->domain;
            $validated['location']      = $adminProfile?->location;
            $validated['phone']         = $adminProfile?->phone;
            $validated['business_type'] = $adminProfile?->business_type;
            $validated['industry']      = $adminProfile?->industry;
            
            // Ensure role stays as what was assigned in DB
            $validated['role'] = $user->role; 

            // Mark invite as joined if not already
            $this->profiles->markInviteAsJoined($user->email);

        } else {
            // ── Admin Flow ──
            if (!$user->team_id) {
                $team = $this->profiles->createTeam($validated['company_name'], $user->id);
                $user->team_id = $team->id;
                $user->save();
            } else {
                $team = $user->team;
            }
            
            $validated['role'] = 'admin';
        }

        // Handle profile picture upload
        $picturePath = null;
        if ($request->hasFile('picture')) {
            $picturePath = $request->file('picture')->store('profile_pictures', 'public');
        }

        // Save the profile
        $this->profiles->updateOrCreateProfile($user->id, [
            'first_name'      => $validated['first_name'],
            'last_name'       => $validated['last_name'],
            'company_name'    => $validated['company_name'],
            'domain'          => $validated['domain'],
            'location'        => $validated['location'],
            'phone'           => $validated['phone'],
            'business_type'   => $validated['business_type'],
            'industry'        => $validated['industry'],
            'bio'             => $validated['bio'] ?? null,
            'profile_picture' => $picturePath,
        ]);

        // Redirect each role to their correct dashboard
        return match($user->role) {
            'admin'    => redirect()->route('plans')
                            ->with('success', 'Profile saved! Your invite code is: ' . $team->invite_code),
            'employee' => redirect()->route('employee.dashboard')
                            ->with('success', 'Welcome to ' . $team->name),
            'client'   => redirect()->route('client.portal')
                            ->with('success', 'Welcome to ' . $team->name),
            default    => redirect('/dashboard'),
        };
    }

    // ... (Keep your edit, update, and show methods exactly as they were) ...
    
    public function edit(Request $request)
    {
        $profile  = auth()->user()->profile;
        $user     = auth()->user();
        $userRole = $user->role ?? 'admin';
        return view('profile.edit', compact('profile', 'user', 'userRole'));
    }

    public function update(UpdateProfileRequest $request)
    {
        $validated = $request->validated();
        $user    = auth()->user();
        $oldRole = $user->role;

        if ($validated['role'] !== $oldRole) {
            if ($oldRole === 'admin' && in_array($validated['role'], ['employee', 'client'])) {
                return back()->withErrors(['role' => 'Admins cannot change to other roles. Transfer ownership first.'])->withInput();
            }
            if ($validated['role'] === 'admin' && $oldRole !== 'admin') {
                if (!$user->team_id) {
                    $team = $this->profiles->createTeam($validated['company_name'], $user->id);
                    $user->team_id = $team->id;
                }
            }
            $user->role = $validated['role'];
        }
        $user->save();

        $profileData = [
            'first_name'    => $validated['first_name'],
            'last_name'     => $validated['last_name'],
            'company_name'  => $validated['company_name'],
            'industry'      => $validated['industry'],
            'domain'        => $validated['domain'],
            'phone'         => $validated['full_phone'],
            'location'      => $validated['location'],
            'bio'           => $validated['bio'] ?? null,
            'business_type' => $validated['business_type'],
        ];

        if ($request->hasFile('picture')) {
            if ($user->profile?->profile_picture) {
                Storage::disk('public')->delete($user->profile->profile_picture);
            }
            $profileData['profile_picture'] = $request->file('picture')->store('profile_pictures', 'public');
        }

        $this->profiles->updateOrCreateProfile($user->id, $profileData);
        return redirect()->route('profile.show')->with('success', 'Profile updated successfully!');
    }

    public function show()
    {
        $profile = auth()->user()->profile;
        return view('profile.show', compact('profile'));
    }
}