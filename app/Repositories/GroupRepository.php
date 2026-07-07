<?php

namespace App\Repositories;

use App\Models\Group;
use App\Models\User;
use App\Repositories\Contracts\GroupRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use App\Models\GroupMessage;

class GroupRepository implements GroupRepositoryInterface
{
    public function getForUser(User $user): Collection
    {
        return $user->groups()->withCount('members')
                    ->with('latestMessage.user')
                    ->get();
    }

    public function create(array $validated, User $creator): Group
    {
        // Defense in depth: re-verify every selected member is actually
        // on the same team, even though the Rule::exists above already
        // scopes it — never trust array input blindly.
        $validMemberIds = User::where('team_id', $creator->team_id)
            ->whereIn('id', $validated['member_ids'])
            ->pluck('id')
            ->toArray();

        $group = Group::create([
            'name'        => $validated['name'],
            'description' => $validated['description'] ?? null,
            'created_by'  => $creator->id,
        ]);

        // Creator joins as group-admin
        $group->users()->attach($creator->id, [
            'role'      => 'admin',
            'joined_at' => now(),
        ]);

        // Selected teammates join as members (skip creator if they
        // accidentally included themselves in the selection)
        foreach ($validMemberIds as $userId) {
            if ($userId === $creator->id) {
                continue;
            }
            $group->users()->attach($userId, [
                'role'      => 'member',
                'joined_at' => now(),
            ]);
        }

        return $group;
    }

    public function addMember(Group $group, int $userId): void
    {
        $group->members()->firstOrCreate([
            'user_id' => $userId,
        ], ['role' => 'member']);
    }

    public function removeMember(Group $group, User $user): void
    {
        $group->members()->where('user_id', $user->id)->delete();
    }

    public function delete(Group $group): void
    {
        $group->delete();
    }
    public function createMessage(Group $group, int $userId, array $data, $attachment = null): GroupMessage
{
    $payload = [
        'group_id' => $group->id,
        'user_id'  => $userId,
        'message'  => $data['message'] ?? null,
    ];

    if ($attachment) {
        $payload['attachment'] = $attachment->store('group-attachments', 'public');
    }

    return GroupMessage::create($payload);
}
}