<?php

namespace App\Jobs;

use App\Models\WaiverSend;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SendWaiverEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60;

    public function __construct(public WaiverSend $waiverSend) {}

    public function handle(): void
    {
        $send = $this->waiverSend->load(['waiver', 'client']);

        Mail::send('emails.waiver-invite', ['waiverSend' => $send], function ($message) use ($send) {
            $message->to($send->client_email, $send->client_name)
                    ->subject('Please sign: ' . $send->waiver->title);

            // ── Attach PDF if exists ──
            if ($send->waiver->pdf_document) {
                $path = Storage::disk('public')->path($send->waiver->pdf_document);

                if (file_exists($path)) {
                    $message->attach($path, [
                        'as'   => $send->waiver->title . '.pdf',
                        'mime' => 'application/pdf',
                    ]);
                }
            }
        });
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('WaiverSend email failed', [
            'waiver_send_id' => $this->waiverSend->id,
            'error'          => $exception->getMessage(),
        ]);
    }
}