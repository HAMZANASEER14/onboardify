<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTaskStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * Ensures the task being updated belongs to the admin's team.
     * The {task} route parameter is available via $this->route('task').
     */
    public function authorize(): bool
    {
        $task = $this->route('task');

        return auth()->check()
            && $task
            && $task->team_id === auth()->user()->team_id;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in(['pending', 'in_progress', 'completed'])],
        ];
    }
}