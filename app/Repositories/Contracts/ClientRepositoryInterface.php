<?php

namespace App\Repositories\Contracts;

use App\Models\Client;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ClientRepositoryInterface
{
    public function paginateForUser(int $userId, int $perPage = 15): LengthAwarePaginator;
    public function create(array $data, int $userId): Client;
    public function update(Client $client, array $data): Client;
    public function delete(Client $client): void;
}