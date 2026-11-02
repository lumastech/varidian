<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class LeadReviewRequest extends FormRequest
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
            'decision' => ['required', 'in:confirm,reject,engage,extend,won,lost'],
            'note' => ['nullable', 'required_if:decision,reject', 'string', 'max:255'],
            'team_id' => ['nullable', 'required_if:decision,won', 'integer', 'exists:teams,id'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'note.required_if' => 'Tell the partner why, e.g. "Already registered" or "Existing client".',
            'team_id.required_if' => 'Choose the client account this lead signed up as.',
        ];
    }
}
