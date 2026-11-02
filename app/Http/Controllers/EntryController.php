<?php

namespace App\Http\Controllers;

use App\Models\Entrie;
use App\Models\Entry;
use App\Models\Streak;
use App\Services\GeminiService;
use Illuminate\Http\Request;

class EntryController extends Controller
{
    protected GeminiService $gemini;

    public function __construct(GeminiService $gemini)
    {
        $this->gemini = $gemini;
    }

    /**
     * Show the new entry form.
     */
    public function create()
    {
        $user = auth()->user();

        if (!$user->canSubmitEntry()) {
            return redirect()->route('dashboard')
                ->with('error', 'You have reached your 3 daily entries. Upgrade to Premium for unlimited access.');
        }

        return view('entries.create');
    }

    /**
     * Process the entry and get AI response.
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        if (!$user->canSubmitEntry()) {
            return redirect()->route('dashboard')
                ->with('error', 'Daily limit reached. Upgrade to Premium.');
        }

        $request->validate([
            'user_input' => 'required|string|min:10|max:1000',
            'language'   => 'required|in:en,bn',
            'mode'       => 'required|in:thinking,decision',
            // Decision mode fields
            'option_a'   => 'required_if:mode,decision|nullable|string|max:300',
            'option_b'   => 'required_if:mode,decision|nullable|string|max:300',
        ]);

        $userInput = $request->user_input;
        $language  = $request->language;
        $mode      = $request->mode;

        // Step 1: Classify the input
        $category = $this->gemini->classify($userInput);

        // Step 2: Generate AI response based on mode
        if ($mode === 'decision') {
            $aiResponse = $this->gemini->generateDecisionSupport(
                $userInput,
                $request->option_a ?? '',
                $request->option_b ?? '',
                $language
            );
            $category = 'decision_confusion';
        } else {
            $aiResponse = $this->gemini->generateStructuredResponse(
                $userInput,
                $category,
                $language
            );
        }

        // Step 3: Save the entry
        $entry = Entrie::create([
            'user_id'    => $user->id,
            'user_input' => $userInput,
            'category'   => $category,
            'ai_response'=> $aiResponse,
            'language'   => $language,
        ]);

        // Step 4: Update streak
        $this->updateStreak($user);

        return redirect()->route('entries.show', $entry->id);
    }

    /**
     * Show a single entry with AI response.
     */
    public function show(Entrie $entry)
    {
        // Security: users can only see their own entries
        if ($entry->user_id !== auth()->id()) {
            abort(403);
        }

        return view('entries.show', compact('entry'));
    }

    /**
     * Show all past entries (premium only).
     */
    public function index()
    {
        $entries = auth()->user()
            ->entries()
            ->latest()
            ->paginate(10);

        return view('entries.index', compact('entries'));
    }

    /**
     * Update or create streak for the user.
     */
    private function updateStreak($user): void
    {
        $streak = Streak::firstOrCreate(
            ['user_id' => $user->id],
            ['current_streak' => 0, 'last_entry_date' => null]
        );

        $today     = today()->toDateString();
        $yesterday = today()->subDay()->toDateString();
        $lastDate  = $streak->last_entry_date?->toDateString();

        if ($lastDate === $today) {
            // Already submitted today — no streak change
            return;
        }

        if ($lastDate === $yesterday) {
            // Continuing the streak
            $streak->current_streak += 1;
        } else {
            // Streak broken or first entry ever
            $streak->current_streak = 1;
        }

        $streak->last_entry_date = $today;
        $streak->save();
    }
}