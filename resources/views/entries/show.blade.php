<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Your Guidance
        </h2>
    </x-slot>

    <div class="py-8 max-w-2xl mx-auto px-4">

        {{-- Category badge --}}
        <div class="flex items-center gap-3 mb-6">
            @php
                $colors = [
                    'procrastination'   => 'bg-orange-100 text-orange-700',
                    'overthinking'      => 'bg-blue-100 text-blue-700',
                    'decision_confusion'=> 'bg-indigo-100 text-indigo-700',
                    'motivation_drop'   => 'bg-red-100 text-red-700',
                    'anxiety'           => 'bg-yellow-100 text-yellow-700',
                    'general'           => 'bg-gray-100 text-gray-600',
                ];
                $icons = [
                    'procrastination'   => '⏰',
                    'overthinking'      => '🌀',
                    'decision_confusion'=> '⚖️',
                    'motivation_drop'   => '🔋',
                    'anxiety'           => '😮‍💨',
                    'general'           => '🧠',
                ];
                $color = $colors[$entry->category] ?? 'bg-gray-100 text-gray-600';
                $icon  = $icons[$entry->category]  ?? '🧠';
            @endphp

            <span class="px-4 py-1 rounded-full text-sm font-semibold {{ $color }}">
                {{ $icon }} {{ ucwords(str_replace('_', ' ', $entry->category)) }}
            </span>
            <span class="text-gray-400 text-sm">
                {{ $entry->created_at->format('M d, Y · h:i A') }}
            </span>
        </div>

        {{-- User's original input --}}
        <div class="bg-gray-50 border border-gray-100 rounded-2xl p-5 mb-6">
            <p class="text-xs text-gray-400 mb-1 font-medium uppercase tracking-wide">You said</p>
            <p class="text-gray-700">{{ $entry->user_input }}</p>
        </div>

        {{-- AI Response --}}
        <div class="bg-white border border-purple-100 rounded-2xl shadow-sm p-6 mb-6">
            <p class="text-xs text-purple-400 mb-4 font-medium uppercase tracking-wide">
                AI Thinking Coach
            </p>
            <div class="prose prose-sm max-w-none text-gray-700 leading-relaxed whitespace-pre-line">
                {{ $entry->ai_response }}
            </div>
        </div>

        {{-- Action buttons --}}
        <div class="flex gap-3">
            <a href="{{ route('entries.create') }}"
               class="flex-1 text-center bg-purple-600 hover:bg-purple-700 text-white
                      font-semibold py-3 rounded-xl transition">
                + New Entry
            </a>
            <a href="{{ route('dashboard') }}"
               class="flex-1 text-center bg-gray-100 hover:bg-gray-200 text-gray-700
                      font-semibold py-3 rounded-xl transition">
                Dashboard
            </a>
        </div>

    </div>
</x-app-layout>