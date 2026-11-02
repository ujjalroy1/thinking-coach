<?php

namespace App\Services\AI;

use App\Services\AI\Providers\GeminiProvider;
use App\Services\AI\Providers\GroqProvider;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * Tries providers in the configured order (AI_PROVIDERS) and returns the first good answer.
 * A provider that errors is skipped for a short cooldown, so later requests don't wait on it.
 */
final class AiClient
{
    private const PROVIDERS = [
        'groq'   => GroqProvider::class,
        'gemini' => GeminiProvider::class,
    ];

    private const MAX_COOLDOWN = 300;

    /**
     * @param  callable(string): bool|null  $accept  reject unusable output and try the next provider
     */
    public function generate(AiRequest $request, ?callable $accept = null): ?string
    {
        foreach ($this->providers() as $provider) {
            if ($this->isCoolingDown($provider)) {
                continue;
            }

            $text = $this->attempt($provider, $request);

            if ($text !== null && ($accept === null || $accept($text))) {
                return $text;
            }
        }

        Log::error('All AI providers failed or are cooling down.');

        return null;
    }

    private function attempt(AiProvider $provider, AiRequest $request): ?string
    {
        try {
            return $provider->generate($request);
        } catch (AiProviderException $e) {
            $this->coolDown($provider, $e->retryAfter);
            Log::warning("AI provider [{$provider->name()}] failed", ['error' => $e->getMessage()]);

            return null;
        }
    }

    /**
     * @return iterable<AiProvider>
     */
    private function providers(): iterable
    {
        foreach ((array) config('services.ai.providers', ['groq', 'gemini']) as $name) {
            $class = self::PROVIDERS[trim($name)] ?? null;

            if ($class === null) {
                Log::warning("Unknown AI provider [{$name}] in config.");
                continue;
            }

            $provider = app($class);

            if ($provider->isConfigured()) {
                yield $provider;
            }
        }
    }

    // Cache failures (e.g. Redis down) must never break AI calls, hence rescue().
    private function isCoolingDown(AiProvider $provider): bool
    {
        return rescue(fn () => Cache::has($this->cooldownKey($provider)), false, false);
    }

    private function coolDown(AiProvider $provider, ?int $retryAfter): void
    {
        $seconds = min($retryAfter ?? (int) config('services.ai.cooldown', 60), self::MAX_COOLDOWN);

        rescue(fn () => Cache::put($this->cooldownKey($provider), true, $seconds), null, false);
    }

    private function cooldownKey(AiProvider $provider): string
    {
        return "ai:cooldown:{$provider->name()}";
    }
}
