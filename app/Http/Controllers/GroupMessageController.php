<?php
namespace App\Http\Controllers;

use App\Events\GroupMessageSent;
use App\Http\Requests\StoreGroupMessageRequest;
use App\Models\Group;
use App\Repositories\Contracts\GroupRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class GroupMessageController extends Controller
{
    public function __construct(private GroupRepositoryInterface $groups) {}

    public function store(StoreGroupMessageRequest $request, Group $group)
    {
        $message = $this->groups->createMessage(
            $group,
            Auth::id(),
            $request->validated(),
            $request->file('attachment')
        );

        $message->load('user');

        broadcast(new GroupMessageSent($message))->toOthers();

        return response()->json(['status' => 'ok', 'message' => $message]);
    }
}