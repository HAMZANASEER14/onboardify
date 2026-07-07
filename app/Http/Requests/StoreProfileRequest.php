<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProfileRequest extends FormRequest
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
            'bio'           => 'nullable|string|max:500',
            'picture'       => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'role'          => 'required|in:admin,employee,client',
            'invite_code'   => 'required_if:role,employee,client|nullable|string|max:10',
            'industry'      => 'required_if:role,admin|nullable|string|max:255',
            'company_name'  => 'required_if:role,admin|nullable|string|max:255',
            'domain' => [
                'required_if:role,admin',
                'nullable',
                'string',
                'max:255',
                'regex:/^https?:\/\/[a-zA-Z0-9][a-zA-Z0-9-]*(\.[a-zA-Z0-9][a-zA-Z0-9-]*)+(\/.*)?$/',
            ],
            'phone' => [
                'required_if:role,admin',
                'nullable',
                'string',
                'max:20',
                'regex:/^\+[1-9][0-9]{6,14}$/'
            ],
            'location'      => 'required_if:role,admin|nullable|string|max:255',
            'business_type' => 'required|string',
        ];
    }
}