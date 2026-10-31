<?php

namespace App\Http\Requests\Billing;

use App\Enums\SubscriptionStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StartSubscriptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Accept what people paste, e.g. "https://www.Acme.co.zm/", and keep the bare domain.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'domain' => Str::of((string) $this->input('domain'))
                ->trim()
                ->lower()
                ->replaceMatches('#^https?://#', '')
                ->replaceMatches('#^www\.#', '')
                ->before('/')
                ->toString(),
        ]);
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'domain' => [
                'required',
                'string',
                'max:253',
                'regex:/^(?!-)[a-z0-9-]{1,63}(?<!-)(\.(?!-)[a-z0-9-]{1,63}(?<!-))+$/',
                Rule::unique('subscriptions', 'domain')->whereNot('status', SubscriptionStatus::Cancelled->value),
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'domain.regex' => 'Enter a domain name like yourbusiness.co.zm.',
            'domain.unique' => 'This domain is already hosted with us. Contact us if it belongs to you.',
        ];
    }
}
