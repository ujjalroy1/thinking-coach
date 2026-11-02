<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Weekly Insight Report
        </h2>
    </x-slot>

    <div class="py-8 max-w-3xl mx-auto px-4 space-y-6">

        {{-- Top stats row --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

            <div class="bg-white rounded-2xl border p-4 text-center shadow-sm">
                <p class="text-3xl font-bold text-purple-600">{{ $totalEntries }}</p>
                <p class="text-xs text-gray-400 mt-1">Total entries</p>
            </div>

            <div class="bg-white rounded-2xl border p-4 text-center shadow-sm">
                <p class="text-3xl font-bold text-orange-500">
                    {{ $streak?->current_streak ?? 0 }}🔥
                </p>
                <p class="text-xs text-gray-400 mt-1">Current streak</p>
            </div>

            <div class="bg-white rounded-2xl border p-4 text-center shadow-sm">
                <p class="text-3xl font-bold text-blue-600">
                    {{ $weekComparison['this_week'] }}
                </p>
                <p class="text-xs text-gray-400 mt-1">Entries this week</p>
            </div>

            <div class="bg-white rounded-2xl border p-4 text-center shadow-sm">
                @php
                    $change = $weekComparison['change'];
                    $changeColor = $change >= 0 ? 'text-green-600' : 'text-red-500';
                    $changePrefix = $change >= 0 ? '+' : '';
                @endphp
                <p class="text-3xl font-bold {{ $changeColor }}">
                    {{ $changePrefix }}{{ $change }}
                </p>
                <p class="text-xs text-gray-400 mt-1">vs last week</p>
            </div>

        </div>

        {{-- Category breakdown --}}
        <div class="bg-white rounded-2xl border shadow-sm p-6">
            <h3 class="font-semibold text-gray-700 mb-4">This Week's Patterns</h3>

            @if($categoryBreakdown->isEmpty())
                <p class="text-gray-400 text-sm">No entries this week yet.</p>
            @else
                @php
                    $maxCount = $categoryBreakdown->max();
                    $categoryColors = [
                        'procrastination'    => ['bar' => 'bg-orange-400', 'badge' => 'bg-orange-100 text-orange-700'],
                        'overthinking'       => ['bar' => 'bg-blue-400',   'badge' => 'bg-blue-100 text-blue-700'],
                        'decision_confusion' => ['bar' => 'bg-indigo-400', 'badge' => 'bg-indigo-100 text-indigo-700'],
                        'motivation_drop'    => ['bar' => 'bg-red-400',    'badge' => 'bg-red-100 text-red-700'],
                        'anxiety'            => ['bar' => 'bg-yellow-400', 'badge' => 'bg-yellow-100 text-yellow-700'],
                        'general'            => ['bar' => 'bg-gray-400',   'badge' => 'bg-gray-100 text-gray-600'],
                    ];
                @endphp

                <div class="space-y-4">
                    @foreach($categoryBreakdown as $category => $count)
                        @php
                            $colors  = $categoryColors[$category] ?? $categoryColors['general'];
                            $percent = $maxCount > 0 ? round(($count / $maxCount) * 100) : 0;
                        @endphp
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <span class="px-3 py-0.5 rounded-full text-xs font-medium {{ $colors['badge'] }} capitalize">
                                    {{ str_replace('_', ' ', $category) }}
                                </span>
                                <span class="text-sm text-gray-500 font-medium">
                                    {{ $count }} {{ Str::plural('entry', $count) }}
                                </span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2.5">
                                <div class="{{ $colors['bar'] }} h-2.5 rounded-full transition-all"
                                     style="width: {{ $percent }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Peak time + top category --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            <div class="bg-white rounded-2xl border shadow-sm p-6">
                <p class="text-xs text-gray-400 uppercase tracking-wide font-medium mb-2">
                    Peak challenge time
                </p>
                <p class="text-2xl font-bold text-gray-800">⏰ {{ $peakHourLabel }}</p>
                <p class="text-sm text-gray-400 mt-1">
                    When you tend to struggle most this week
                </p>
            </div>

            <div class="bg-white rounded-2xl border shadow-sm p-6">
                <p class="text-xs text-gray-400 uppercase tracking-wide font-medium mb-2">
                    Your biggest pattern
                </p>
                <p class="text-2xl font-bold text-gray-800 capitalize">
                    🎯 {{ $topCategory ? str_replace('_', ' ', $topCategory) : 'Not enough data' }}
                </p>
                <p class="text-sm text-gray-400 mt-1">
                    Most common challenge overall
                </p>
            </div>

        </div>

        {{-- 14-day activity heatmap --}}
        <div class="bg-white rounded-2xl border shadow-sm p-6">
            <h3 class="font-semibold text-gray-700 mb-4">14-Day Activity</h3>
            <div class="flex gap-2 flex-wrap">
                @foreach($dailyActivity as $date => $count)
                    @php
                        $intensity = match(true) {
                            $count === 0 => 'bg-gray-100',
                            $count === 1 => 'bg-purple-200',
                            $count === 2 => 'bg-purple-400',
                            default      => 'bg-purple-600',
                        };
                        $label = \Carbon\Carbon::parse($date)->format('M d');
                    @endphp
                    <div class="flex flex-col items-center gap-1">
                        <div class="w-8 h-8 rounded-lg {{ $intensity }} flex items-center
                                    justify-center text-xs font-bold
                                    {{ $count > 0 ? 'text-white' : 'text-gray-300' }}"
                             title="{{ $label }}: {{ $count }} entries">
                            {{ $count ?: '' }}
                        </div>
                        <span class="text-xs text-gray-300"
                              style="font-size:9px">
                            {{ \Carbon\Carbon::parse($date)->format('d') }}
                        </span>
                    </div>
                @endforeach
            </div>
            <div class="flex items-center gap-2 mt-4 text-xs text-gray-400">
                <span>Less</span>
                <div class="w-4 h-4 rounded bg-gray-100"></div>
                <div class="w-4 h-4 rounded bg-purple-200"></div>
                <div class="w-4 h-4 rounded bg-purple-400"></div>
                <div class="w-4 h-4 rounded bg-purple-600"></div>
                <span>More</span>
            </div>
        </div>

        {{-- AI weekly report --}}
        @if($aiReport)
            <div class="bg-gradient-to-br from-purple-50 to-indigo-50
                        border border-purple-100 rounded-2xl shadow-sm p-6">
                <p class="text-xs text-purple-400 uppercase tracking-wide font-medium mb-4">
                    AI Insight Report
                </p>
                <div class="text-gray-700 leading-relaxed whitespace-pre-line text-sm">
                    {{ $aiReport }}
                </div>
            </div>
        @else
            <div class="bg-gray-50 border border-gray-100 rounded-2xl p-6 text-center text-gray-400">
                <p class="text-2xl mb-2">📭</p>
                <p class="text-sm">Submit some entries this week to unlock your AI report.</p>
            </div>
        @endif

        {{-- CTA --}}
        <div class="text-center pb-4">
            <a href="{{ route('entries.create') }}"
               class="inline-block bg-purple-600 hover:bg-purple-700 text-white
                      font-semibold py-3 px-8 rounded-xl transition">
                + New Entry
            </a>
        </div>

    </div>
</x-app-layout>