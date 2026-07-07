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

        // ✅ 1. Get the role from the form (default to 'employee' if missing)
        $inviteRole = $request->input('role', 'employee');

        // ✅ 2. Security check: Ensure role is ONLY employee or client
        if (!in_array($inviteRole, ['employee', 'client'])) {
            return back()->withErrors(['role' => 'Invalid role selected.'])->withInput();
        }

        if ($request->invite_method === 'manual') {

            $emails = preg_split('/[\s,;]+/', $request->manual_emails);

            foreach ($emails as $email) {
                $email = trim($email);
                if (empty($email)) continue;

                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $this->invites->createFailed($admin->id, $email, $inviteRole, 'Invalid email format', 'manual');
                    continue;
                }

                $invite = $this->invites->createPending($admin->id, $email, $inviteRole, 'manual');

                SendInviteJob::dispatch($invite);
                $count++;
            }

            return back()->with('success', "Success! {$count} {$inviteRole} invites are being sent in the background.");
        }

        // ── CSV INVITE ──
        $file   = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), 'r');
        fgetcsv($handle); // Skip header

        while (($data = fgetcsv($handle, 1000, ',')) !== false) {
            $email = trim($data[0]);
            if (empty($email)) continue;

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->invites->createFailed($admin->id, $email, $inviteRole, 'Invalid email format', 'csv');
                continue;
            }

            $invite = $this->invites->createPending($admin->id, $email, $inviteRole, 'csv');

            SendInviteJob::dispatch($invite);
            $count++;
        }

        fclose($handle);

        return back()->with('success', "Success! {$count} {$inviteRole} invites are being sent in the background.");
    }

    public function index()
    {
        $invites = $this->invites->paginateAll();
        return view('admin.invites.index', compact('invites'));
    }
}