<?php

namespace App\Repositories;

use App\Models\Task;
use App\Models\User;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Illuminate\Support\Collection;

class TaskRepository implements TaskRepositoryInterface
{
    public function getAllForTeam(int $teamId): Collection
    {
        return Task::where('team_id', $teamId)
            ->with(['assignedTo', 'assignedBy'])
            ->latest()
            ->get();
    }

    public function getTeamEmployees(int $teamId): Collection
    {
        return User::where('team_id', $teamId)
            ->where('role', 'employee')
            ->get();
    }

    public function create(array $data): Task
    {
        return Task::create($data);
    }

    public function loadRelations(Task $task): Task
    {
        return $task->load(['assignedTo', 'assignedBy']);
    }

    public function updateStatus(Task $task, string $status): Task
    {
        $task->update(['status' => $status]);

        return $task;
    }

    public function delete(Task $task): void
    {
        $task->delete();
    }
}