<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    /*
    | AI providers are tried in this order. If one fails, the next is used,
    | and the failed one is skipped for `cooldown` seconds.
    */
    'ai' => [
        'providers' => explode(',', env('AI_PROVIDERS', 'groq,gemini')),
        'cooldown'  => (int) env('AI_PROVIDER_COOLDOWN', 60),
    ],

    'groq' => [
        'key'              => env('GROQ_API_KEY'),
        'model'            => env('GROQ_MODEL', 'openai/gpt-oss-120b'),
        'fast_model'       => env('GROQ_FAST_MODEL', 'openai/gpt-oss-20b'),
        'base_url'         => env('GROQ_BASE_URL', 'https://api.groq.com/openai/v1'),
        'timeout'          => (int) env('GROQ_TIMEOUT', 10),
        // low | medium | high — only for reasoning models (gpt-oss). Leave empty for others.
        'reasoning_effort' => env('GROQ_REASONING_EFFORT', 'low'),
    ],

    'gemini' => [
        'key'            => env('GEMINI_API_KEY'),
        'model'          => env('GEMINI_MODEL', 'gemini-3.5-flash-lite'),
        'fast_model'     => env('GEMINI_FAST_MODEL'),
        'base_url'       => env('GEMINI_BASE_URL', 'https://generativelanguage.googleapis.com/v1beta'),
        'timeout'        => (int) env('GEMINI_TIMEOUT', 15),
        // minimal | low | medium | high. Lower = faster.
        'thinking_level' => env('GEMINI_THINKING_LEVEL', 'low'),
    ],

];