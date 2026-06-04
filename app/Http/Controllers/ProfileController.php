<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profile;

class ProfileController extends Controller
{
    // Show the create/edit form
    public function create()
    {
        // Pass existing profile if it exists, or null
        $profile = auth()->user()->profile;
        return view('profile.create', compact('profile'));
    }

    // Save the profile
    public function store(Request $request)
    {
        $request->validate([
            'business_type' => 'required',
            'first_name'    => 'required',
        
        ]);
       
    

        Profile::updateOrCreate(
            ['user_id' => auth()->id()],  // match by user
            [
                'business_type' => $request->business_type,
                'first_name'    => $request->first_name,
                'last_name'     => $request->last_name,
                'company_name'  => $request->company_name,
                'industry'      => $request->industry,
                'domain'        => $request->domain,
                'phone'         => $request->phone,
                'location'      => $request->location,
                'address'       => $request->address,
                'bio'           => $request->bio,
            ]
        );

        return redirect('/plans');
    }

    // Show the profile page
    public function show()
    {
        $profile = auth()->user()->profile;
        return view('profile.show', compact('profile'));
    }
}