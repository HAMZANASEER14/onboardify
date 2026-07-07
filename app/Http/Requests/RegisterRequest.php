<?php

namespace App\Http\Requests;

use App\Models\Invite;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class RegisterRequest extends FormRequest
{
    public ?Invite $resolvedInvite = null;

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        // ✅ FIX: ALWAYS look for an invite by email, don't wait for company_code
        $this->resolvedInvite = Invite::where('email', $this->input('email'))
            ->whereIn('status', ['sent', 'pending'])
            ->latest()
            ->first();

        // If an invite is found, force the email to match the invite exactly (prevents spoofing)
        if ($this->resolvedInvite) {
            $this->merge(['email' => $this->resolvedInvite->email]);
        }
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            $companyCode = $this->input('company_code') ?: session('pending_company_code');

            // If they explicitly passed a company code but we couldn't find an invite, fail.
            if ($companyCode && !$this->resolvedInvite) {
                $validator->errors()->add('email', 'This email does not have a valid invitation.');
            }
        });
    }

    public function rules(): array
    {
        return [
            'name'     => ['required', 'string', 'min:2', 'max:255', 'regex:/^[a-zA-Z\s\'\-\.]+$/'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => [
                'required',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&_\-#])[A-Za-z\d@$!%*?&_\-#]{8,}$/',
            ],
        ];
    }
}