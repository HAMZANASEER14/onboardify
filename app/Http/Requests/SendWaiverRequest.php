<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendWaiverRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'emails'   => 'required|array|min:1',
            'emails.*' => 'required|email|max:255',
            'names'    => 'required|array|min:1',
            'names.*'  => 'required|string|max:255',
        ];
    }
}