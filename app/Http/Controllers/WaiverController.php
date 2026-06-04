<?php

namespace App\Http\Controllers;

use App\Models\Waiver;
use App\Models\Client;
use App\Models\WaiverSend;
use App\Models\WaiverSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WaiverController extends Controller
{
    // ── Index ─────────────────────────────────────────────────────
    public function index()
    {
        $waivers = Waiver::where('user_id', auth()->id())
                         ->latest()
                         ->get();
        return view('waivers.index', compact('waivers'));
    }

    // ── Create ────────────────────────────────────────────────────
    public function create()
    {
        return view('waivers.create');
    }

    // ── Store ─────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'title'  => 'required|string|max:255',
            'fields' => 'required|string',
        ]);

        $fields = json_decode($request->input('fields'), true);

        if (empty($fields)) {
            return back()
                ->withInput()
                ->withErrors(['fields' => 'Please add at least one field.']);
        }

        Waiver::create([
            'user_id'           => auth()->id(),
            'title'             => $request->input('title'),
            'fields'            => $fields,
            'require_signature' => $request->has('require_signature'),
            'slug'              => Str::slug($request->input('title')) . '-' . uniqid(),
        ]);

        return redirect()->route('waivers.index')
                         ->with('success', 'Waiver created!');
    }

    // ── Show ──────────────────────────────────────────────────────
    public function show(Waiver $waiver)
    {
        return view('waivers.show', compact('waiver'));
    }

    // ── Edit ──────────────────────────────────────────────────────
    public function edit(Waiver $waiver)
    {
        return view('waivers.edit', compact('waiver'));
    }

    // ── Update ────────────────────────────────────────────────────
    public function update(Request $request, Waiver $waiver)
    {
        $request->validate([
            'title'  => 'required|string|max:255',
            'fields' => 'required|string',
        ]);

        $fields = json_decode($request->input('fields'), true);

        $waiver->update([
            'title'             => $request->input('title'),
            'fields'            => $fields,
            'require_signature' => $request->has('require_signature'),
        ]);

        return redirect()->route('waivers.index')
                         ->with('success', 'Waiver updated!');
    }

    // ── Destroy ───────────────────────────────────────────────────
    public function destroy(Waiver $waiver)
    {
        $waiver->delete();
        return redirect()->route('waivers.index')
                         ->with('success', 'Waiver deleted!');
    }

    // ── Show Send Form ────────────────────────────────────────────
    public function sendForm(Waiver $waiver)
    {
        return view('waivers.send', compact('waiver'));
    }

    // ── Send Waiver ───────────────────────────────────────────────
    public function send(Request $request, Waiver $waiver)
    {
        $request->validate([
            'client_name'  => 'required|string|max:255',
            'client_email' => 'required|email|max:255',
        ]);

        // Create or find client automatically
        $client = Client::firstOrCreate(
            [
                'email'   => $request->client_email,
                'user_id' => auth()->id(),
            ],
            [
                'name'   => $request->client_name,
                'status' => 'active',
            ]
        );

        $waiverSend = WaiverSend::create([
            'waiver_id'    => $waiver->id,
            'sent_by'      => auth()->id(),
            'client_id'    => $client->id,
            'client_name'  => $request->client_name,
            'client_email' => $request->client_email,
            'token'        => Str::uuid(),
            'status'       => 'pending',
        ]);

        $link = route('waivers.sign', $waiverSend->token);

        \Mail::raw(
            "Hi {$request->client_name},\n\n" .
            "You have been sent a waiver to sign.\n\n" .
            "Click here to sign:\n{$link}\n\n" .
            "Thank you,\nOnboardify",
            function ($message) use ($request, $waiver) {
                $message->to($request->client_email, $request->client_name)
                        ->subject('Please sign: ' . $waiver->title);
            }
        );

        return back()->with('success', '✅ Waiver sent to ' . $request->client_email);
    }

    // ── Sign Page (public) ────────────────────────────────────────
    public function sign($token)
    {
        $send   = WaiverSend::where('token', $token)->firstOrFail();
        $waiver = $send->waiver;
        $fields = $waiver->fields ?? [];

        return view('waivers.sign', compact('send', 'waiver', 'fields'));
    }

    // ── Submit Signature ──────────────────────────────────────────
    public function submitSign(Request $request, $token)
    {
        $send   = WaiverSend::where('token', $token)->firstOrFail();
        $waiver = $send->waiver; // ← was missing!

        $request->validate([
            'signer_name'  => 'required|string|max:255',
            'signer_email' => 'required|email|max:255',
        ]);

        $send->update([
            'status'    => 'signed',
            'signed_at' => now(),
        ]);

        // Save submission
        WaiverSubmission::create([
            'waiver_id'  => $send->waiver_id,
            'sent_by'    => $send->sent_by,
            'client_id'  => $send->client_id,
            'token'      => $token,
            'responses'  => json_encode($request->except(['_token', 'signer_name', 'signer_email', 'signature'])),
            'signature'  => $request->signature ?? null,
            'status'     => 'signed',
        ]);

        return view('waivers.signed', compact('send', 'waiver'));
    }

    // ── My Submissions ────────────────────────────────────────────
    public function mySubmissions()
    {
        $submissions = WaiverSubmission::with(['waiver', 'client'])
                        ->where('sent_by', auth()->id())
                        ->latest()
                        ->get();

        return view('dashboard.submissions', compact('submissions'));
    }
}