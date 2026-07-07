<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreGroupRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
               return in_array(auth()->user()->role, ['admin', 'employee']);

    }

   public function rules(): array
    {
        $me = auth()->user();

        return [
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'member_ids'  => 'required|array|min:1',
            'member_ids.*' => [
                'integer',
                Rule::exists('users', 'id')->where('team_id', $me->team_id),
            ],
        ];
    }
}
