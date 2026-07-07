<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskStatusRequest;
use App\Models\Task;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function __construct(
        protected TaskRepositoryInterface $tasks
    ) {
    }

    public function index(): View
    {
        $user = auth()->user();

        $tasks = $this->tasks->getAllForTeam($user->team_id);

        return view('admin.tasks.index', compact('tasks'));
    }

    public function create(): View
    {
        $user = auth()->user();

        $employees = $this->tasks->getTeamEmployees($user->team_id);

        return view('admin.tasks.create', compact('employees'));
    }

    public function store(StoreTaskRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $user      = auth()->user();

        $this->tasks->create([
            'team_id'     => $user->team_id,
            'assigned_to' => $validated['assigned_to'],
            'assigned_by' => $user->id,
            'title'       => $validated['title'],
            'description' => $validated['description'] ?? null,
            'status'      => 'pending',
            'due_date'    => $validated['due_date'] ?? null,
        ]);

        return redirect()->route('admin.tasks.index')
            ->with('success', 'Task assigned successfully!');
    }

    public function show(Task $task): View
    {
        if ($task->team_id !== auth()->user()->team_id) {
            abort(403);
        }

        $task = $this->tasks->loadRelations($task);

        return view('admin.tasks.show', compact('task'));
    }

    /**
     * Team ownership is checked inside UpdateTaskStatusRequest::authorize().
     */
    public function updateStatus(UpdateTaskStatusRequest $request, Task $task): RedirectResponse
    {
        $validated = $request->validated();

        $this->tasks->updateStatus($task, $validated['status']);

        return back()->with('success', 'Task status updated!');
    }

    public function destroy(Task $task): RedirectResponse
    {
        if ($task->team_id !== auth()->user()->team_id) {
            abort(403);
        }

        $this->tasks->delete($task);

        return redirect()->route('admin.tasks.index')
            ->with('success', 'Task deleted successfully!');
    }
}