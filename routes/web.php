<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EntryController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SubscriptionController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
         ->name('dashboard');

    // Entries
    Route::get('/entries/create',  [EntryController::class, 'create'])->name('entries.create');
    Route::post('/entries',        [EntryController::class, 'store'])->name('entries.store');
    Route::get('/entries/{entry}', [EntryController::class, 'show'])->name('entries.show');

    // Subscription
    Route::get('/subscription',            [SubscriptionController::class, 'index'])   ->name('subscription.index');
    Route::post('/subscription/upgrade',   [SubscriptionController::class, 'upgrade']) ->name('subscription.upgrade');
    Route::post('/subscription/downgrade', [SubscriptionController::class, 'downgrade'])->name('subscription.downgrade');

    // Premium only
    Route::middleware('premium')->group(function () {
        Route::get('/entries', [EntryController::class, 'index'])->name('entries.index');
        Route::get('/reports', [ReportController::class, 'index'])->name('reports');
    });

});

// Profile
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])  ->name('profile.edit');
    Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
});

require __DIR__.'/auth.php';