<?php

namespace App\Repositories\Contracts;

use App\Models\Task;
use Illuminate\Support\Collection;

interface TaskRepositoryInterface
{
    /**
     * Get all tasks for a given team, with relations loaded.
     */
    public function getAllForTeam(int $teamId): Collection;

    /**
     * Get all employees belonging to a team (candidates for assignment).
     */
    public function getTeamEmployees(int $teamId): Collection;

    /**
     * Create a new task.
     */
    public function create(array $data): Task;

    /**
     * Load relations needed for the show view.
     */
    public function loadRelations(Task $task): Task;

    /**
     * Update a task's status.
     */
    public function updateStatus(Task $task, string $status): Task;

    /**
     * Delete a task.
     */
    public function delete(Task $task): void;
}