<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BulkInviteRequest;
use App\Jobs\SendInviteJob;
use App\Repositories\Contracts\InviteRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class BulkInviteController extends Controller
{
    public function __construct(protected InviteRepositoryInterface $invites)
    {
    }

    public function show()
    {
        return view('admin.bulk-invite');
    }

    public function process(BulkInviteRequest $request)
    {
        $admin = Auth::user();
        $count = 0;
         $invalidEmails   = [];
    $duplicateEmails = [];

        // ✅ 1. Get the role from the form (default to 'employee' if missing)
        $inviteRole = $request->input('role', 'employee');

        // ✅ 2. Security check: Ensure role is ONLY employee or client
        if (!in_array($inviteRole, ['employee', 'client'])) {
            return back()->withErrors(['role' => 'Invalid role selected.'])->withInput();
        }

        if ($request->invite_method === 'manual') {

            $emails = preg_split('/[\s,;]+/', $request->manual_emails);
$row = 0;
            foreach ($emails as $email) {
                $row++;
                $email = trim($email);
                if (empty($email)) continue;

                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $this->invites->createFailed($admin->id, $email, $inviteRole, 'Invalid email format', 'manual');
                                    $invalidEmails[] = ['email' => $email, 'reason' => 'Invalid email format'];

                    continue;
                }
                        if ($this->invites->alreadyInvited($admin->id, $email)) {
                $duplicateEmails[] = ['email' => $email, 'row' => $row];
                continue;
            }

                $invite = $this->invites->createPending($admin->id, $email, $inviteRole, 'manual');

                SendInviteJob::dispatch($invite);
                $count++;
            }

    } else {

        $file   = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), 'r');
        fgetcsv($handle); // Skip header
 $row = 1;
        while (($data = fgetcsv($handle, 1000, ',')) !== false) {
            $row++;
            $email = trim($data[0] ?? '');
            if (empty($email)) continue;
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->invites->createFailed($admin->id, $email, $inviteRole, 'Invalid email format', 'csv');
                $invalidEmails[] = ['email' => $email, 'reason' => 'Invalid email format'];
                continue;
            }

            if ($this->invites->alreadyInvited($admin->id, $email)) {
                $duplicateEmails[] = ['email' => $email, 'row' => $row];
                continue;
            }

            $invite = $this->invites->createPending($admin->id, $email, $inviteRole, 'csv');
            SendInviteJob::dispatch($invite);
            $count++;
        }

        fclose($handle);
    }

            return back()
        ->with('success', "Success! {$count} {$inviteRole} invites are being sent in the background.")
        ->with('invalid_emails', $invalidEmails)
        ->with('duplicate_emails', $duplicateEmails);
    }

    public function index()
    {
        $invites = $this->invites->paginateForAdmin(Auth::id());
        return view('admin.invites.index', compact('invites'));
    }
    public function resend(Invite $invite)
{
    $this->invites->resend($invite);
    return back()->with('success', 'Invite resent to ' . $invite->email);
}
}