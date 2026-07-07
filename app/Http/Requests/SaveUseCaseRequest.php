<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SaveUseCaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'business_type' => 'required|string|in:gym,health,tech,entrepreneur,nonprofit,education,adventure,guiding,ecommerce,realestate,restaurant,creative,legal,consulting,manufacturing,other',
        ];
    }
}
