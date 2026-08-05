<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskIndexRequest;
use App\Http\Requests\TaskRequest;
use App\Models\Task;

class TaskController extends Controller
{
    /**
     * Display the logged-in user's tasks.
     */
    public function index(TaskIndexRequest $request)
    {
        $filters = $request->validated();
        $query = $request->user()->tasks()->with('category');

        // Search
        if (! empty($filters['search'])) {
            $search = $filters['search'];

            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Filter by priority
        if (! empty($filters['priority'])) {
            $query->where('priority', $filters['priority']);
        }

        // Filter by category
        if (! empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        // Filter by due date
        if (! empty($filters['due_date'])) {
            match ($filters['due_date']) {
                'today' => $query->dueToday(),
                'overdue' => $query->overdue(),
                'upcoming' => $query->upcoming(),
                'no_due' => $query->whereNull('due_at'),
            };
        }

        // Sort
        switch ($filters['sort'] ?? 'newest') {
            case 'oldest':
                $query->oldest();
                break;

            case 'due_soon':
                $query
                    ->orderByRaw('due_at IS NULL')
                    ->orderBy('due_at');
                break;

            case 'priority_high':
                $query->orderByRaw("
                CASE priority
                    WHEN 'high' THEN 1
                    WHEN 'medium' THEN 2
                    WHEN 'low' THEN 3
                END
            ");
                break;

            default:
                $query->latest();
                break;
        }

        $tasks = $query->get();

        $categories = auth()
            ->user()
            ->categories()
            ->orderBy('name')
            ->get();

        return view('tasks.index', compact('tasks', 'categories'));
    }

    /**
     * Show the form for creating a new task.
     */
    public function create()
    {
        $categories = auth()
            ->user()
            ->categories()
            ->orderBy('name')
            ->get();

        return view('tasks.create', compact('categories'));
    }

    /**
     * Store a new task.
     */
    public function store(TaskRequest $request)
    {
        $validated = $request->validated();

        $validated['completed_at'] =
            $validated['status'] === 'completed'
                ? now()
                : null;

        auth()
            ->user()
            ->tasks()
            ->create($validated);

        return redirect()
            ->route('tasks.index')
            ->with(
                'success',
                'Task created successfully.'
            );
    }

    /**
     * Show the form for editing a task.
     */
    public function edit(Task $task)
    {
        $this->ensureTaskBelongsToUser($task);

        $categories = auth()
            ->user()
            ->categories()
            ->orderBy('name')
            ->get();

        return view(
            'tasks.edit',
            compact('task', 'categories')
        );
    }

    /**
     * Update an existing task.
     */
    public function update(
        TaskRequest $request,
        Task $task
    ) {
        $this->ensureTaskBelongsToUser($task);

        $validated = $request->validated();

        if ($validated['status'] === 'completed') {
            $validated['completed_at'] =
                $task->completed_at ?? now();
        } else {
            $validated['completed_at'] = null;
        }

        $task->update($validated);

        return redirect()
            ->route('tasks.index')
            ->with(
                'success',
                'Task updated successfully.'
            );
    }

    public function toggle(Task $task)
    {
        $this->ensureTaskBelongsToUser($task);

        if ($task->status === 'completed') {
            $task->update([
                'status' => 'pending',
                'completed_at' => null,
            ]);
        } else {
            $task->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);
        }

        return back()->with(
            'success',
            'Task status updated successfully.'
        );
    }

    /**
     * Delete a task.
     */
    public function destroy(Task $task)
    {
        $this->ensureTaskBelongsToUser($task);

        $task->delete();

        return redirect()
            ->route('tasks.index')
            ->with(
                'success',
                'Task deleted successfully.'
            );
    }

    /**
     * Make sure the task belongs
     * to the logged-in user.
     */
    private function ensureTaskBelongsToUser(
        Task $task
    ): void {
        abort_unless(
            $task->user_id === auth()->id(),
            403
        );
    }
}
