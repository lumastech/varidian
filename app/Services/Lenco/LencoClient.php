<?php

namespace App\Services\Lenco;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

/**
 * Server-side calls to the Lenco v2 API. Only the public key ever reaches the browser.
 */
class LencoClient
{
    /**
     * Get the current state of a collection by our reference.
     *
     * @return array<string, mixed>
     *
     * @throws ConnectionException
     * @throws RequestException
     */
    public function collectionStatus(string $reference): array
    {
        $response = Http::withToken(config('services.lenco.secret_key'))
            ->acceptJson()
            ->timeout((int) config('services.lenco.timeout', 30))
            ->get(rtrim(config('services.lenco.api_url'), '/').'/collections/status/'.rawurlencode($reference))
            ->throw();

        return $response->json('data') ?? [];
    }

    /**
     * Determine if a webhook body was signed by Lenco.
     */
    public function hasValidSignature(string $payload, ?string $signature): bool
    {
        if (! $signature) {
            return false;
        }

        $key = config('services.lenco.webhook_secret') ?: hash('sha256', (string) config('services.lenco.secret_key'));

        return hash_equals(hash_hmac('sha512', $payload, $key), $signature);
    }

    /**
     * Get the inline widget script for the configured environment.
     */
    public function widgetUrl(): string
    {
        return config('services.lenco.widget_url') ?: (config('services.lenco.environment') === 'live'
            ? 'https://pay.lenco.co/js/v1/inline.js'
            : 'https://pay.sandbox.lenco.co/js/v1/inline.js');
    }
}
