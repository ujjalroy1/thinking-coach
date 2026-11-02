<?php

namespace App\Services\AI;

interface AiProvider
{
    public function name(): string;

    public function isConfigured(): bool;

    /**
     * Returns the generated text, or null if the provider answered with no usable text.
     *
     * @throws AiProviderException on network, auth, rate-limit or server errors
     */
    public function generate(AiRequest $request): ?string;
}
