<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class DashboardRedesignTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_focus_task_uses_the_approved_deterministic_priority_order(): void
    {
        Carbon::setTestNow('2026-08-28 12:00:00');
        $user = User::factory()->create();

        Task::factory()->create([
            'user_id' => $user->id,
            'title' => 'Upcoming high',
            'status' => 'pending',
            'priority' => 'high',
            'due_at' => now()->addDay(),
        ]);

        Task::factory()->create([
            'user_id' => $user->id,
            'title' => 'Overdue high',
            'status' => 'pending',
            'priority' => 'high',
            'due_at' => now()->subDay(),
        ]);

        Task::factory()->create([
            'user_id' => $user->id,
            'title' => 'Today medium',
            'status' => 'pending',
            'priority' => 'medium',
            'due_at' => now()->addHours(2),
        ]);

        $expectedFocus = Task::factory()->create([
            'user_id' => $user->id,
            'title' => 'Today high',
            'status' => 'pending',
            'priority' => 'high',
            'due_at' => now()->addHours(4),
        ]);

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertOk()
            ->assertViewHas('focusTask', fn ($task) => $task->is($expectedFocus));
    }

    public function test_dashboard_lists_are_bounded_and_daily_progress_is_accurate(): void
    {
        Carbon::setTestNow('2026-08-28 12:00:00');
        $user = User::factory()->create();

        Task::factory()->count(6)->create([
            'user_id' => $user->id,
            'status' => 'pending',
            'due_at' => now()->addHour(),
        ]);

        Task::factory()->count(2)->create([
            'user_id' => $user->id,
            'status' => 'completed',
            'completed_at' => now(),
            'due_at' => now()->addHours(2),
        ]);

        Task::factory()->count(5)->create([
            'user_id' => $user->id,
            'status' => 'pending',
            'due_at' => now()->addDays(2),
        ]);

        Task::factory()->count(4)->create([
            'user_id' => $user->id,
            'status' => 'pending',
            'due_at' => now()->subDay(),
        ]);

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertOk()
            ->assertViewHas('todayTasks', fn ($tasks) => $tasks->count() === 5)
            ->assertViewHas('upcomingTasks', fn ($tasks) => $tasks->count() === 4)
            ->assertViewHas('overdueTaskList', fn ($tasks) => $tasks->count() === 3)
            ->assertViewHas('todayTotalCount', 8)
            ->assertViewHas('todayCompletedCount', 2)
            ->assertViewHas('todayProgressPercentage', 25.0)
            ->assertViewHas('overdueTasks', 4);
    }

    public function test_dashboard_renders_new_workspace_and_completed_day_states(): void
    {
        Carbon::setTestNow('2026-08-28 12:00:00');
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertOk()
            ->assertSee('Start with what matters.');

        Task::factory()->create([
            'user_id' => $user->id,
            'status' => 'completed',
            'completed_at' => now(),
            'due_at' => now()->addHour(),
        ]);

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertOk()
            ->assertSee('Today is complete.')
            ->assertSee('1 of 1 completed');
    }
}
