<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubmitSignRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'signer_name'  => 'sometimes|string|max:255',
            'signer_email' => 'sometimes|email|max:255',
            'signature'    => $this->input('signature'), // preserved exactly as original — this is a pre-existing quirk, not a rule
        ];
    }
}