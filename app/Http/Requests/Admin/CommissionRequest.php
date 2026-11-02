<?php

namespace App\Http\Requests\Admin;

use App\Enums\CommissionType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CommissionRequest extends FormRequest
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
        $isAdjustment = $this->input('type') === CommissionType::Adjustment->value;

        return [
            'type' => ['required', Rule::enum(CommissionType::class)->except(CommissionType::Hosting)],
            'lead_id' => [
                Rule::requiredIf(! $isAdjustment),
                'nullable',
                Rule::exists('leads', 'id')->where('partner_id', $this->route('partner')->id),
            ],
            'description' => ['required', 'string', 'max:255'],
            'amount' => [
                'required', 'numeric', 'decimal:0,2', 'max:10000000',
                $isAdjustment ? 'min:-10000000' : 'min:0.01',
                $isAdjustment ? 'not_in:0' : 'present',
            ],
            'earned_on' => ['required', 'date', 'before_or_equal:today'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'type.enum' => 'Hosting commission is recorded automatically when the client pays.',
            'lead_id.required' => 'Choose the client this payment came from.',
        ];
    }
}
