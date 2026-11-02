<?php

namespace App\Services\Hestia;

use RuntimeException;

class HestiaException extends RuntimeException
{
    /**
     * Hestia CLI return codes (see /usr/local/hestia/func/main.sh).
     */
    public const int ExistsCode = 4;

    public const int NotExistCode = 3;

    public const int SuspendedCode = 5;

    public const int UnsuspendedCode = 6;

    public static function commandFailed(string $command, int $returnCode): self
    {
        return new self("Hestia command {$command} failed with return code {$returnCode}.", $returnCode);
    }

    public static function notConfigured(): self
    {
        return new self('Hestia is not configured; set HESTIA_URL, HESTIA_ACCESS_KEY and HESTIA_SECRET_KEY.');
    }

    public static function unexpectedResponse(string $command, string $body): self
    {
        return new self("Hestia command {$command} returned an unexpected response: ".mb_substr(trim($body), 0, 200));
    }

    /**
     * Whether the failure means the object (user, domain, …) already exists.
     */
    public function alreadyExists(): bool
    {
        return $this->getCode() === self::ExistsCode;
    }

    /**
     * Whether the failure means the object does not exist.
     */
    public function notFound(): bool
    {
        return $this->getCode() === self::NotExistCode;
    }

    /**
     * Whether a suspend or unsuspend failed only because the account was already in that state.
     */
    public function alreadyInRequestedState(): bool
    {
        return in_array($this->getCode(), [self::SuspendedCode, self::UnsuspendedCode], true);
    }
}
