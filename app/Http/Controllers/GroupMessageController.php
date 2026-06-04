<?php
namespace App\Http\Controllers;

use App\Events\GroupMessageSent;
use App\Models\Group;
use App\Models\GroupMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupMessageController extends Controller
{
    public function store(Request $request, Group $group)
    {
        // Only members can send
        abort_unless(
            $group->members()->where('user_id', Auth::id())->exists(),
            403
        );

        $request->validate([
            'message'    => 'required_without:attachment|string|nullable',
            'attachment' => 'nullable|file|max:5120',
        ]);

        $data = [
            'group_id' => $group->id,
            'user_id'  => Auth::id(),
            'message'  => $request->message,
        ];

        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')
                                          ->store('group-attachments', 'public');
        }

        $message = GroupMessage::create($data);
        $message->load('user');

        broadcast(new GroupMessageSent($message))->toOthers();

        return response()->json(['status' => 'ok', 'message' => $message]);
    }
}