<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class SubscriptionController extends Controller
{
    /**
     * Show the pricing/upgrade page.
     */
    public function index()
    {
        $user = auth()->user();
        return view('subscription.index', compact('user'));
    }

    /**
     * Manually upgrade a user to premium.
     * In production you would replace this with a real
     * payment gateway (SSLCommerz, Stripe, etc.)
     */
    public function upgrade(Request $request)
    {
        $request->validate([
            'plan' => 'required|in:monthly,yearly',
        ]);

        $user = auth()->user();
        $user->update(['role' => 'premium']);

        return redirect()->route('dashboard')
            ->with('success', '🎉 Welcome to Premium! All features are now unlocked.');
    }

    /**
     * Downgrade back to free (cancel subscription).
     */
    public function downgrade()
    {
        auth()->user()->update(['role' => 'free']);

        return redirect()->route('subscription.index')
            ->with('success', 'Your plan has been changed to Free.');
    }
}