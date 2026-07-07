<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::where('assigned_to', auth()->id())
            ->with(['assignedBy'])
            ->latest()
            ->get();

        return view('employee.tasks.index', compact('tasks'));
    }

    public function updateStatus(Request $request, Task $task)
    {
        // Ensure employee can only update their own tasks
        abort_if($task->assigned_to !== auth()->id(), 403);

        $request->validate([
            'status' => 'required|in:pending,in_progress,completed',
        ]);

        $task->update(['status' => $request->status]);

        return back()->with('success', 'Task status updated!');
    }
}