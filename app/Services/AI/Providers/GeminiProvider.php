<?php

namespace App\Services\AI\Providers;

use App\Services\AI\AiRequest;

final class GeminiProvider extends HttpProvider
{
    private const DEFAULT_BASE_URL = 'https://generativelanguage.googleapis.com/v1beta';

    public function name(): string
    {
        return 'gemini';
    }

    public function generate(AiRequest $request): ?string
    {
        $model = $this->model($request) ?: 'gemini-3.5-flash-lite';

        $response = $this->post(
            $this->baseUrl(self::DEFAULT_BASE_URL) . "/models/{$model}:generateContent",
            ['x-goog-api-key' => $this->config['key']], // header, never in the URL
            $this->payload($request),
            $request,
        );

        $text = collect($response->json('candidates.0.content.parts', []))
            ->reject(fn ($part) => $part['thought'] ?? false)
            ->pluck('text')
            ->implode('');

        return trim($text) === '' ? null : trim($text);
    }

    private function payload(AiRequest $request): array
    {
        $config = [
            'temperature'     => $request->temperature,
            'maxOutputTokens' => $request->maxTokens,
        ];

        if ($request->enum !== []) {
            $config['responseMimeType'] = 'text/x.enum';
            $config['responseSchema']   = ['type' => 'STRING', 'enum' => $request->enum];
        }

        if ($level = ($this->config['thinking_level'] ?? null)) {
            $config['thinkingConfig'] = ['thinkingLevel' => $level];
        }

        $payload = [
            'contents'         => [['role' => 'user', 'parts' => [['text' => $request->prompt]]]],
            'generationConfig' => $config,
        ];

        if ($request->system) {
            $payload['systemInstruction'] = ['parts' => [['text' => $request->system]]];
        }

        return $payload;
    }
}
