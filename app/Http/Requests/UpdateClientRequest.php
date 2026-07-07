<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Ensure the client belongs to the logged-in user
        return $this->route('client')?->user_id === auth()->id();
    }

    public function rules(): array
    {
        return [
            'name'   => 'required|string|max:255',
            'email'  => 'required|email|max:255',
            'status' => 'nullable|string|in:active,inactive',
        ];
    }
}