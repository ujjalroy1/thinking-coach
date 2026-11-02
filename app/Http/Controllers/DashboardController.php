<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //

     public function index()
    {
        $user        = auth()->user();
        $todayCount  = $user->todayEntryCount();
        $streak      = $user->streak;
        $recentEntries = $user->entries()
                              ->latest()
                              ->take(5)
                              ->get();

        return view('dashboard', compact(
            'user',
            'todayCount',
            'streak',
            'recentEntries'
        ));
    }
}
