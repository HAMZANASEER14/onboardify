<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWaiverRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'        => 'required|string|max:255',
            'fields'       => 'required|string',
            'pdf_document' => 'nullable|file|mimes:pdf|max:10240',
        ];
    }
}