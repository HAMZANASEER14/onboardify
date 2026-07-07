<?php

namespace App\Http\Controllers;

use App\Jobs\SendWaiverEmail;
use App\Models\Waiver;
use App\Models\WaiverSend;
use App\Http\Requests\StoreWaiverRequest;
use App\Http\Requests\UpdateWaiverRequest;
use App\Http\Requests\SendWaiverRequest;
use App\Http\Requests\SubmitSignRequest;
use App\Repositories\Contracts\WaiverRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Barryvdh\DomPDF\Facade\Pdf;

class WaiverController extends Controller
{
    public function __construct(protected WaiverRepositoryInterface $waivers)
    {
    }

    // ── Index ─────────────────────────────────────────────────────
    public function index()
    {
        $waivers = $this->waivers->paginateForUser(auth()->id());
        return view('waivers.index', compact('waivers'));
    }

    // ── Create ────────────────────────────────────────────────────
    public function create()
    {
        return view('waivers.create');
    }

    // ── Store ─────────────────────────────────────────────────────
    public function store(StoreWaiverRequest $request)
    {
        $fields = json_decode($request->input('fields'), true);

        if (empty($fields)) {
            return back()->withInput()->withErrors(['fields' => 'Please add at least one field.']);
        }

        $pdfPath = null;
        if ($request->hasFile('pdf_document')) {
            $pdfPath = $request->file('pdf_document')->store('waivers/pdfs', 'public');
        }

        $this->waivers->create([
            'user_id'           => auth()->id(),
            'title'             => $request->input('title'),
            'fields'            => $fields,
            'require_signature' => $request->has('require_signature'),
            'slug'              => Str::slug($request->input('title')) . '-' . uniqid(),
            'pdf_document'      => $pdfPath,
        ]);

        return redirect()->route('waivers.index')->with('success', 'Waiver created!');
    }

    // ── Show ──────────────────────────────────────────────────────
    public function show(Waiver $waiver)
    {
        $waiver = $this->waivers->loadSendsWithClient($waiver);
        return view('waivers.show', compact('waiver'));
    }

    // ── Edit ──────────────────────────────────────────────────────
    public function edit(Waiver $waiver)
    {
        return view('waivers.edit', compact('waiver'));
    }

    // ── Update ────────────────────────────────────────────────────
    public function update(UpdateWaiverRequest $request, Waiver $waiver)
    {
        $fields = json_decode($request->input('fields'), true);

        if (empty($fields)) {
            return back()->withInput()->withErrors(['fields' => 'Please add at least one field.']);
        }

        $data = [
            'title'             => $request->input('title'),
            'fields'            => $fields,
            'require_signature' => $request->has('require_signature'),
        ];

        if ($request->hasFile('pdf_document')) {
            if ($waiver->pdf_document) {
                Storage::disk('public')->delete($waiver->pdf_document);
            }
            $data['pdf_document'] = $request->file('pdf_document')->store('waivers/pdfs', 'public');
        }

        $this->waivers->update($waiver, $data);

        return redirect()->route('waivers.index')->with('success', 'Waiver updated!');
    }

    // ── Destroy ───────────────────────────────────────────────────
    public function destroy(Waiver $waiver)
    {
        if ($waiver->pdf_document) {
            Storage::disk('public')->delete($waiver->pdf_document);
        }
        $this->waivers->delete($waiver);
        return redirect()->route('waivers.index')->with('success', 'Waiver deleted!');
    }

    // ── Show Send Form ────────────────────────────────────────────
    public function sendForm(Waiver $waiver)
    {
        $send = $this->waivers->firstOrCreateShareLink($waiver->id, auth()->id(), Str::random(16));

        $link = url('/sign/' . $send->token);

        return view('waivers.send', compact('waiver', 'link'));
    }

    // ── Send Waiver ───────────────────────────────────────────────
    public function send(SendWaiverRequest $request, Waiver $waiver)
    {
        foreach ($request->emails as $index => $email) {
            $name = $request->names[$index] ?? 'Client';

            $client = $this->waivers->findOrCreateClient($email, auth()->id(), $name);

            $waiverSend = $this->waivers->createSend([
                'waiver_id'    => $waiver->id,
                'sent_by'      => auth()->id(),
                'client_id'    => $client->id,
                'client_name'  => $name,
                'client_email' => $email,
                'token'        => Str::uuid(),
                'status'       => 'pending',
            ]);

            SendWaiverEmail::dispatch($waiverSend);
        }

        $count = count($request->emails);
        return redirect()->route('waivers.index')
            ->with('success', "✅ Waiver sent to {$count} recipient(s)!");
    }

    // ── Sign Page (public) ────────────────────────────────────────
    public function sign($token)
    {
        $send = $this->waivers->findSendByToken($token);

        $waiver = $send->waiver;
        $fields = $waiver->fields ?? [];

        return view('waivers.sign', compact('send', 'waiver', 'fields'));
    }

    // ── Submit Signature ──────────────────────────────────────────
    public function submitSign(SubmitSignRequest $request, $token)
    {
        $send   = $this->waivers->findSendByToken($token);
        $waiver = $send->waiver;

        $this->waivers->markSendSigned($send);

        $responses = $request->input('responses', []);
        $files     = $request->file('responses', []);

        if (is_array($files)) {
            foreach ($files as $fieldId => $file) {
                if ($file && $file->isValid()) {
                    $path = $file->store('submissions/files', 'public');
                    $responses[$fieldId] = $path;
                }
            }
        }

        $this->waivers->createSubmission([
            'waiver_id' => $send->waiver_id,
            'sent_by'   => $send->sent_by,
            'client_id' => $send->client_id,
            'token'     => $send->token,
            'responses' => $responses,
            'signature' => $request->input('signature'),
            'status'    => 'signed',
        ]);

        return view('waivers.signed', compact('send', 'waiver'));
    }

    // ── Download Signed PDF (existing — by token, public) ────────
    public function downloadSignedPdf($token)
    {
        $send       = $this->waivers->findSendByToken($token);
        $waiver     = $send->waiver;
        $submission = $send->submission;

        abort_if($send->sent_by !== auth()->id(), 403);
        abort_if(!$submission, 404, 'No submission found.');

        $fields    = $waiver->fields ?? [];
        $responses = $submission->responses ?? [];
        $signature = $submission->signature ?? null;

        $pdf = Pdf::loadView('waivers.signed-pdf', compact(
            'send', 'waiver', 'fields', 'responses', 'signature'
        ));

        return $pdf->download($waiver->title . '-signed.pdf');
    }

    // ── My Submissions (Analytics Dashboard) ─────────────────────
    public function mySubmissions()
    {
        $userId = auth()->id();

        $submissions = $this->waivers->submissionsForUser($userId);

        $totalSent    = $this->waivers->countSends($userId);
        $totalSigned  = $this->waivers->countSends($userId, 'signed');
        $totalPending = $this->waivers->countSends($userId, 'pending');
        $totalViewed  = $this->waivers->countSends($userId, 'viewed');

        $conversionRate    = $totalSent > 0 ? round(($totalSigned  / $totalSent) * 100, 1) : 0;
        $signedPercentage  = $totalSent > 0 ? round(($totalSigned  / $totalSent) * 100, 0) : 0;
        $pendingPercentage = $totalSent > 0 ? round(($totalPending / $totalSent) * 100, 0) : 0;

        $topWaivers = $this->waivers->topWaiversForUser($userId)
            ->map(function ($waiver) {
                return [
                    'name'            => $waiver->title,
                    'sent'            => $waiver->sends_count,
                    'signed'          => $waiver->signed_count,
                    'pending'         => $waiver->pending_count,
                    'conversion_rate' => $waiver->sends_count > 0
                        ? round(($waiver->signed_count / $waiver->sends_count) * 100, 1)
                        : 0,
                    'last_sent'       => $waiver->sends()->latest()->first()?->created_at?->diffForHumans() ?? 'Never',
                ];
            });

        $submissionsOverTime = $this->waivers->submissionsOverTime($userId);

        $chartLabels = [];
        $chartData   = [];
        for ($i = 29; $i >= 0; $i--) {
            $date           = now()->subDays($i)->format('Y-m-d');
            $chartLabels[]  = now()->subDays($i)->format('M d');
            $submission     = $submissionsOverTime->firstWhere('date', $date);
            $chartData[]    = $submission ? $submission->count : 0;
        }

        return view('dashboard.submissions', compact(
            'submissions', 'totalSent', 'totalSigned', 'totalPending', 'totalViewed',
            'conversionRate', 'signedPercentage', 'pendingPercentage', 'topWaivers',
            'chartLabels', 'chartData'
        ));
    }

    // ── Export submissions as CSV ────────────────────────────
    public function exportSubmissions(Request $request): StreamedResponse
    {
        $userId = auth()->id();
        $status = $request->query('status', 'all');
        $days   = (int) $request->query('days', 0);

        $query = $this->waivers->exportQuery($userId, $status, $days);

        $filename = 'submissions_' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, ['ID', 'Waiver', 'Client', 'Email', 'Status', 'Sent Date', 'Signed Date']);

            $query->chunk(200, function ($rows) use ($handle) {
                foreach ($rows as $row) {
                    fputcsv($handle, [
                        $row->id,
                        $row->waiver?->title       ?? '—',
                        $row->client_name          ?? $row->client?->name ?? '—',
                        $row->client_email         ?? $row->client?->email ?? '—',
                        $row->status,
                        $row->created_at->format('Y-m-d H:i'),
                        $row->signed_at?->format('Y-m-d H:i') ?? '—',
                    ]);
                }
            });

            fclose($handle);
        }, $filename, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    // ── Lazy-load modal responses (JSON) ─────────────────────
    public function submissionResponses(WaiverSend $send): JsonResponse
    {
        abort_if($send->sent_by !== auth()->id(), 403);

        $submission = $send->submission;

        if (! $submission) {
            return response()->json([
                'fields'        => [],
                'responses'     => [],
                'signature_url' => '',
            ]);
        }

        $waiver = $send->waiver;

        $signatureRaw = $submission->signature ?? '';
        $signatureUrl = '';

        if ($signatureRaw) {
            if (str_starts_with($signatureRaw, 'data:image/')) {
                $signatureUrl = $signatureRaw;
            } elseif (Storage::disk('public')->exists($signatureRaw)) {
                $signatureUrl = Storage::disk('public')->url($signatureRaw);
            }
        }

        return response()->json([
            'fields'        => $waiver?->fields ?? [],
            'responses'     => $submission->responses ?? [],
            'signature_url' => $signatureUrl,
        ]);
    }

    // ── Send reminder for a pending WaiverSend ───────────────
    public function remindSubmission(WaiverSend $send): JsonResponse
    {
        abort_if($send->sent_by !== auth()->id(), 403);

        if ($send->status === 'signed') {
            return response()->json(['message' => 'This waiver is already signed.'], 422);
        }

        SendWaiverEmail::dispatch($send);

        return response()->json(['message' => 'Reminder sent.']);
    }

    // ── Download signed PDF by WaiverSend ID (dashboard) ────
    public function downloadSubmission(WaiverSend $send)
    {
        abort_if($send->sent_by !== auth()->id(), 403);
        abort_if($send->status !== 'signed', 403, 'Only signed submissions can be downloaded.');

        $submission = $send->submission;
        abort_if(! $submission, 404, 'No submission found.');

        $waiver    = $send->waiver;
        $fields    = $waiver->fields ?? [];
        $responses = $submission->responses ?? [];
        $signature = $submission->signature ?? null;

        $pdf = Pdf::loadView('waivers.signed-pdf', compact(
            'send', 'waiver', 'fields', 'responses', 'signature'
        ));

        return $pdf->download($waiver->title . '-signed.pdf');
    }

    // ── Templates (this controller's own separate set) ────────────────────────────────────────────
    public function templates()
    {
        // Note: this method still uses its own inline 12-field-rich array,
        // separate from getTemplates() below — preserved exactly as original.
        $templates = [
            ['id' => 'nda',      'title' => 'NDA Agreement',        'icon' => '📝', 'category' => 'Legal',       'color' => 'blue',   'description' => 'Non-disclosure agreement for business partnerships.',  'fields' => [['label' => 'Full Name',        'type' => 'text',      'required' => true], ['label' => 'Company Name',    'type' => 'text',      'required' => true], ['label' => 'Date',             'type' => 'date',      'required' => true], ['label' => 'Signature', 'type' => 'signature', 'required' => true]]],
            ['id' => 'gym',      'title' => 'Gym / Fitness Waiver', 'icon' => '🏋️', 'category' => 'Health',      'color' => 'orange', 'description' => 'Liability waiver for gym and fitness centers.',         'fields' => [['label' => 'Full Name',        'type' => 'text',      'required' => true], ['label' => 'Date of Birth',   'type' => 'date',      'required' => true], ['label' => 'Emergency Contact','type' => 'text',      'required' => true], ['label' => 'Emergency Phone', 'type' => 'text',      'required' => true], ['label' => 'Signature', 'type' => 'signature', 'required' => true]]],
            ['id' => 'service',  'title' => 'Service Agreement',    'icon' => '📋', 'category' => 'Business',    'color' => 'teal',   'description' => 'Agreement for service providers and clients.',           'fields' => [['label' => 'Client Name',      'type' => 'text',      'required' => true], ['label' => 'Email',           'type' => 'email',     'required' => true], ['label' => 'Service Type',     'type' => 'text',      'required' => true], ['label' => 'Start Date',      'type' => 'date',      'required' => true], ['label' => 'Signature', 'type' => 'signature', 'required' => true]]],
            ['id' => 'event',    'title' => 'Event Waiver',         'icon' => '🎉', 'category' => 'Events',      'color' => 'purple', 'description' => 'Liability waiver for event participants.',               'fields' => [['label' => 'Full Name',        'type' => 'text',      'required' => true], ['label' => 'Email',           'type' => 'email',     'required' => true], ['label' => 'Phone',            'type' => 'text',      'required' => false],['label' => 'Event Date',      'type' => 'date',      'required' => true], ['label' => 'Signature', 'type' => 'signature', 'required' => true]]],
            ['id' => 'medical',  'title' => 'Medical Consent',      'icon' => '🏥', 'category' => 'Health',      'color' => 'green',  'description' => 'Medical consent form for patients.',                    'fields' => [['label' => 'Patient Name',     'type' => 'text',      'required' => true], ['label' => 'Date of Birth',   'type' => 'date',      'required' => true], ['label' => 'Allergies',        'type' => 'textarea',  'required' => false],['label' => 'Doctor Name',     'type' => 'text',      'required' => true], ['label' => 'Signature', 'type' => 'signature', 'required' => true]]],
            ['id' => 'property', 'title' => 'Property Inspection',  'icon' => '🏠', 'category' => 'Real Estate', 'color' => 'yellow', 'description' => 'Inspection agreement for property visits.',              'fields' => [['label' => 'Inspector Name',   'type' => 'text',      'required' => true], ['label' => 'Property Address','type' => 'text',      'required' => true], ['label' => 'Inspection Date',  'type' => 'date',      'required' => true], ['label' => 'Owner Name',      'type' => 'text',      'required' => true], ['label' => 'Signature', 'type' => 'signature', 'required' => true]]],
        ];

        $templates = collect($templates);
        return view('waivers.templates', compact('templates'));
    }

    public function useTemplate(Request $request, $templateId)
    {
        $templates = collect($this->waivers->getTemplates());
        $template  = $templates->firstWhere('id', $templateId);

        if (! $template) {
            abort(404);
        }

        $waiver = $this->waivers->create([
            'user_id'           => auth()->id(),
            'title'             => $template['title'],
            'fields'            => $template['fields'],
            'slug'              => Str::slug($template['title']) . '-' . uniqid(),
            'status'            => 'draft',
            'require_signature' => true,
            'content'           => '',
        ]);

        return redirect()->route('waivers.edit', $waiver)
               ->with('success', '✅ Template loaded! Customize and save.');
    }
}