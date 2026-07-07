<?php
namespace App\Mail;

use App\Models\Invite;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TeamInviteMail extends Mailable
{
    use Queueable, SerializesModels;

    public $invite;

    public function __construct(Invite $invite)
    {
        $this->invite = $invite;
    }

    public function build()
    {
        $admin = $this->invite->admin;

        $companyCode = $admin->team ? $admin->team->invite_code : '';
        
$inviteLink = route('register.prepare', [
    'code'  => $companyCode,
    'email' => $this->invite->email
]);
    }
}