<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SendMessageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
          $conversation = $this->route('conversation');

        return $conversation
            && ($conversation->sender_id === auth()->id() || $conversation->receiver_id === auth()->id());
   
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
          return [
            'content' => 'nullable|string|max:2000',
            'file'    => 'nullable|file|max:2048',
        ];
    }
}
