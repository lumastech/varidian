<?php

namespace App\Http\Requests\Partners;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class LeadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'organisation' => ['required', 'string', 'max:200'],
            'contact_name' => ['required', 'string', 'max:150'],
            'contact_phone' => ['required', 'string', 'max:30'],
            'contact_email' => ['nullable', 'email', 'max:150'],
            'interest' => ['required', 'string', 'max:120'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
