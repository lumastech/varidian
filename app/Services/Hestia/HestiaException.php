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

    public static function commandFailed(string $command, int $returnCode): self
    {
        return new self("Hestia command {$command} failed with return code {$returnCode}.", $returnCode);
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
}
