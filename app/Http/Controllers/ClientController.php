<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Models\WaiverInvitation;
use App\Models\Waiver;              
use Illuminate\Support\Str;
use App\Http\Requests\UpdateClientRequest;
use App\Repositories\Contracts\ClientRepositoryInterface;
use App\Repositories\Contracts\WaiverRepositoryInterface;

class ClientController extends Controller
{
    public function __construct(
    protected ClientRepositoryInterface $clients,
    protected WaiverRepositoryInterface $waivers
) {}
   public function index()
{
    $userId = auth()->id();

    $clients = Client::where('user_id', $userId)->latest()->paginate(15);
    $activeCount = Client::where('user_id', $userId)->where('status', 'active')->count();
    $addedThisMonth = Client::where('user_id', $userId)->where('created_at', '>=', now()->startOfMonth())->count();

    return view('clients.index', compact('clients', 'activeCount', 'addedThisMonth'));
}
  public function create()
{
    $waivers = $this->waivers->publishedForUser(auth()->id());
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
    public function show(Client $client)
    {
        return view('clients.show', compact('client'));
    }

    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }
    public function update(UpdateClientRequest $request, Client $client)
        {
                // Update the client model with validated data from the request
                $client->update($request->validated());

                return redirect()->route('clients.index')->with('success', 'Client updated!');
        }
   public function destroy(Client $client)
    {
        $client->delete();
        return redirect()->route('clients.index')->with('success', 'Client deleted!');
    }
}