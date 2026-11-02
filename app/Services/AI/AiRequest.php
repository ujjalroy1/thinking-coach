<?php

namespace App\Services\AI;

/**
 * Provider-agnostic description of one AI call.
 */
final class AiRequest
{
    public function __construct(
        public readonly string $prompt,
        public readonly ?string $system = null,
        public readonly float $temperature = 0.7,
        public readonly int $maxTokens = 2048,
        public readonly ?int $timeout = null,   // per-provider cap in seconds; null = provider default
        public readonly bool $fast = false,     // use the provider's fast/cheap model if configured
        public readonly array $enum = [],       // allowed outputs (used natively where supported)
    ) {
    }
}
