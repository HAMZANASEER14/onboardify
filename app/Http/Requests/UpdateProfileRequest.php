<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name'    => 'required|string|max:255',
            'last_name'     => 'required|string|max:255',
            'company_name'  => 'required|string|max:255',
            'industry'      => 'required|string|max:255',
            'domain'        => 'required|string|max:255',
            'full_phone'    => [
                'required',
                'string',
                'max:20',
                'regex:/^\+[1-9][0-9]{6,14}$/'
            ],
            'location'      => 'required|string|max:255',
            'bio'           => 'nullable|string|max:500',
            'business_type' => 'required|string',
            'role'          => 'required|in:admin,employee,client',
            'picture'       => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ];
    }
}