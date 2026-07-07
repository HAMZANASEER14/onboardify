<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DismissNotificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'keys'   => 'array',
            'keys.*' => 'string',
        ];
    }
}