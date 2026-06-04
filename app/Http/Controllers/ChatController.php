<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    // Show list of all users to start a chat with
    public function index()
    {
        $users = User::where('id', '!=', auth()->id())->get();
        return view('chat.index', compact('users'));
    }

    // Open a conversation with a specific user
    public function show(User $user)
    {
        $conversation = Conversation::between(
            auth()->id(),
            $user->id
        );

        // Mark their messages as read
        $conversation->messages()
            ->where('user_id', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $messages = $conversation->messages()->with('user')->get();

        return view('chat.show', compact(
            'conversation', 'messages', 'user'
        ));

       
    }

    // Receive and save a new message
    public function send(Request $request, Conversation $conversation)
    {
        // Make sure this user belongs to this conversation
        abort_if(
            $conversation->sender_id !== auth()->id() &&
            $conversation->receiver_id !== auth()->id(),
            403
        );

        $request->validate([
            'content' => 'required|string|max:2000',
        ]);

        // Save to database
        $message = $conversation->messages()->create([
            'user_id' => auth()->id(),
           'content' => $request->input('content'),
        ]);

        $message->load('user');

        // Push to both users via Reverb
        broadcast(new MessageSent($message));

        return response()->json([
            'id'          => $message->id,
            'content'     => $message->content,
            'sender_name' => $message->user->name,
            'created_at'  => $message->created_at->format('h:i A'),
        ]);
    }
    public function typing(Conversation $conversation)
{
    abort_if(
        $conversation->sender_id !== auth()->id() &&
        $conversation->receiver_id !== auth()->id(),
        403
    );

    broadcast(new \App\Events\UserTyping(
        $conversation->id,
        auth()->id(),
        auth()->user()->name
    ))->toOthers(); // don't send it back to yourself

    return response()->json(['status' => 'ok']);
}
}