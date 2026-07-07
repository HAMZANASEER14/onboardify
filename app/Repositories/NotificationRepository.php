<?php

namespace App\Repositories;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Task;
use App\Models\User;
use App\Models\WaiverSend;
use App\Repositories\Contracts\NotificationRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class NotificationRepository implements NotificationRepositoryInterface
{
    public function getUnreadMessages(int $userId): Collection
    {
        try {
            $conversationIds = Conversation::where('sender_id', $userId)
                ->orWhere('receiver_id', $userId)
                ->pluck('id');

            return Message::whereIn('conversation_id', $conversationIds)
                ->where('user_id', '!=', $userId)
                ->where('is_read', false)
                ->with('user:id,name')
                ->latest()
                ->take(5)
                ->get()
                ->map(fn ($m) => [
                    'key'   => 'message_' . $m->id,
                    'type'  => 'message',
                    'title' => $m->user->name ?? 'Someone',
                    'body'  => strlen($m->content ?? '') > 50
                                ? substr($m->content, 0, 50) . '...'
                                : ($m->content ?: '📎 Sent an attachment'),
                    'time'  => $m->created_at->diffForHumans(),
                    'url'   => '/chats',
                ]);
        } catch (\Throwable $e) {
            \Log::error('Notifications - messages error: ' . $e->getMessage());
            return collect();
        }
    }

    public function getPendingTasks(int $userId, User $user): Collection
    {
        try {
            return Task::where('assigned_to', $userId)
                ->whereIn('status', ['pending', 'in_progress'])
                ->latest()
                ->take(5)
                ->get()
                ->map(fn ($t) => [
                    'key'   => 'task_' . $t->id,
                    'type'  => 'task',
                    'title' => $t->title,
                    'body'  => 'Status: ' . ucfirst(str_replace('_', ' ', $t->status)),
                    'time'  => $t->created_at->diffForHumans(),
                    'url'   => $user->role === 'admin'
                                ? '/admin/tasks/' . $t->id
                                : '/employee/my-tasks',
                ]);
        } catch (\Throwable $e) {
            \Log::error('Notifications - pending tasks error: ' . $e->getMessage());
            return collect();
        }
    }

    public function getDueTasks(int $userId, User $user): Collection
    {
        try {
            return Task::where('assigned_to', $userId)
                ->whereNotIn('status', ['completed'])
                ->whereNotNull('due_date')
                ->where('due_date', '<=', now()->addDay())
                ->latest()
                ->take(3)
                ->get()
                ->map(fn ($t) => [
                    'key'   => 'due_' . $t->id,
                    'type'  => 'due',
                    'title' => '⏰ Due: ' . $t->title,
                    'body'  => now()->isAfter($t->due_date)
                                ? 'This task is overdue!'
                                : 'Due ' . \Carbon\Carbon::parse($t->due_date)->diffForHumans(),
                    'time'  => $t->due_date,
                    'url'   => $user->role === 'admin'
                                ? '/admin/tasks/' . $t->id
                                : '/employee/my-tasks',
                ]);
        } catch (\Throwable $e) {
            \Log::error('Notifications - due tasks error: ' . $e->getMessage());
            return collect();
        }
    }

    public function getWaiverNotifications(int $userId, User $user): Collection
    {
        try {
            if ($user->role !== 'admin') {
                return collect();
            }

            return WaiverSend::where('sent_by', $userId)
                ->where('status', 'signed')
                ->whereNotNull('signed_at')
                ->where('signed_at', '>=', now()->subDays(7))
                ->latest('signed_at')
                ->take(5)
                ->get()
                ->map(fn ($w) => [
                    'key'   => 'waiver_signed_' . $w->id,
                    'type'  => 'waiver',
                    'title' => '✍️ Waiver Signed',
                    'body'  => ($w->client_name ?? 'A client') . ' signed your waiver',
                    'time'  => \Carbon\Carbon::parse($w->signed_at)->diffForHumans(),
                    'url'   => '/waivers/' . $w->waiver_id,
                ]);
        } catch (\Throwable $e) {
            \Log::error('Notifications - waivers error: ' . $e->getMessage());
            return collect();
        }
    }

    public function getNewMemberNotifications(int $userId, User $user): Collection
    {
        try {
            if ($user->role !== 'admin' || !$user->team_id) {
                return collect();
            }

            return User::where('team_id', $user->team_id)
                ->where('id', '!=', $userId)
                ->where('created_at', '>=', now()->subDays(7))
                ->latest()
                ->take(3)
                ->get()
                ->map(fn ($u) => [
                    'key'   => 'member_' . $u->id,
                    'type'  => 'member',
                    'title' => '👥 New Member Joined',
                    'body'  => $u->name . ' joined your team as ' . ucfirst($u->role ?? 'member'),
                    'time'  => $u->created_at->diffForHumans(),
                    'url'   => '/admin/dashboard',
                ]);
        } catch (\Throwable $e) {
            \Log::error('Notifications - members error: ' . $e->getMessage());
            return collect();
        }
    }

    public function getDismissedKeys(int $userId): Collection
    {
        return DB::table('notification_dismissals')
            ->where('user_id', $userId)
            ->pluck('notification_key');
    }

    public function insertDismissals(array $rows): void
    {
        DB::table('notification_dismissals')->insertOrIgnore($rows);
    }

    public function markChatRead(int $userId): void
    {
        try {
            $conversationIds = Conversation::where('sender_id', $userId)
                ->orWhere('receiver_id', $userId)
                ->pluck('id');

            Message::whereIn('conversation_id', $conversationIds)
                ->where('user_id', '!=', $userId)
                ->where('is_read', false)
                ->update(['is_read' => true]);
        } catch (\Throwable $e) {
            \Log::error('markChatRead error: ' . $e->getMessage());
        }
    }
}