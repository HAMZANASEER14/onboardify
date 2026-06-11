<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Models\WaiverInvitation;
use App\Models\Waiver;              
// use App\Models\WaiverInvitation;
use Illuminate\Support\Str;


class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::where('user_id', auth()->id())
                         ->latest()
                         ->paginate(15); // ✅ paginated
        return view('clients.index', compact('clients'));
    }
//     public function create()
// {
//     // Clients are auto-created when sending waivers
//     return redirect()->route('waivers.index')
//            ->with('info', 'Add clients by sending a waiver.');
// }

    public function create()
    {
        $waivers = Waiver::where('user_id', auth()->id())
                         ->where('status', 'published')
                         ->get();

        return view('clients.create', compact('waivers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|max:255',
            'waiver_id'  => 'nullable|exists:waivers,id',
        ]);

        $client = Client::create([
            'user_id' => auth()->id(),
            'name'    => $request->name,
            'email'   => $request->email,
            'phone'   => $request->phone,
            'status'  => 'active',
        ]);
        return redirect()->route('clients.index')->with('success', 'Client added!');
    }

    //     // If a waiver was selected, generate invitation
    //     if ($request->waiver_id) {
    //         $token = Str::uuid();

    //         WaiverInvitation::create([
    //             'client_id' => $client->id,
    //             'waiver_id' => $request->waiver_id,
    //             'user_id'   => auth()->id(),
    //             'token'     => $token,
    //         ]);

    //         $link = url('/sign/' . $token);

    //         return redirect()->route('clients.invitation', [
    //             'client' => $client->id,
    //             'token'  => $token,
    //         ]);
    //     }

    //     return redirect()->route('clients.index')->with('success', 'Client added!');
    // }

    public function show(Client $client)
    {
        return view('clients.show', compact('client'));
    }

    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);

        $client->update($request->only('name', 'email', 'status'));

        return redirect()->route('clients.index')->with('success', 'Client updated!');
    }

    public function destroy(Client $client)
    {
        $client->delete();
        return redirect()->route('clients.index')->with('success', 'Client deleted!');
    }
//     public function invitation(Client $client, string $token)
// {
//     $invitation = WaiverInvitation::where('token', $token)->firstOrFail();
//     $link = url('/sign/' . $token);

//     return view('clients.invitation', compact('client', 'invitation', 'link', 'token'));
// }
}