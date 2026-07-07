<?php

namespace App\Repositories;

use App\Models\Client;
use App\Repositories\Contracts\ClientRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ClientRepository implements ClientRepositoryInterface
{
    public function paginateForUser(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        return Client::where('user_id', $userId)
            ->latest()
            ->paginate($perPage);
    }

    public function create(array $data, int $userId): Client
    {
        return Client::create([
            'user_id' => $userId,
            'name'    => $data['name'],
            'email'   => $data['email'],
            'phone'   => $data['phone'] ?? null,
            'status'  => 'active',
        ]);
    }

    public function update(Client $client, array $data): Client
    {
        $client->update([
            'name'   => $data['name'],
            'email'  => $data['email'],
            'status' => $data['status'] ?? $client->status,
        ]);

        return $client;
    }

    public function delete(Client $client): void
    {
        $client->delete();
    }
}