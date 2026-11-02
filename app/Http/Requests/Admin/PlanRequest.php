<?php

namespace App\Http\Requests\Admin;

use App\Models\Plan;
use App\Services\Hestia\HestiaClient;
use App\Services\Hestia\HestiaException;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Validation\Rule;

class PlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_featured' => $this->boolean('is_featured'),
            'is_active' => $this->boolean('is_active'),
        ]);
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $plan = $this->route('plan');

        return [
            'name' => ['required', 'string', 'max:50'],
            'slug' => $plan
                ? ['prohibited']
                : ['required', 'string', 'max:50', 'alpha_dash:ascii', 'lowercase', Rule::unique('plans', 'slug')],
            'audience' => ['nullable', 'string', 'max:120'],
            'hestia_package' => [
                'required', 'string', 'max:100',
                Rule::unique('plans', 'hestia_package')->ignore($plan),
                $this->packageExistsOnServer($plan),
            ],
            'price' => ['required', 'numeric', 'min:1', 'max:100000', 'decimal:0,2'],
            'storage_gb' => ['required', 'integer', 'min:1', 'max:10000'],
            'bandwidth_gb' => ['required', 'integer', 'min:1', 'max:100000'],
            'websites' => ['required', 'integer', 'min:1', 'max:1000'],
            'databases' => ['required', 'integer', 'min:0', 'max:1000'],
            'cron_jobs' => ['required', 'integer', 'min:0', 'max:1000'],
            'backups' => ['required', 'integer', 'min:0', 'max:100'],
            'is_featured' => ['boolean'],
            'is_active' => ['boolean'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:1000'],
        ];
    }

    /**
     * Get the validated attributes ready for the model, with the price in ngwee.
     *
     * @return array<string, mixed>
     */
    public function planAttributes(): array
    {
        $validated = $this->safe()->except('price');

        return [...$validated, 'price_ngwee' => (int) round((float) $this->validated('price') * 100)];
    }

    /**
     * Customers are created on this package, so it must exist on srv2. Only checked when
     * the package changes, so prices can still be edited while the panel is unreachable.
     */
    private function packageExistsOnServer(?Plan $plan): Closure
    {
        return function (string $attribute, mixed $value, Closure $fail) use ($plan) {
            if ($plan?->hestia_package === $value) {
                return;
            }

            try {
                $packages = app(HestiaClient::class)->listPackages();
            } catch (HestiaException|HttpClientException) {
                $fail("We couldn't reach the Hestia server to check this package. Try again shortly.");

                return;
            }

            if (! array_key_exists($value, $packages)) {
                $fail('There is no package with this name on the Hestia server.');
            }
        };
    }
}
