<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Upgrade Your Plan
        </h2>
    </x-slot>

    <div class="py-10 max-w-4xl mx-auto px-4">

        {{-- Flash messages --}}
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-xl">
                {{ session('success') }}
            </div>
        @endif

        {{-- Header --}}
        <div class="text-center mb-10">
            <h2 class="text-3xl font-bold text-gray-800">
                Choose Your Plan
            </h2>
            <p class="text-gray-400 mt-2">
                Unlock your full thinking potential
            </p>
        </div>

        {{-- Plan cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">

            {{-- Free plan --}}
            <div class="bg-white rounded-2xl border-2
                        {{ !$user->isPremium() ? 'border-purple-300' : 'border-gray-100' }}
                        shadow-sm p-8 relative">

                @if(!$user->isPremium())
                    <span class="absolute -top-3 left-6 bg-purple-100 text-purple-700
                                 text-xs font-semibold px-3 py-1 rounded-full">
                        Current plan
                    </span>
                @endif

                <h3 class="text-xl font-bold text-gray-800 mb-1">Free</h3>
                <p class="text-4xl font-bold text-gray-800 mb-1">$0
                    <span class="text-base font-normal text-gray-400">/month</span>
                </p>
                <p class="text-gray-400 text-sm mb-6">Forever free</p>

                <ul class="space-y-3 text-sm text-gray-600 mb-8">
                    <li class="flex items-center gap-2">
                        <span class="text-green-500">✓</span> 3 entries per day
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="text-green-500">✓</span> Thinking mode
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="text-green-500">✓</span> Decision mode
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="text-red-400">✗</span>
                        <span class="text-gray-400">Entry history</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="text-red-400">✗</span>
                        <span class="text-gray-400">Weekly reports</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="text-red-400">✗</span>
                        <span class="text-gray-400">Pattern memory</span>
                    </li>
                </ul>

                @if($user->isPremium())
                    <form method="POST" action="{{ route('subscription.downgrade') }}">
                        @csrf
                        <button type="submit"
                                onclick="return confirm('Downgrade to Free plan?')"
                                class="w-full border border-gray-300 text-gray-500
                                       font-semibold py-3 rounded-xl hover:bg-gray-50 transition">
                            Switch to Free
                        </button>
                    </form>
                @else
                    <div class="w-full text-center bg-gray-100 text-gray-400
                                font-semibold py-3 rounded-xl">
                        Current Plan
                    </div>
                @endif
            </div>

            {{-- Premium plan --}}
            <div class="bg-gradient-to-br from-purple-600 to-indigo-600
                        rounded-2xl shadow-lg p-8 relative text-white">

                @if($user->isPremium())
                    <span class="absolute -top-3 left-6 bg-white text-purple-700
                                 text-xs font-semibold px-3 py-1 rounded-full">
                        Current plan
                    </span>
                @else
                    <span class="absolute -top-3 left-6 bg-yellow-400 text-yellow-900
                                 text-xs font-semibold px-3 py-1 rounded-full">
                        ⭐ Recommended
                    </span>
                @endif

                <h3 class="text-xl font-bold mb-1">Premium</h3>

                {{-- Toggle monthly/yearly --}}
                <div x-data="{ plan: 'monthly' }">

                    <div class="flex gap-3 mb-4 mt-2">
                        <button @click="plan = 'monthly'"
                                :class="plan === 'monthly'
                                    ? 'bg-white text-purple-700'
                                    : 'bg-white/20 text-white'"
                                class="flex-1 text-sm font-semibold py-1.5 rounded-lg transition">
                            Monthly
                        </button>
                        <button @click="plan = 'yearly'"
                                :class="plan === 'yearly'
                                    ? 'bg-white text-purple-700'
                                    : 'bg-white/20 text-white'"
                                class="flex-1 text-sm font-semibold py-1.5 rounded-lg transition">
                            Yearly
                            <span class="text-xs ml-1 opacity-75">Save 30%</span>
                        </button>
                    </div>

                    <p class="text-4xl font-bold mb-1">
                        <span x-show="plan === 'monthly'">$9</span>
                        <span x-show="plan === 'yearly'">$75</span>
                        <span class="text-base font-normal opacity-75">
                            <span x-show="plan === 'monthly'">/month</span>
                            <span x-show="plan === 'yearly'">/year</span>
                        </span>
                    </p>
                    <p class="text-purple-200 text-sm mb-6">
                        <span x-show="plan === 'monthly'">Billed monthly</span>
                        <span x-show="plan === 'yearly'">Billed once per year</span>
                    </p>

                    <ul class="space-y-3 text-sm mb-8">
                        <li class="flex items-center gap-2">
                            <span class="text-green-300">✓</span> Unlimited entries
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-green-300">✓</span> Thinking mode
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-green-300">✓</span> Decision mode
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-green-300">✓</span> Full entry history
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-green-300">✓</span> Weekly AI reports
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-green-300">✓</span> Pattern memory
                        </li>
                    </ul>

                    @if(!$user->isPremium())
                        <form method="POST" action="{{ route('subscription.upgrade') }}">
                            @csrf
                            <input type="hidden" name="plan" :value="plan">
                            <button type="submit"
                                    class="w-full bg-white text-purple-700 font-bold
                                           py-3 rounded-xl hover:bg-purple-50 transition text-lg">
                                Upgrade Now →
                            </button>
                        </form>
                    @else
                        <div class="w-full text-center bg-white/20 text-white
                                    font-semibold py-3 rounded-xl">
                            ⭐ Active Premium
                        </div>
                    @endif

                </div>
            </div>

        </div>

        {{-- FAQ --}}
        <div class="bg-white rounded-2xl border shadow-sm p-8"
             x-data="{ open: null }">
            <h3 class="font-bold text-gray-700 text-lg mb-6">Frequently Asked Questions</h3>

            @foreach([
                ['q' => 'Can I cancel anytime?',
                 'a' => 'Yes. You can switch back to the Free plan at any time from this page. No questions asked.'],
                ['q' => 'What happens to my history if I downgrade?',
                 'a' => 'Your entries are saved. You just lose access to the history view and reports until you re-upgrade.'],
                ['q' => 'Is Bangla fully supported?',
                 'a' => 'Yes. Select বাংলা in the entry form and all AI responses will be in Bengali.'],
                ['q' => 'How many entries can I submit per day on Free?',
                 'a' => 'Free users get 3 entries per day. Premium users get unlimited.'],
            ] as $i => $faq)
                <div class="border-b last:border-0 py-4" x-data="{ open: false }">
                    <button @click="open = !open"
                            class="w-full text-left flex justify-between items-center
                                   font-medium text-gray-700">
                        {{ $faq['q'] }}
                        <span x-text="open ? '−' : '+'"></span>
                    </button>
                    <p x-show="open" x-transition
                       class="mt-2 text-sm text-gray-400 leading-relaxed">
                        {{ $faq['a'] }}
                    </p>
                </div>
            @endforeach
        </div>

    </div>
</x-app-layout>