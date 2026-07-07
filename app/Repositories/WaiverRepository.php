<?php

namespace App\Repositories;

use App\Models\Waiver;
use App\Models\WaiverSend;
use App\Models\WaiverSubmission;
use App\Models\Client;
use App\Repositories\Contracts\WaiverRepositoryInterface;

class WaiverRepository implements WaiverRepositoryInterface
{
public function paginateForUser(int $userId, int $perPage = 10)
{
    return Waiver::where('user_id', $userId)
        ->withCount([
            'sends' => fn($q) => $q->whereNotNull('client_id'),
            'sends as signed_count' => fn($q) => $q->whereNotNull('client_id')->where('status', 'signed'),
            'sends as pending_count' => fn($q) => $q->whereNotNull('client_id')->where('status', 'pending'),
        ])
        ->with(['sends' => fn($q) => $q->whereNotNull('client_id')->latest()->limit(1)])
        ->latest()
        ->paginate($perPage);
}

    public function create(array $data): Waiver
    {
        return Waiver::create($data);
    }

    public function loadSendsWithClient(Waiver $waiver): Waiver
    {
        $waiver->load(['sends' => function ($query) {
            $query->whereNotNull('client_id');
        }]);
        return $waiver;
    }

    public function update(Waiver $waiver, array $data): Waiver
    {
        $waiver->update($data);
        return $waiver;
    }

    public function delete(Waiver $waiver): void
    {
        $waiver->delete();
    }

    public function firstOrCreateShareLink(int $waiverId, int $userId, string $token): WaiverSend
    {
        return WaiverSend::firstOrCreate(
            [
                'waiver_id' => $waiverId,
                'sent_by'   => $userId,
                'client_id' => null,
            ],
            [
                'token' => $token,
            ]
        );
    }

    public function findOrCreateClient(string $email, int $userId, string $name): Client
    {
        return Client::firstOrCreate(
            ['email' => $email, 'user_id' => $userId],
            ['name'  => $name, 'status' => 'active']
        );
    }

    public function createSend(array $data): WaiverSend
    {
        return WaiverSend::create($data);
    }

    public function findSendByToken(string $token): WaiverSend
    {
        return WaiverSend::where('token', $token)->firstOrFail();
    }

    public function markSendSigned(WaiverSend $send): void
    {
        $send->update([
            'status'    => 'signed',
            'signed_at' => now(),
        ]);
    }

    public function createSubmission(array $data): WaiverSubmission
    {
        return WaiverSubmission::create($data);
    }

    public function submissionsForUser(int $userId, int $perPage = 10)
    {
        return WaiverSend::with(['waiver', 'client', 'submission'])
            ->where('sent_by', $userId)
            ->latest()
            ->paginate($perPage);
    }

    public function countSends(int $userId, ?string $status = null)
    {
        $query = WaiverSend::where('sent_by', $userId)->whereNotNull('client_id');

        if ($status) {
            $query->where('status', $status);
        }

        return $query->count();
    }

    public function topWaiversForUser(int $userId, int $limit = 5)
    {
        return Waiver::where('user_id', $userId)
            ->withCount([
                'sends'                  => fn($q) => $q->whereNotNull('client_id'),
                'sends as signed_count'  => fn($q) => $q->whereNotNull('client_id')->where('status', 'signed'),
                'sends as pending_count' => fn($q) => $q->whereNotNull('client_id')->where('status', 'pending'),
            ])
            ->having('sends_count', '>', 0)
            ->orderByDesc('sends_count')
            ->take($limit)
            ->get();
    }

    public function submissionsOverTime(int $userId, int $days = 30)
    {
        return WaiverSubmission::where('sent_by', $userId)
            ->where('created_at', '>=', now()->subDays($days))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    public function exportQuery(int $userId, string $status, int $days)
    {
        $query = WaiverSend::with(['waiver', 'client'])
            ->where('sent_by', $userId)
            ->latest();

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($days > 0) {
            $query->where('created_at', '>=', now()->subDays($days));
        }

        return $query;
    }

    // ── Template Library (this controller's own set — separate from TemplateController's) ──
    public function getTemplates(): array
    {
        return [
            ['id' => 'nda',      'title' => 'NDA Agreement',        'fields' => [['label' => 'Full Name',      'type' => 'text',     'required' => true], ['label' => 'Company Name',    'type' => 'text',  'required' => true], ['label' => 'Date',            'type' => 'date',      'required' => true], ['label' => 'Signature', 'type' => 'signature', 'required' => true]]],
            ['id' => 'gym',      'title' => 'Gym / Fitness Waiver', 'fields' => [['label' => 'Full Name',      'type' => 'text',     'required' => true], ['label' => 'Date of Birth',   'type' => 'date',  'required' => true], ['label' => 'Emergency Contact','type' => 'text',      'required' => true], ['label' => 'Emergency Phone', 'type' => 'text',      'required' => true], ['label' => 'Signature', 'type' => 'signature', 'required' => true]]],
            ['id' => 'service',  'title' => 'Service Agreement',    'fields' => [['label' => 'Client Name',    'type' => 'text',     'required' => true], ['label' => 'Email',           'type' => 'email', 'required' => true], ['label' => 'Service Type',     'type' => 'text',      'required' => true], ['label' => 'Start Date',      'type' => 'date',      'required' => true], ['label' => 'Signature', 'type' => 'signature', 'required' => true]]],
            ['id' => 'event',    'title' => 'Event Waiver',         'fields' => [['label' => 'Full Name',      'type' => 'text',     'required' => true], ['label' => 'Email',           'type' => 'email', 'required' => true], ['label' => 'Phone',            'type' => 'text',      'required' => false],['label' => 'Event Date',      'type' => 'date',      'required' => true], ['label' => 'Signature', 'type' => 'signature', 'required' => true]]],
            ['id' => 'medical',  'title' => 'Medical Consent',      'fields' => [['label' => 'Patient Name',   'type' => 'text',     'required' => true], ['label' => 'Date of Birth',   'type' => 'date',  'required' => true], ['label' => 'Allergies',        'type' => 'textarea',  'required' => false],['label' => 'Doctor Name',     'type' => 'text',      'required' => true], ['label' => 'Signature', 'type' => 'signature', 'required' => true]]],
            ['id' => 'property', 'title' => 'Property Inspection',  'fields' => [['label' => 'Inspector Name', 'type' => 'text',     'required' => true], ['label' => 'Property Address','type' => 'text',  'required' => true], ['label' => 'Inspection Date',  'type' => 'date',      'required' => true], ['label' => 'Owner Name',      'type' => 'text',      'required' => true], ['label' => 'Signature', 'type' => 'signature', 'required' => true]]],
        ];
    }
    public function publishedForUser(int $userId)
{
    return Waiver::where('user_id', $userId)
                 ->where('status', 'published')
                 ->get();
}
}