<?php

namespace App\Http\Controllers;

use App\Repositories\Contracts\NotificationRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class NotificationController extends Controller
{
    public function __construct(private NotificationRepositoryInterface $notifications) {}

    public function fetch()
    {
        $userId = auth()->id();
        $user   = auth()->user();

        $unreadMessages          = $this->notifications->getUnreadMessages($userId);
        $pendingTasks            = $this->notifications->getPendingTasks($userId, $user);
        $dueTasks                = $this->notifications->getDueTasks($userId, $user);
        $waiverNotifications     = $this->notifications->getWaiverNotifications($userId, $user);
        $newMemberNotifications  = $this->notifications->getNewMemberNotifications($userId, $user);

        // ── Merge all ──────────────────────────────────────────────
        $all = collect($unreadMessages)
            ->merge($pendingTasks)
            ->merge($dueTasks)
            ->merge($waiverNotifications)
            ->merge($newMemberNotifications)
            ->values();

        // ── ✅ Filter out dismissed notifications ───────────────────
        $dismissedKeys = $this->notifications->getDismissedKeys($userId);

        $all = $all->reject(fn ($n) => $dismissedKeys->contains($n['key']))->values();

        return response()->json([
            'count'         => $all->count(),
            'notifications' => $all,
        ]);
    }

    // ── ✅ NEW: Dismiss all currently-visible notifications ─────────
    public function dismissAll(Request $request)
    {
        $userId = auth()->id();
        $keys   = $request->input('keys', []);

        $rows = collect($keys)->map(fn ($key) => [
            'user_id'          => $userId,
            'notification_key' => $key,
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);

        $this->notifications->insertDismissals($rows->toArray());

        return response()->json(['success' => true]);
    }

    public function markChatRead()
    {
        $this->notifications->markChatRead(auth()->id());

        return response()->json(['success' => true]);
    }
}