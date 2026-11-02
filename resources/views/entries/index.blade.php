<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Entry History
        </h2>
    </x-slot>

    <div class="py-8 max-w-3xl mx-auto px-4">

        @if($entries->count())
            <div class="space-y-4">
                @foreach($entries as $entry)
                    @php
                        $colors = [
                            'procrastination'    => 'border-orange-300 bg-orange-50',
                            'overthinking'       => 'border-blue-300 bg-blue-50',
                            'decision_confusion' => 'border-indigo-300 bg-indigo-50',
                            'motivation_drop'    => 'border-red-300 bg-red-50',
                            'anxiety'            => 'border-yellow-300 bg-yellow-50',
                            'general'            => 'border-gray-300 bg-gray-50',
                        ];
                        $style = $colors[$entry->category] ?? 'border-gray-200 bg-gray-50';
                    @endphp

                    <a href="{{ route('entries.show', $entry->id) }}"
                       class="block border-l-4 {{ $style }} rounded-xl p-5
                              hover:shadow-sm transition">
                        <div class="flex justify-between items-start mb-2">
                            <span class="text-sm font-semibold text-gray-600 capitalize">
                                {{ str_replace('_', ' ', $entry->category) }}
                            </span>
                            <span class="text-xs text-gray-400">
                                {{ $entry->created_at->format('M d · h:i A') }}
                            </span>
                        </div>
                        <p class="text-gray-700 text-sm">
                            {{ Str::limit($entry->user_input, 120) }}
                        </p>
                    </a>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $entries->links() }}
            </div>

        @else
            <div class="text-center py-16 text-gray-400">
                <p class="text-4xl mb-3">📭</p>
                <p>No entries yet.</p>
                <a href="{{ route('entries.create') }}"
                   class="mt-4 inline-block bg-purple-600 text-white px-6 py-2 rounded-xl text-sm">
                    Submit your first entry
                </a>
            </div>
        @endif

    </div>
</x-app-layout>