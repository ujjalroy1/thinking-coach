<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Daily Thinking Coach</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-800">

    {{-- Navbar --}}
    <nav class="bg-white border-b px-6 py-4 flex justify-between items-center max-w-5xl mx-auto">
        <span class="font-bold text-purple-600 text-lg">🧠 Thinking Coach</span>
        <div class="flex gap-4">
            @auth
                <a href="{{ route('dashboard') }}"
                   class="bg-purple-600 text-white px-5 py-2 rounded-xl text-sm font-semibold hover:bg-purple-700 transition">
                    Go to App
                </a>
            @else
                <a href="{{ route('login') }}"
                   class="text-gray-500 hover:text-gray-700 px-4 py-2 text-sm font-medium">
                    Login
                </a>
                <a href="{{ route('register') }}"
                   class="bg-purple-600 text-white px-5 py-2 rounded-xl text-sm font-semibold hover:bg-purple-700 transition">
                    Get Started Free
                </a>
            @endauth
        </div>
    </nav>

    {{-- Hero --}}
    <section class="max-w-3xl mx-auto px-6 py-20 text-center">
        <span class="inline-block bg-purple-100 text-purple-700 text-sm font-semibold
                     px-4 py-1 rounded-full mb-6">
            AI-Powered · Structured · Daily
        </span>
        <h1 class="text-5xl font-bold text-gray-900 leading-tight mb-6">
            Think Clearer.<br>Decide Faster.<br>
            <span class="text-purple-600">Every Day.</span>
        </h1>
        <p class="text-xl text-gray-400 mb-10 max-w-xl mx-auto leading-relaxed">
            Not another chatbot. A structured AI coach that guides you through
            procrastination, overthinking, and decisions — with a framework, not just answers.
        </p>
        <div class="flex gap-4 justify-center flex-wrap">
            <a href="{{ route('register') }}"
               class="bg-purple-600 hover:bg-purple-700 text-white font-bold
                      px-8 py-4 rounded-xl text-lg transition">
                Start for Free →
            </a>
            <a href="{{ route('subscription.index') }}"
               class="border border-gray-200 text-gray-600 hover:bg-gray-50
                      font-semibold px-8 py-4 rounded-xl text-lg transition">
                See Pricing
            </a>
        </div>
    </section>

    {{-- How it works --}}
    <section class="max-w-4xl mx-auto px-6 py-16">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-10">
            How It Works
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach([
                ['step'=>'01','icon'=>'✍️','title'=>'You share a thought',
                 'desc'=>'Type what\'s blocking you — a task you\'re avoiding, a decision you\'re stuck on, or a feeling you can\'t shake.'],
                ['step'=>'02','icon'=>'🤖','title'=>'AI classifies & guides',
                 'desc'=>'Gemini AI detects your thinking pattern and applies a structured framework — not a generic reply.'],
                ['step'=>'03','icon'=>'⚡','title'=>'You take one action',
                 'desc'=>'Every response ends with one small, specific step you can take in the next 5 minutes.'],
            ] as $item)
                <div class="bg-white rounded-2xl border p-6 shadow-sm">
                    <div class="text-3xl mb-3">{{ $item['icon'] }}</div>
                    <span class="text-xs text-purple-400 font-bold">STEP {{ $item['step'] }}</span>
                    <h3 class="font-bold text-gray-800 mt-1 mb-2">{{ $item['title'] }}</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">{{ $item['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Features --}}
    <section class="bg-white border-y py-16">
        <div class="max-w-4xl mx-auto px-6">
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-10">
                Everything You Need to Think Better
            </h2>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                @foreach([
                    ['🧠', 'Thinking Frameworks', 'Proven patterns for every mental block'],
                    ['⚖️', 'Decision Mode', 'Structured Option A vs B analysis'],
                    ['📊', 'Weekly Reports', 'See your behavioral patterns over time'],
                    ['🔥', 'Streak System', 'Build a daily thinking habit'],
                    ['🇧🇩', 'Bangla Support', 'Full support in Bengali language'],
                    ['🔒', 'Private & Safe', 'Your entries are yours alone'],
                ] as $f)
                    <div class="bg-gray-50 rounded-xl p-5 border">
                        <div class="text-2xl mb-2">{{ $f[0] }}</div>
                        <p class="font-semibold text-gray-700 text-sm">{{ $f[1] }}</p>
                        <p class="text-gray-400 text-xs mt-1">{{ $f[2] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="max-w-2xl mx-auto px-6 py-20 text-center">
        <h2 class="text-3xl font-bold text-gray-800 mb-4">
            Ready to think better?
        </h2>
        <p class="text-gray-400 mb-8">Free forever. No credit card needed.</p>
        <a href="{{ route('register') }}"
           class="inline-block bg-purple-600 hover:bg-purple-700 text-white
                  font-bold px-10 py-4 rounded-xl text-lg transition">
            Get Started Free →
        </a>
    </section>

    {{-- Footer --}}
    <footer class="border-t py-6 text-center text-gray-400 text-sm">
        © {{ date('Y') }} AI Daily Thinking Coach. Built with Laravel + Gemini AI.
    </footer>

</body>
</html>