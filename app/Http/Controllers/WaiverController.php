<?php

namespace App\Http\Controllers;

use App\Jobs\SendWaiverEmail;
use App\Models\Waiver;
use App\Models\Client;
use App\Models\WaiverSend;
use App\Models\WaiverSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;


class WaiverController extends Controller
{
    // ── Index ─────────────────────────────────────────────────────
    public function index()
    {
        $waivers = Waiver::where('user_id', auth()->id())
                         ->latest()
                         ->paginate(10);
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
            'title'        => 'required|string|max:255',
            'fields'       => 'required|string',
            'pdf_document' => 'nullable|file|mimes:pdf|max:10240', // max 10MB
        ]);

        $fields = json_decode($request->input('fields'), true);

        if (empty($fields)) {
            return back()->withInput()->withErrors(['fields' => 'Please add at least one field.']);
        }

        // Handle PDF upload
        $pdfPath = null;
        if ($request->hasFile('pdf_document')) {
            $pdfPath = $request->file('pdf_document')->store('waivers/pdfs', 'public');
        }

        Waiver::create([
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
        $waiver->load('sends');
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
            'title'        => 'required|string|max:255',
            'fields'       => 'required|string',
            'pdf_document' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $fields = json_decode($request->input('fields'), true);

        $data = [
            'title'             => $request->input('title'),
            'fields'            => $fields,
            'require_signature' => $request->has('require_signature'),
        ];

        // Handle PDF upload on update
        if ($request->hasFile('pdf_document')) {
            // Delete old PDF if exists
            if ($waiver->pdf_document) {
                Storage::disk('public')->delete($waiver->pdf_document);
            }
            $data['pdf_document'] = $request->file('pdf_document')->store('waivers/pdfs', 'public');
        }

        $waiver->update($data);

        return redirect()->route('waivers.index')->with('success', 'Waiver updated!');
    }

    // ── Destroy ───────────────────────────────────────────────────
    public function destroy(Waiver $waiver)
    {
        // Delete PDF if exists
        if ($waiver->pdf_document) {
            Storage::disk('public')->delete($waiver->pdf_document);
        }
        $waiver->delete();
        return redirect()->route('waivers.index')->with('success', 'Waiver deleted!');
    }

    // ── Show Send Form ────────────────────────────────────────────
    public function sendForm(Waiver $waiver)
    {
         $link = route('waivers.sign', $waiver->slug);
    return view('waivers.send', compact('waiver', 'link'));
        // return view('waivers.send', compact('waiver'));
    }

    // ── Send Waiver ───────────────────────────────────────────────
   public function send(Request $request, Waiver $waiver)
{
    $request->validate([
        'emails'   => 'required|array|min:1',
        'emails.*' => 'required|email|max:255',
        'names'    => 'required|array|min:1',
        'names.*'  => 'required|string|max:255',
    ]);

    foreach ($request->emails as $index => $email) {
        $name = $request->names[$index] ?? 'Client';

        $client = Client::firstOrCreate(
            ['email' => $email, 'user_id' => auth()->id()],
            ['name'  => $name, 'status' => 'active']
        );

        $waiverSend = WaiverSend::create([
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
        $send   = WaiverSend::where('token', $token)->firstOrFail();
        $waiver = $send->waiver;
        $fields = $waiver->fields ?? [];

        return view('waivers.sign', compact('send', 'waiver', 'fields'));
    }

    // ── Submit Signature ──────────────────────────────────────────
    public function submitSign(Request $request, $token)
    {
        $send   = WaiverSend::where('token', $token)->firstOrFail();
        $waiver = $send->waiver;

        $request->validate([
            'signer_name'  => 'sometimes|string|max:255',
            'signer_email' => 'sometimes|email|max:255',
        ]);

        $send->update([
            'status'    => 'signed',
            'signed_at' => now(),
        ]);

        // Handle file uploads from client (pdf_upload, image, file fields)
        $responses = $request->except(['_token', 'signer_name', 'signer_email', 'signature']);

        // Store any uploaded files
        foreach ($request->allFiles() as $key => $file) {
            if (str_starts_with($key, 'responses')) {
                $path = $file->store('submissions/files', 'public');
                // Replace file object with stored path in responses
                $fieldKey = str_replace(['responses[', ']'], '', $key);
                $responses[$fieldKey] = $path;
            }
        }

        WaiverSubmission::create([
            'waiver_id' => $send->waiver_id,
            'sent_by'   => $send->sent_by,
            'client_id' => $send->client_id,
            'token'     => $token,
            'responses' => json_encode($responses),
            'signature' => $request->signature ?? null,
            'status'    => 'signed',
        ]);

        return view('waivers.signed', compact('send', 'waiver'));
    }

    // ── My Submissions ────────────────────────────────────────────
   // ── My Submissions (Analytics Dashboard) ─────────────────────────
public function mySubmissions()
{
    // Get all submissions for the user
$submissions = WaiverSend::with(['waiver', 'client'])
                ->where('sent_by', auth()->id())
                ->latest()
                ->paginate(15);

    // ── Analytics Data ──
    $userId = auth()->id();
    
    // Total counts
    $totalSent = WaiverSend::where('sent_by', $userId)->count();
    $totalSigned = WaiverSend::where('sent_by', $userId)->where('status', 'signed')->count();
    $totalPending = WaiverSend::where('sent_by', $userId)->where('status', 'pending')->count();
    $totalViewed = WaiverSend::where('sent_by', $userId)->where('status', 'viewed')->count();
    
    // Conversion rate
    $conversionRate = $totalSent > 0 ? round(($totalSigned / $totalSent) * 100, 1) : 0;
    
    // Percentages for progress bars
    $signedPercentage = $totalSent > 0 ? round(($totalSigned / $totalSent) * 100, 0) : 0;
    $pendingPercentage = $totalSent > 0 ? round(($totalPending / $totalSent) * 100, 0) : 0;
    
    // Top performing waivers
    $topWaivers = Waiver::where('user_id', $userId)
        ->withCount(['sends', 'sends as signed_count' => function($q) {
            $q->where('status', 'signed');
        }, 'sends as pending_count' => function($q) {
            $q->where('status', 'pending');
        }])
        ->having('sends_count', '>', 0)
        ->orderByDesc('sends_count')
        ->take(5)
        ->get()
        ->map(function($waiver) {
            $conversion = $waiver->sends_count > 0 
                ? round(($waiver->signed_count / $waiver->sends_count) * 100, 1) 
                : 0;
            return [
                'name' => $waiver->title,
                'sent' => $waiver->sends_count,
                'signed' => $waiver->signed_count,
                'pending' => $waiver->pending_count,
                'conversion_rate' => $conversion,
                'last_sent' => $waiver->sends()->latest()->first()?->created_at?->diffForHumans() ?? 'Never',
            ];
        });
    
    // Submissions over time (last 30 days)
    $submissionsOverTime = WaiverSubmission::where('sent_by', $userId)
        ->where('created_at', '>=', now()->subDays(30))
        ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
        ->groupBy('date')
        ->orderBy('date')
        ->get();
    
    // Format for chart
    $chartLabels = [];
    $chartData = [];
    for ($i = 29; $i >= 0; $i--) {
        $date = now()->subDays($i)->format('Y-m-d');
        $chartLabels[] = now()->subDays($i)->format('M d');
        $submission = $submissionsOverTime->firstWhere('date', $date);
        $chartData[] = $submission ? $submission->count : 0;
    }

    return view('dashboard.submissions', compact(
        'submissions',
        'totalSent',
        'totalSigned',
        'totalPending',
        'totalViewed',
        'conversionRate',
        'signedPercentage',
        'pendingPercentage',
        'topWaivers',
        'chartLabels',
        'chartData'
    ));
}
public function templates()
{
    $templates = [
        [
            'id' => 'nda',
            'title' => 'NDA Agreement',
            'icon' => '📝',
            'category' => 'Legal',
            'color' => 'blue',
            'description' => 'Non-disclosure agreement for business partnerships.',
            'fields' => [
                ['label' => 'Full Name', 'type' => 'text', 'required' => true],
                ['label' => 'Company Name', 'type' => 'text', 'required' => true],
                ['label' => 'Date', 'type' => 'date', 'required' => true],
                ['label' => 'Signature', 'type' => 'signature', 'required' => true],
            ]
        ],
        [
            'id' => 'gym',
            'title' => 'Gym / Fitness Waiver',
            'icon' => '🏋️',
            'category' => 'Health',
            'color' => 'orange',
            'description' => 'Liability waiver for gym and fitness centers.',
            'fields' => [
                ['label' => 'Full Name', 'type' => 'text', 'required' => true],
                ['label' => 'Date of Birth', 'type' => 'date', 'required' => true],
                ['label' => 'Emergency Contact', 'type' => 'text', 'required' => true],
                ['label' => 'Emergency Phone', 'type' => 'text', 'required' => true],
                ['label' => 'Signature', 'type' => 'signature', 'required' => true],
            ]
        ],
        [
            'id' => 'service',
            'title' => 'Service Agreement',
            'icon' => '📋',
            'category' => 'Business',
            'color' => 'teal',
            'description' => 'Agreement for service providers and clients.',
            'fields' => [
                ['label' => 'Client Name', 'type' => 'text', 'required' => true],
                ['label' => 'Email', 'type' => 'email', 'required' => true],
                ['label' => 'Service Type', 'type' => 'text', 'required' => true],
                ['label' => 'Start Date', 'type' => 'date', 'required' => true],
                ['label' => 'Signature', 'type' => 'signature', 'required' => true],
            ]
        ],
        [
            'id' => 'event',
            'title' => 'Event Waiver',
            'icon' => '🎉',
            'category' => 'Events',
            'color' => 'purple',
            'description' => 'Liability waiver for event participants.',
            'fields' => [
                ['label' => 'Full Name', 'type' => 'text', 'required' => true],
                ['label' => 'Email', 'type' => 'email', 'required' => true],
                ['label' => 'Phone', 'type' => 'text', 'required' => false],
                ['label' => 'Event Date', 'type' => 'date', 'required' => true],
                ['label' => 'Signature', 'type' => 'signature', 'required' => true],
            ]
        ],
        [
            'id' => 'medical',
            'title' => 'Medical Consent',
            'icon' => '🏥',
            'category' => 'Health',
            'color' => 'green',
            'description' => 'Medical consent form for patients.',
            'fields' => [
                ['label' => 'Patient Name', 'type' => 'text', 'required' => true],
                ['label' => 'Date of Birth', 'type' => 'date', 'required' => true],
                ['label' => 'Allergies', 'type' => 'textarea', 'required' => false],
                ['label' => 'Doctor Name', 'type' => 'text', 'required' => true],
                ['label' => 'Signature', 'type' => 'signature', 'required' => true],
            ]
        ],
        [
            'id' => 'property',
            'title' => 'Property Inspection',
            'icon' => '🏠',
            'category' => 'Real Estate',
            'color' => 'yellow',
            'description' => 'Inspection agreement for property visits.',
            'fields' => [
                ['label' => 'Inspector Name', 'type' => 'text', 'required' => true],
                ['label' => 'Property Address', 'type' => 'text', 'required' => true],
                ['label' => 'Inspection Date', 'type' => 'date', 'required' => true],
                ['label' => 'Owner Name', 'type' => 'text', 'required' => true],
                ['label' => 'Signature', 'type' => 'signature', 'required' => true],
            ]
        ],
    ];

    $templates = collect($templates);
return view('waivers.templates', compact('templates'));
}

public function useTemplate(Request $request, $templateId)
{
    $templates = collect($this->getTemplates());
    $template = $templates->firstWhere('id', $templateId);

    if (!$template) abort(404);

    $waiver = Waiver::create([
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

private function getTemplates(): array
{
    return [
    
        ['id'=>'nda','title'=>'NDA Agreement','fields'=>[['label'=>'Full Name','type'=>'text','required'=>true],['label'=>'Company Name','type'=>'text','required'=>true],['label'=>'Date','type'=>'date','required'=>true],['label'=>'Signature','type'=>'signature','required'=>true]]],
        ['id'=>'gym','title'=>'Gym / Fitness Waiver','fields'=>[['label'=>'Full Name','type'=>'text','required'=>true],['label'=>'Date of Birth','type'=>'date','required'=>true],['label'=>'Emergency Contact','type'=>'text','required'=>true],['label'=>'Emergency Phone','type'=>'text','required'=>true],['label'=>'Signature','type'=>'signature','required'=>true]]],
        ['id'=>'service','title'=>'Service Agreement','fields'=>[['label'=>'Client Name','type'=>'text','required'=>true],['label'=>'Email','type'=>'email','required'=>true],['label'=>'Service Type','type'=>'text','required'=>true],['label'=>'Start Date','type'=>'date','required'=>true],['label'=>'Signature','type'=>'signature','required'=>true]]],
        ['id'=>'event','title'=>'Event Waiver','fields'=>[['label'=>'Full Name','type'=>'text','required'=>true],['label'=>'Email','type'=>'email','required'=>true],['label'=>'Phone','type'=>'text','required'=>false],['label'=>'Event Date','type'=>'date','required'=>true],['label'=>'Signature','type'=>'signature','required'=>true]]],
        ['id'=>'medical','title'=>'Medical Consent','fields'=>[['label'=>'Patient Name','type'=>'text','required'=>true],['label'=>'Date of Birth','type'=>'date','required'=>true],['label'=>'Allergies','type'=>'textarea','required'=>false],['label'=>'Doctor Name','type'=>'text','required'=>true],['label'=>'Signature','type'=>'signature','required'=>true]]],
        ['id'=>'property','title'=>'Property Inspection','fields'=>[['label'=>'Inspector Name','type'=>'text','required'=>true],['label'=>'Property Address','type'=>'text','required'=>true],['label'=>'Inspection Date','type'=>'date','required'=>true],['label'=>'Owner Name','type'=>'text','required'=>true],['label'=>'Signature','type'=>'signature','required'=>true]]],
    ];
}
}