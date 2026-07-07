<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BulkInviteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        if ($this->input('invite_method') === 'manual') {
            return [
                'manual_emails' => 'required|string',
            ];
        }

        return [
            'csv_file' => 'required|mimes:csv,txt|max:2048',
        ];
    }
}