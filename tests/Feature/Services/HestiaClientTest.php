<?php

use App\Services\Hestia\HestiaClient;
use App\Services\Hestia\HestiaException;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    config([
        'services.hestia.url' => 'https://panel.test:8086/api/',
        'services.hestia.access_key' => 'ACCESS',
        'services.hestia.secret_key' => 'SECRET',
        'services.hestia.resolve_ip' => null,
    ]);
});

test('commands are posted with the hash and positional arguments', function () {
    Http::fake(['panel.test:8086/api/' => Http::response('0')]);

    app(HestiaClient::class)->createUser('acme', 'secret-pass', 'it@acme.co.zm', 'Starter', 'Acme Ltd');

    Http::assertSent(fn (Request $request) => $request->url() === 'https://panel.test:8086/api/'
        && $request['hash'] === 'ACCESS:SECRET'
        && $request['returncode'] === 'yes'
        && $request['cmd'] === 'v-add-user'
        && $request['arg1'] === 'acme'
        && $request['arg2'] === 'secret-pass'
        && $request['arg3'] === 'it@acme.co.zm'
        && $request['arg4'] === 'Starter'
        && $request['arg5'] === 'Acme Ltd');
});

test('a non-zero return code throws with the hestia code', function () {
    Http::fake(['*' => Http::response('4')]);

    try {
        app(HestiaClient::class)->createUser('acme', 'secret-pass', 'it@acme.co.zm', 'Starter', 'Acme Ltd');
        $this->fail('Expected a HestiaException.');
    } catch (HestiaException $e) {
        expect($e->getCode())->toBe(4)
            ->and($e->alreadyExists())->toBeTrue()
            ->and($e->getMessage())->not->toContain('secret-pass');
    }
});

test('a failed http response throws', function () {
    Http::fake(['*' => Http::response('Forbidden', 403)]);

    app(HestiaClient::class)->suspendUser('acme');
})->throws(HestiaException::class, 'HTTP 403');

test('queries decode json output', function () {
    Http::fake(['*' => Http::response(json_encode([
        'Starter' => ['DISK_QUOTA' => '5120', 'BANDWIDTH' => '20480', 'WEB_DOMAINS' => '1'],
    ]))]);

    $packages = app(HestiaClient::class)->listPackages();

    expect($packages['Starter']['DISK_QUOTA'])->toBe('5120');

    Http::assertSent(fn (Request $request) => $request['returncode'] === 'no'
        && $request['cmd'] === 'v-list-user-packages'
        && $request['arg1'] === 'json');
});

test('user details are unwrapped from the username key', function () {
    Http::fake(['*' => Http::response(json_encode([
        'acme' => ['PACKAGE' => 'Starter', 'U_DISK' => '120', 'SUSPENDED' => 'no'],
    ]))]);

    expect(app(HestiaClient::class)->getUser('acme'))
        ->toMatchArray(['PACKAGE' => 'Starter', 'SUSPENDED' => 'no']);
});

test('non-json query output throws', function () {
    Http::fake(['*' => Http::response("Error: user acme doesn't exist")]);

    app(HestiaClient::class)->getUser('acme');
})->throws(HestiaException::class, "doesn't exist");
