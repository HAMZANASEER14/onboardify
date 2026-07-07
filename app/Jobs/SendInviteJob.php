<?php

namespace App\Jobs;

use App\Mail\TeamInviteMail;
use App\Models\Invite;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendInviteJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $invite;

    public function __construct(Invite $invite)
    {
        $this->invite = $invite;
    }

   public function handle()
{
    try {
        Mail::to($this->invite->email)->send(new TeamInviteMail($this->invite));

        $this->invite->update([
            'status' => 'sent',
        ]);
    } catch (\Exception $e) {
        $this->invite->update([
            'status' => 'failed',
            'failure_reason' => $e->getMessage(),
        ]);

        throw $e;
    }
}
}