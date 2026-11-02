<?php

namespace App\Http\Requests\Partners;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PartnerApplicationRequest extends FormRequest
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
            'full_name' => ['required', 'string', 'max:150'],
            'nrc_number' => ['required', 'string', 'regex:/^\d{6}\/\d{2}\/\d$/'],
            'phone' => ['required', 'string', 'max:30'],
            'payout_method' => ['required', 'in:mobile-money,bank-transfer'],
            'payout_details' => ['required', 'string', 'max:255'],
            'accept_agreement' => ['accepted'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nrc_number.regex' => 'Enter your NRC number as it appears on the card, e.g. 123456/10/1.',
            'accept_agreement.accepted' => 'Please read and accept the Sales Partner Agreement.',
        ];
    }
}
