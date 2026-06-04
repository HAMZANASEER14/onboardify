<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model
{
    protected $fillable = ['sender_id', 'receiver_id'];

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class)->orderBy('created_at');
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function otherUser(int $myId): User
    {
        return $this->sender_id === $myId
            ? $this->receiver
            : $this->sender;
    }

public static function between(int $a, int $b): self
{
    // First try to find existing conversation
    $conversation = self::where(function ($q) use ($a, $b) {
        $q->where('sender_id', $a)->where('receiver_id', $b);
    })->orWhere(function ($q) use ($a, $b) {
        $q->where('sender_id', $b)->where('receiver_id', $a);
    })->first(); // ← first() not firstOrCreate()

    // Only create if not found
    if (!$conversation) {
        $conversation = self::create([
            'sender_id'   => $a,
            'receiver_id' => $b,
        ]);
    }

    return $conversation;
}
public function lastMessage()
{
    return $this->hasOne(Message::class)->latestOfMany();
}
}