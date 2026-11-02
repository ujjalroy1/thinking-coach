<?php

namespace App\Services\AI\Providers;

use App\Services\AI\AiProvider;
use App\Services\AI\AiProviderException;
use App\Services\AI\AiRequest;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

/**
 * Shared HTTP plumbing for providers. No retries here: fallback to the next provider is faster.
 */
abstract class HttpProvider implements AiProvider
{
    protected array $config;

    public function __construct()
    {
        $this->config = (array) config("services.{$this->name()}", []);
    }

    public function isConfigured(): bool
    {
        return filled($this->config['key'] ?? null);
    }

    protected function post(string $url, array $headers, array $payload, AiRequest $request): Response
    {
        try {
            $response = Http::withHeaders($headers)
                ->acceptJson()
                ->asJson()
                ->connectTimeout(3)
                ->timeout($this->timeout($request))
                ->post($url, $payload);
        } catch (ConnectionException $e) {
            throw new AiProviderException("Connection failed: {$e->getMessage()}");
        }

        if ($response->failed()) {
            throw AiProviderException::fromResponse($response);
        }

        return $response;
    }

    protected function model(AiRequest $request): string
    {
        $fastModel = $this->config['fast_model'] ?? null;

        return $request->fast && filled($fastModel) ? $fastModel : (string) $this->config['model'];
    }

    protected function baseUrl(string $default): string
    {
        return rtrim(($this->config['base_url'] ?? null) ?: $default, '/');
    }

    private function timeout(AiRequest $request): int
    {
        $default = (int) (($this->config['timeout'] ?? null) ?: 15);

        return $request->timeout ? min($request->timeout, $default) : $default;
    }
}
