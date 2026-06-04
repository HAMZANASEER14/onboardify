<?php
namespace App\Events;

use App\Models\GroupMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GroupMessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public GroupMessage $groupMessage)
    {
        //
    }

    public function broadcastOn(): array
    {
        // PresenceChannel → all group members get notified
        return [
            new PresenceChannel('group.' . $this->groupMessage->group_id),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'id'         => $this->groupMessage->id,
            'group_id'   => $this->groupMessage->group_id,
            'message'    => $this->groupMessage->message,
            'attachment' => $this->groupMessage->attachment,
            'user'       => [
                'id'   => $this->groupMessage->user->id,
                'name' => $this->groupMessage->user->name,
            ],
            'created_at' => $this->groupMessage->created_at->diffForHumans(),
        ];
    }

    public function broadcastAs(): string
    {
        return 'group.message';
    }
}