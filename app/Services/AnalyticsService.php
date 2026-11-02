<?php

namespace App\Services;

use App\Models\Entrie;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class AnalyticsService
{
    private const MAX_DAYS = 365;

    /**
     * Category breakdown for a user over N days: ['anxiety' => 4, ...]
     */
    public function getCategoryBreakdown(int $userId, int $days = 7): Collection
    {
        return $this->since($userId, $days)
            ->selectRaw('category, COUNT(*) as total')
            ->groupBy('category')
            ->orderByDesc('total')
            ->pluck('total', 'category')
            ->map(fn ($count) => (int) $count);
    }

    /**
     * Hour of day (0-23) the user submits most entries.
     */
    public function getPeakHour(int $userId, int $days = 7): ?int
    {
        $hour = $this->since($userId, $days)
            ->selectRaw('HOUR(created_at) as hour, COUNT(*) as total')
            ->groupBy('hour')
            ->orderByDesc('total')
            ->value('hour');

        return $hour === null ? null : (int) $hour;
    }

    /**
     * This week vs last week, in a single query.
     */
    public function getWeekComparison(int $userId): array
    {
        $thisWeekStart = now()->startOfWeek();
        $lastWeekStart = $thisWeekStart->copy()->subWeek();

        $row = Entrie::query()
            ->where('user_id', $userId)
            ->where('created_at', '>=', $lastWeekStart)
            ->selectRaw(
                'SUM(created_at >= ?) as this_week, SUM(created_at < ?) as last_week',
                [$thisWeekStart, $thisWeekStart]
            )
            ->first();

        $thisWeek = (int) ($row?->this_week ?? 0);
        $lastWeek = (int) ($row?->last_week ?? 0);

        return [
            'this_week'      => $thisWeek,
            'last_week'      => $lastWeek,
            'change'         => $thisWeek - $lastWeek,
            'change_percent' => $lastWeek > 0
                ? round(($thisWeek - $lastWeek) / $lastWeek * 100, 1)
                : null,
        ];
    }

    /**
     * The user's most common category of all time.
     */
    public function getTopCategory(int $userId): ?string
    {
        return Entrie::query()
            ->where('user_id', $userId)
            ->selectRaw('category, COUNT(*) as total')
            ->groupBy('category')
            ->orderByDesc('total')
            ->value('category');
    }

    /**
     * Daily entry counts for the last N days, missing days filled with 0.
     */
    public function getDailyActivity(int $userId, int $days = 14): Collection
    {
        $days  = $this->clampDays($days);
        $start = now()->subDays($days - 1)->startOfDay();

        $counts = Entrie::query()
            ->where('user_id', $userId)
            ->where('created_at', '>=', $start)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->pluck('total', 'date');

        return collect(range(0, $days - 1))->mapWithKeys(function (int $offset) use ($start, $counts) {
            $date = $start->copy()->addDays($offset)->toDateString();

            return [$date => (int) ($counts[$date] ?? 0)];
        });
    }

    /**
     * Convert peak hour integer to a readable label.
     */
    public function formatPeakHour(?int $hour): string
    {
        if ($hour === null) {
            return 'Not enough data yet';
        }

        $period  = $hour < 12 ? 'AM' : 'PM';
        $display = $hour % 12 ?: 12;
        $label   = match (true) {
            $hour >= 5 && $hour < 12  => 'morning',
            $hour >= 12 && $hour < 17 => 'afternoon',
            $hour >= 17 && $hour < 21 => 'evening',
            default                   => 'night',
        };

        return "{$display}:00 {$period} ({$label})";
    }

    /**
     * Category counts for the weekly AI report.
     */
    public function buildWeeklySummaryForAI(int $userId): array
    {
        return $this->getCategoryBreakdown($userId, 7)->toArray();
    }

    private function since(int $userId, int $days): Builder
    {
        return Entrie::query()
            ->where('user_id', $userId)
            ->where('created_at', '>=', now()->subDays($this->clampDays($days)));
    }

    private function clampDays(int $days): int
    {
        return max(1, min($days, self::MAX_DAYS));
    }
}