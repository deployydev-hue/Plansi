<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Total tasks
        $totalTasks = $user
            ->tasks()
            ->count();

        // Pending tasks
        $pendingTasks = $user
            ->tasks()
            ->pending()
            ->count();

        // Completed tasks
        $completedTasks = $user
            ->tasks()
            ->where('status', 'completed')
            ->count();

        // High priority pending tasks
        $highPriorityTasks = $user
            ->tasks()
            ->where('priority', 'high')
            ->where('status', 'pending')
            ->count();

        // Tasks due today
        $dueTodayTasks = $user
            ->tasks()
            ->dueToday()
            ->count();

        // Overdue tasks
        $overdueTasks = $user
            ->tasks()
            ->overdue()
            ->count();

        // Categories count
        $categoriesCount = $user
            ->categories()
            ->count();

        // Completion percentage
        $completionPercentage = $totalTasks > 0
            ? round(($completedTasks / $totalTasks) * 100)
            : 0;

        // Recent tasks
        $recentTasks = $user
            ->tasks()
            ->with('category')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalTasks',
            'pendingTasks',
            'completedTasks',
            'highPriorityTasks',
            'dueTodayTasks',
            'overdueTasks',
            'categoriesCount',
            'completionPercentage',
            'recentTasks'
        ));
    }
}
