<?php
namespace App\Http\Controllers;
use App\Events\MessageSent;
use App\Http\Requests\SendMessageRequest;
use App\Models\Conversation;
use App\Models\User;
use App\Repositories\Contracts\ChatRepositoryInterface;
use Illuminate\Http\Request;
class ChatController extends Controller
{
    public function __construct(private ChatRepositoryInterface $chats) {}

    public function index()
    {
        $authId = auth()->id();
        $users  = User::where('id', '!=', $authId)->get();
        $unreadCounts = $this->chats->getUnreadCounts($authId);

        return view('chat.index', compact('users', 'unreadCounts'));
    }

    public function show(User $user)
    {
        $conversation = $this->chats->getConversationBetween(auth()->id(), $user->id);

        $this->chats->markMessagesRead($conversation, $user->id);

        $messages = $this->chats->getMessagesWithSender($conversation);

        return view('chat.show', compact('conversation', 'messages', 'user'));
    }

    public function send(SendMessageRequest $request, Conversation $conversation)
    {
        $message = $this->chats->storeMessage(
            $conversation,
            auth()->id(),
            $request->validated(),
            $request->file('file')
        );

        $message->load('user');

        broadcast(new MessageSent($message))->toOthers();

        return response()->json([
            'id'          => $message->id,
            'content'     => $message->content,
            'sender_name' => $message->user->name,
            'created_at'  => $message->created_at->format('h:i A'),
            'file_path'   => $message->file_path ? asset('storage/' . $message->file_path) : null,
            'file_name'   => $message->file_name,
            'file_type'   => $message->file_type,
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
        ))->toOthers();

        return response()->json(['status' => 'ok']);
    }
}