<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSalarySlipRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Any authenticated admin can upload — team scoping happens
        // via the 'user_id' rule below and in the controller/repository.
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $teamId = auth()->user()->team_id;

        return [
            'user_id' => [
                'required',
                Rule::exists('users', 'id')->where(function ($query) use ($teamId) {
                    $query->where('team_id', $teamId)
                          ->where('role', 'employee');
                }),
            ],
            'month' => ['required', 'string', 'max:255'],
            'file'  => ['required', 'file', 'mimes:pdf', 'max:10240'],
        ];
    }

    /**
     * Custom error messages.
     */
    public function messages(): array
    {
        return [
            'user_id.exists' => 'The selected employee is invalid or does not belong to your team.',
            'file.mimes'      => 'The salary slip must be a PDF file.',
            'file.max'        => 'The salary slip must not exceed 10MB.',
        ];
    }
}