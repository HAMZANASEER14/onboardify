<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\WaiverSend;

class ClientPortalController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $client = Client::where('portal_user_id', $user->id)->first();

        $pendingWaivers = collect();
        $signedWaivers  = collect();

        if ($client) {
            $pendingWaivers = WaiverSend::with('waiver')
                ->where('client_id', $client->id)
                ->where('status', 'pending')
                ->latest()
                ->get();

            $signedWaivers = WaiverSend::with('waiver')
                ->where('client_id', $client->id)
                ->where('status', 'signed')
                ->latest()
                ->get();
        }

        return view('client.portal', compact('pendingWaivers', 'signedWaivers'));
    }
     public function myDocuments()
    {
        $user = auth()->user();
 
        $client = Client::where('portal_user_id', $user->id)->first();
 
        $pendingWaivers = collect();
        $signedWaivers  = collect();
 
        if ($client) {
            $pendingWaivers = WaiverSend::with('waiver')
                ->where('client_id', $client->id)
                ->where('status', 'pending')
                ->latest()
                ->get();
 
            $signedWaivers = WaiverSend::with('waiver')
                ->where('client_id', $client->id)
                ->where('status', 'signed')
                ->latest()
                ->get();
        }
 
        return view('client.documents', compact('pendingWaivers', 'signedWaivers'));
    }
}