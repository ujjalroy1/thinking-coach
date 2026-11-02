<?php

namespace App\Http\Controllers;

use App\Services\AnalyticsService;
use App\Services\GeminiService;

class ReportController extends Controller
{
    public function __construct(
        protected AnalyticsService $analytics,
        protected GeminiService    $gemini
    ) {}

    public function index()
    {
        $user   = auth()->user();
        $userId = $user->id;

        // Gather all analytics data
        $categoryBreakdown = $this->analytics->getCategoryBreakdown($userId, 7);
        $peakHour          = $this->analytics->getPeakHour($userId, 7);
        $peakHourLabel     = $this->analytics->formatPeakHour($peakHour);
        $weekComparison    = $this->analytics->getWeekComparison($userId);
        $topCategory       = $this->analytics->getTopCategory($userId);
        $dailyActivity     = $this->analytics->getDailyActivity($userId, 14);
        $totalEntries      = $user->entries()->count();
        $streak            = $user->streak;

        // Generate AI weekly report only if user has entries this week
        $aiReport = null;
        if ($categoryBreakdown->isNotEmpty()) {
            $summary  = $this->analytics->buildWeeklySummaryForAI($userId);
            $aiReport = $this->gemini->generateWeeklyReport($summary, 'en');
        }

        return view('reports.index', compact(
            'categoryBreakdown',
            'peakHourLabel',
            'weekComparison',
            'topCategory',
            'dailyActivity',
            'totalEntries',
            'streak',
            'aiReport'
        ));
    }
}