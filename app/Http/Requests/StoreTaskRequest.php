<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $teamId = auth()->user()->team_id;

        return [
            'assigned_to' => [
                'required',
                Rule::exists('users', 'id')->where(function ($query) use ($teamId) {
                    $query->where('team_id', $teamId)
                          ->where('role', 'employee');
                }),
            ],
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'due_date'    => ['nullable', 'date', 'after:today'],
        ];
    }

    /**
     * Custom error messages.
     */
    public function messages(): array
    {
        return [
            'assigned_to.exists' => 'The selected employee is invalid or does not belong to your team.',
            'due_date.after'     => 'The due date must be a date after today.',
        ];
    }
}