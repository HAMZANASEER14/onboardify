<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreGroupMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        $group = $this->route('group');

        return $group->members()->where('user_id', Auth::id())->exists();
    }

    public function rules(): array
    {
        return [
            'message'    => 'required_without:attachment|string|nullable',
            'attachment' => 'nullable|file|max:5120',
        ];
    }
}