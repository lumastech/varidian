<?php

namespace App\Services\Hestia;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

/**
 * Thin wrapper around the Hestia control panel API (POST /api/ with hash authentication).
 */
class HestiaClient
{
    /**
     * List the hosting packages defined on the server, keyed by package name.
     *
     * @return array<string, array<string, string>>
     */
    public function listPackages(): array
    {
        return $this->query('v-list-user-packages', ['json']);
    }

    /**
     * Get a single package's limits.
     *
     * @return array<string, string>
     */
    public function getPackage(string $package): array
    {
        return $this->query('v-list-user-package', [$package, 'json'])[$package] ?? [];
    }

    /**
     * Get a user's account details, including disk and bandwidth usage against their limits.
     *
     * @return array<string, string>
     */
    public function getUser(string $username): array
    {
        return $this->query('v-list-user', [$username, 'json'])[$username] ?? [];
    }

    public function createUser(string $username, string $password, string $email, string $package, string $name): void
    {
        $this->run('v-add-user', [$username, $password, $email, $package, $name]);
    }

    public function changePassword(string $username, string $password): void
    {
        $this->run('v-change-user-password', [$username, $password]);
    }

    public function changePackage(string $username, string $package): void
    {
        $this->run('v-change-user-package', [$username, $package]);
    }

    public function addWebDomain(string $username, string $domain): void
    {
        $this->run('v-add-web-domain', [$username, $domain]);
    }

    public function addLetsEncrypt(string $username, string $domain): void
    {
        $this->run('v-add-letsencrypt-domain', [$username, $domain]);
    }

    public function suspendUser(string $username): void
    {
        $this->run('v-suspend-user', [$username]);
    }

    public function unsuspendUser(string $username): void
    {
        $this->run('v-unsuspend-user', [$username]);
    }

    public function deleteUser(string $username): void
    {
        $this->run('v-delete-user', [$username]);
    }

    /**
     * Run a command that only reports success or failure.
     *
     * @param  list<string>  $args
     *
     * @throws HestiaException
     * @throws ConnectionException
     */
    public function run(string $command, array $args = []): void
    {
        $body = trim($this->send($command, $args, returnCode: true));

        if ($body !== '0') {
            throw is_numeric($body)
                ? HestiaException::commandFailed($command, (int) $body)
                : HestiaException::unexpectedResponse($command, $body);
        }
    }

    /**
     * Run a command and decode its JSON output.
     *
     * @param  list<string>  $args
     * @return array<string, mixed>
     *
     * @throws HestiaException
     * @throws ConnectionException
     */
    public function query(string $command, array $args = []): array
    {
        $body = $this->send($command, $args, returnCode: false);
        $decoded = json_decode($body, true);

        if (! is_array($decoded)) {
            throw HestiaException::unexpectedResponse($command, $body);
        }

        return $decoded;
    }

    /**
     * @param  list<string>  $args
     */
    private function send(string $command, array $args, bool $returnCode): string
    {
        $form = [
            'hash' => config('services.hestia.access_key').':'.config('services.hestia.secret_key'),
            'returncode' => $returnCode ? 'yes' : 'no',
            'cmd' => $command,
        ];

        foreach (array_values($args) as $index => $arg) {
            $form['arg'.($index + 1)] = $arg;
        }

        $response = $this->request()->post(config('services.hestia.url'), $form);

        if ($response->failed()) {
            throw HestiaException::unexpectedResponse($command, "HTTP {$response->status()}");
        }

        return $response->body();
    }

    private function request(): PendingRequest
    {
        $request = Http::asForm()
            ->timeout((int) config('services.hestia.timeout', 30))
            ->withOptions(['verify' => (bool) config('services.hestia.verify_ssl', true)]);

        $resolveIp = config('services.hestia.resolve_ip');

        if ($resolveIp) {
            $url = parse_url(config('services.hestia.url'));
            $port = $url['port'] ?? (($url['scheme'] ?? 'https') === 'https' ? 443 : 80);

            // Reach the panel over the LAN while still validating its public certificate.
            $request->withOptions(['curl' => [CURLOPT_RESOLVE => ["{$url['host']}:{$port}:{$resolveIp}"]]]);
        }

        return $request;
    }
}
