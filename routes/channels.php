<?php

use App\Models\Conversation;
use App\Models\Group;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('conversation.{conversationId}', function ($user, $conversationId) {
    $conv = Conversation::find($conversationId);

    if (! $conv) return false;

    // Only allow if this user is the sender OR receiver
    return $conv->sender_id === $user->id
        || $conv->receiver_id === $user->id;
});
Broadcast::channel('group.{groupId}', function ($user, $groupId) {
    $isMember = Group::findOrFail($groupId)
                     ->members()
                     ->where('user_id', $user->id)
                     ->exists();

    if ($isMember) {
        return ['id' => $user->id, 'name' => $user->name];
    }

    return false;
});