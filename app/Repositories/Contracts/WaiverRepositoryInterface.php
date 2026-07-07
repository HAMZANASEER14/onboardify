<?php

namespace App\Repositories\Contracts;

use App\Models\Waiver;
use App\Models\WaiverSend;
use App\Models\WaiverSubmission;
use Illuminate\Http\Request;

interface WaiverRepositoryInterface
{
    public function paginateForUser(int $userId, int $perPage = 10);
    public function create(array $data): Waiver;
    public function loadSendsWithClient(Waiver $waiver): Waiver;
    public function update(Waiver $waiver, array $data): Waiver;
    public function delete(Waiver $waiver): void;

    public function firstOrCreateShareLink(int $waiverId, int $userId, string $token): WaiverSend;
    public function findOrCreateClient(string $email, int $userId, string $name): \App\Models\Client;
    public function createSend(array $data): WaiverSend;
    public function findSendByToken(string $token): WaiverSend;
    public function markSendSigned(WaiverSend $send): void;
    public function createSubmission(array $data): WaiverSubmission;

    public function submissionsForUser(int $userId, int $perPage = 10);
    public function countSends(int $userId, ?string $status = null);
    public function topWaiversForUser(int $userId, int $limit = 5);
    public function submissionsOverTime(int $userId, int $days = 30);
    public function exportQuery(int $userId, string $status, int $days);

    public function getTemplates(): array;
    public function publishedForUser(int $userId);
}