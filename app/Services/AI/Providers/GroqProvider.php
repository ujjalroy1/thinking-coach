<?php

namespace App\Services\AI\Providers;

use App\Services\AI\AiRequest;

final class GroqProvider extends HttpProvider
{
    private const DEFAULT_BASE_URL = 'https://api.groq.com/openai/v1';

    public function name(): string
    {
        return 'groq';
    }

    public function generate(AiRequest $request): ?string
    {
        $response = $this->post(
            $this->baseUrl(self::DEFAULT_BASE_URL) . '/chat/completions',
            ['Authorization' => 'Bearer ' . $this->config['key']],
            $this->payload($request),
            $request,
        );

        // Only "content" — the "reasoning" field must never reach users.
        $text = trim((string) $response->json('choices.0.message.content'));

        return $text === '' ? null : $text;
    }

    private function payload(AiRequest $request): array
    {
        $messages = $request->system
            ? [['role' => 'system', 'content' => $request->system]]
            : [];

        $messages[] = ['role' => 'user', 'content' => $request->prompt];

        return array_filter([
            'model'                 => $this->model($request),
            'messages'              => $messages,
            'temperature'           => $request->temperature,
            'max_completion_tokens' => $request->maxTokens,
            'reasoning_effort'      => ($this->config['reasoning_effort'] ?? null) ?: null,
        ], fn ($value) => $value !== null);
    }
}
