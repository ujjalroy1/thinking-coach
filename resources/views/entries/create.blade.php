<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            New Entry
        </h2>
    </x-slot>

    <div class="py-8 max-w-2xl mx-auto px-4">

        <div class="bg-white rounded-2xl shadow-sm border p-8">

            <p class="text-gray-500 mb-6 text-sm">
                Share what's on your mind. The AI will guide you with a structured framework.
            </p>

            <form method="POST" action="{{ route('entries.store') }}" id="entry-form">
                @csrf

                {{-- Mode selector --}}
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Mode</label>
                    <div class="flex gap-3">
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="mode" value="thinking"
                                   class="sr-only peer" checked>
                            <div class="border-2 rounded-xl p-3 text-center text-sm font-medium
                                        peer-checked:border-purple-500 peer-checked:bg-purple-50
                                        peer-checked:text-purple-700 text-gray-500 transition">
                                🧠 Thinking Mode
                            </div>
                        </label>
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="mode" value="decision"
                                   class="sr-only peer" id="decision-radio">
                            <div class="border-2 rounded-xl p-3 text-center text-sm font-medium
                                        peer-checked:border-blue-500 peer-checked:bg-blue-50
                                        peer-checked:text-blue-700 text-gray-500 transition">
                                ⚖️ Decision Mode
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Main input --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        What's on your mind?
                    </label>
                    <textarea
                        name="user_input"
                        rows="4"
                        placeholder="e.g. I keep delaying my work and feel guilty about it..."
                        class="w-full border border-gray-200 rounded-xl p-4 text-gray-700
                               focus:outline-none focus:ring-2 focus:ring-purple-400 resize-none"
                        required minlength="10" maxlength="1000">{{ old('user_input') }}</textarea>
                    @error('user_input')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Decision mode extra fields --}}
                <div id="decision-fields" class="hidden mb-4 space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Option A</label>
                        <input type="text" name="option_a"
                               placeholder="e.g. Stay at current job"
                               class="w-full border border-gray-200 rounded-xl p-3 text-gray-700
                                      focus:outline-none focus:ring-2 focus:ring-blue-400"
                               value="{{ old('option_a') }}">
                        @error('option_a')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Option B</label>
                        <input type="text" name="option_b"
                               placeholder="e.g. Switch to a new job"
                               class="w-full border border-gray-200 rounded-xl p-3 text-gray-700
                                      focus:outline-none focus:ring-2 focus:ring-blue-400"
                               value="{{ old('option_b') }}">
                        @error('option_b')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Language selector --}}
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Language</label>
                    <select name="language"
                            class="w-full border border-gray-200 rounded-xl p-3 text-gray-700
                                   focus:outline-none focus:ring-2 focus:ring-purple-400">
                        <option value="en" {{ old('language') === 'en' ? 'selected' : '' }}>
                            🇬🇧 English
                        </option>
                        <option value="bn" {{ old('language') === 'bn' ? 'selected' : '' }}>
                            🇧🇩 বাংলা (Bangla)
                        </option>
                    </select>
                </div>

                {{-- Submit --}}
                <button type="submit" id="submit-btn"
                        class="w-full bg-purple-600 hover:bg-purple-700 text-white font-semibold
                               py-3 rounded-xl transition text-lg">
                    Get AI Guidance →
                </button>

            </form>
        </div>
    </div>

    {{-- Toggle decision fields + loading state --}}
    <script>
        document.querySelectorAll('input[name="mode"]').forEach(radio => {
            radio.addEventListener('change', function () {
                const fields = document.getElementById('decision-fields');
                fields.classList.toggle('hidden', this.value !== 'decision');
            });
        });

        document.getElementById('entry-form').addEventListener('submit', function () {
            const btn = document.getElementById('submit-btn');
            btn.disabled = true;
            btn.textContent = '⏳ Thinking...';
        });
    </script>

</x-app-layout>