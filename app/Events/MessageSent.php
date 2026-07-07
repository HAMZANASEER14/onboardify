<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow
{
    use SerializesModels;

    public function __construct(public Message $message) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel(
                'conversation.' . $this->message->conversation_id
            ),
        ];
    }

    public function broadcastAs(): string
    {
        return 'message.sent';
    }

    public function broadcastWith(): array
    {
        return [
            'id'              => $this->message->id,
            'conversation_id' => $this->message->conversation_id,
            'user_id'         => $this->message->user_id,
            'sender_name'     => $this->message->user->name,
            'content'         => $this->message->content,
            'created_at'      => $this->message->created_at->format('h:i A'),
            'file_path' => $this->message->file_path
                ? asset('storage/' . $this->message->file_path)
                : null,
'file_name' => $this->message->file_name,
'file_type' => $this->message->file_type,
        ];
    }
}