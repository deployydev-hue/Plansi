<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $startOfToday = today()->startOfDay();
        $startOfTomorrow = today()->addDay()->startOfDay();

        /*
         * Keep the account-level figures in one aggregate query. These values
         * remain available for regression compatibility, but the redesigned
         * dashboard presents them only as secondary context.
         */
        $taskStats = $user->tasks()
            ->selectRaw(
                <<<'SQL'
                    COUNT(*) AS total,
                    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) AS pending,
                    SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) AS completed,
                    SUM(CASE WHEN status = 'pending' AND priority = 'high' THEN 1 ELSE 0 END) AS high_priority,
                    SUM(CASE WHEN status = 'pending' AND due_at < ? THEN 1 ELSE 0 END) AS overdue
                SQL,
                [$startOfToday]
            )
            ->first();

        $totalTasks = (int) ($taskStats->total ?? 0);
        $pendingTasks = (int) ($taskStats->pending ?? 0);
        $completedTasks = (int) ($taskStats->completed ?? 0);
        $highPriorityTasks = (int) ($taskStats->high_priority ?? 0);
        $overdueTasks = (int) ($taskStats->overdue ?? 0);
        $categoriesCount = $user->categories()->count();

        $completionPercentage = $totalTasks > 0
            ? round(($completedTasks / $totalTasks) * 100)
            : 0;

        /*
         * Today's progress includes both pending and completed work due today.
         * The existing dueToday scope intentionally returns pending tasks only,
         * so this aggregate is kept separate.
         */
        $todayStats = $user->tasks()
            ->whereDate('due_at', today())
            ->selectRaw(
                <<<'SQL'
                    COUNT(*) AS total,
                    SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) AS completed
                SQL
            )
            ->first();

        $todayTotalCount = (int) ($todayStats->total ?? 0);
        $todayCompletedCount = (int) ($todayStats->completed ?? 0);
        $dueTodayTasks = $todayTotalCount - $todayCompletedCount;
        $todayProgressPercentage = $todayTotalCount > 0
            ? round(($todayCompletedCount / $todayTotalCount) * 100)
            : null;

        /*
         * A single deterministic query implements the approved focus order:
         * due-today high, due-today other, overdue high, overdue other,
         * upcoming high, then upcoming other.
         */
        $focusTask = $user->tasks()
            ->with('category')
            ->pending()
            ->whereNotNull('due_at')
            ->orderByRaw(
                <<<'SQL'
                    CASE
                        WHEN due_at >= ? AND due_at < ? AND priority = 'high' THEN 1
                        WHEN due_at >= ? AND due_at < ? THEN 2
                        WHEN due_at < ? AND priority = 'high' THEN 3
                        WHEN due_at < ? THEN 4
                        WHEN due_at >= ? AND priority = 'high' THEN 5
                        ELSE 6
                    END
                SQL,
                [
                    $startOfToday,
                    $startOfTomorrow,
                    $startOfToday,
                    $startOfTomorrow,
                    $startOfToday,
                    $startOfToday,
                    $startOfTomorrow,
                ]
            )
            ->orderBy('due_at')
            ->orderByDesc('created_at')
            ->first();

        $priorityOrder = <<<'SQL'
            CASE priority
                WHEN 'high' THEN 1
                WHEN 'medium' THEN 2
                WHEN 'low' THEN 3
                ELSE 4
            END
        SQL;

        $todayTasks = $user->tasks()
            ->with('category')
            ->dueToday()
            ->orderByRaw($priorityOrder)
            ->orderBy('due_at')
            ->take(5)
            ->get();

        $upcomingTasks = $user->tasks()
            ->with('category')
            ->upcoming()
            ->orderBy('due_at')
            ->orderByRaw($priorityOrder)
            ->take(4)
            ->get();

        $overdueTaskList = collect();

        if ($overdueTasks > 0) {
            $overdueTaskList = $user->tasks()
                ->with('category')
                ->overdue()
                ->orderByRaw($priorityOrder)
                ->orderBy('due_at')
                ->take(3)
                ->get();
        }

        $nameParts = preg_split('/\s+/', trim($user->name), flags: PREG_SPLIT_NO_EMPTY);
        $greetingName = $nameParts[0] ?? null;
        $greeting = match (true) {
            now()->hour < 12 => 'Good morning',
            now()->hour < 18 => 'Good afternoon',
            default => 'Good evening',
        };

        return view('dashboard', compact(
            'totalTasks',
            'pendingTasks',
            'completedTasks',
            'highPriorityTasks',
            'dueTodayTasks',
            'overdueTasks',
            'categoriesCount',
            'completionPercentage',
            'todayTotalCount',
            'todayCompletedCount',
            'todayProgressPercentage',
            'focusTask',
            'todayTasks',
            'upcomingTasks',
            'overdueTaskList',
            'greetingName',
            'greeting'
        ));
    }
}
