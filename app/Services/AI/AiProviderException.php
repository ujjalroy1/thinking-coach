<?php

namespace App\Services\AI;

use Illuminate\Http\Client\Response;
use RuntimeException;

class AiProviderException extends RuntimeException
{
    public function __construct(string $message, public readonly ?int $retryAfter = null)
    {
        parent::__construct($message);
    }

    public static function fromResponse(Response $response): self
    {
        $retryAfter = (int) $response->header('retry-after');

        return new self(
            "HTTP {$response->status()}: " . ($response->json('error.message') ?? 'Unknown error'),
            $retryAfter > 0 ? $retryAfter : null,
        );
    }
}
