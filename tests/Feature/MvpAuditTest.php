<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Task;
use App\Models\User;
use Database\Seeders\DemoWorkspaceSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Tests\TestCase;

class MvpAuditTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        putenv('SMART_TODO_DEMO_PASSWORD');
        parent::tearDown();
    }

    public function test_protected_pages_redirect_guests_to_login(): void
    {
        foreach (['/dashboard', '/tasks', '/tasks/create', '/categories'] as $uri) {
            $this->get($uri)->assertRedirect('/login');
        }
    }

    public function test_registration_validates_and_creates_an_authenticated_user(): void
    {
        $this->post('/register', [
            'name' => 'QA User',
            'email' => 'qa@example.test',
            'password' => 'secure-password',
            'password_confirmation' => 'secure-password',
      ])->assertRedirect(route('verification.notice'));
      
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', ['email' => 'qa@example.test']);

        $this->post('/logout')->assertRedirect('/login');
        $this->assertGuest();
    }

    public function test_login_attempts_are_rate_limited(): void
    {
        User::factory()->create(['email' => 'limited@example.test']);

        foreach (range(1, 5) as $attempt) {
            $this->post('/login', [
                'email' => 'limited@example.test',
                'password' => 'incorrect-value',
            ])->assertSessionHasErrors('email');
        }

        $this->post('/login', [
            'email' => 'limited@example.test',
            'password' => 'incorrect-value',
        ])->assertTooManyRequests();
    }

    public function test_task_validation_rejects_invalid_values_and_another_users_category(): void
    {
        $user = User::factory()->create();
        $foreignCategory = Category::factory()->create();

        $this->actingAs($user)->post('/tasks', [
            'title' => str_repeat('x', 151),
            'description' => str_repeat('x', 5001),
            'priority' => 'urgent',
            'status' => 'archived',
            'category_id' => $foreignCategory->id,
            'due_at' => 'not-a-date',
        ])->assertSessionHasErrors([
            'title', 'description', 'priority', 'status', 'category_id', 'due_at',
        ]);

        $this->assertDatabaseCount('tasks', 0);
    }

    public function test_category_names_are_unique_per_user_but_not_globally(): void
    {
        $first = User::factory()->create();
        $second = User::factory()->create();
        Category::factory()->create(['user_id' => $first->id, 'name' => 'Work']);

        $this->actingAs($first)->post('/categories', ['name' => 'Work'])
            ->assertSessionHasErrors('name');

        $this->actingAs($second)->post('/categories', ['name' => 'Work'])
            ->assertRedirect('/categories');
    }

    public function test_users_cannot_access_or_mutate_other_users_tasks(): void
    {
        $owner = User::factory()->create();
        $attacker = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $owner->id]);
        $payload = [
            'title' => 'Changed', 'description' => null, 'priority' => 'low',
            'status' => 'pending', 'category_id' => null, 'due_at' => null,
        ];

        $this->actingAs($attacker)->get("/tasks/{$task->id}/edit")->assertForbidden();
        $this->actingAs($attacker)->put("/tasks/{$task->id}", $payload)->assertForbidden();
        $this->actingAs($attacker)->patch("/tasks/{$task->id}/toggle")->assertForbidden();
        $this->actingAs($attacker)->delete("/tasks/{$task->id}")->assertForbidden();

        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'title' => $task->title]);
    }

    public function test_users_cannot_mutate_other_users_categories(): void
    {
        $owner = User::factory()->create();
        $attacker = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $owner->id]);

        $this->actingAs($attacker)->put("/categories/{$category->id}", ['name' => 'Changed'])
            ->assertForbidden();
        $this->actingAs($attacker)->delete("/categories/{$category->id}")
            ->assertForbidden();

        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }

    public function test_deleting_a_category_keeps_tasks_and_clears_their_category(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $user->id]);
        $task = Task::factory()->create(['user_id' => $user->id, 'category_id' => $category->id]);

        $this->actingAs($user)->delete("/categories/{$category->id}")
            ->assertRedirect('/categories');

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'category_id' => null]);
    }

    public function test_due_date_buckets_are_mutually_exclusive_and_ignore_completed_tasks(): void
    {
        Carbon::setTestNow('2026-08-03 12:00:00');
        $user = User::factory()->create();

        $yesterday = Task::factory()->create(['user_id' => $user->id, 'title' => 'Yesterday', 'status' => 'pending', 'due_at' => now()->subDay()]);
        $earlierToday = Task::factory()->create(['user_id' => $user->id, 'title' => 'Earlier', 'status' => 'pending', 'due_at' => now()->subMinute()]);
        $laterToday = Task::factory()->create(['user_id' => $user->id, 'title' => 'Later', 'status' => 'pending', 'due_at' => now()->addHour()]);
        $tomorrow = Task::factory()->create(['user_id' => $user->id, 'title' => 'Tomorrow', 'status' => 'pending', 'due_at' => now()->addDay()]);
        Task::factory()->create(['user_id' => $user->id, 'title' => 'Completed past', 'status' => 'completed', 'due_at' => now()->subDay()]);

        $this->actingAs($user)->get('/tasks?due_date=overdue')->assertOk()->assertViewHas('tasks', fn ($tasks) => $tasks->modelKeys() === [$yesterday->id]);
        $this->actingAs($user)->get('/tasks?due_date=today')->assertOk()->assertViewHas('tasks', fn ($tasks) => $tasks->pluck('id')->sort()->values()->all() === collect([$earlierToday->id, $laterToday->id])->sort()->values()->all());
        $this->actingAs($user)->get('/tasks?due_date=upcoming')->assertOk()->assertViewHas('tasks', fn ($tasks) => $tasks->modelKeys() === [$tomorrow->id]);
    }

    public function test_filters_combine_sort_and_remain_scoped_to_the_current_user(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $user->id]);

        $older = Task::factory()->create([
            'user_id' => $user->id, 'category_id' => $category->id, 'title' => 'Release review',
            'description' => 'security checklist', 'status' => 'pending', 'priority' => 'high',
            'due_at' => now()->subDay(), 'created_at' => now()->subDays(2),
        ]);
        $newer = Task::factory()->create([
            'user_id' => $user->id, 'category_id' => $category->id, 'title' => 'Release notes',
            'description' => 'security summary', 'status' => 'pending', 'priority' => 'high',
            'due_at' => now()->subDays(2), 'created_at' => now(),
        ]);
        Task::factory()->create(['user_id' => $other->id, 'title' => 'Release secret', 'status' => 'pending', 'priority' => 'high', 'due_at' => now()->subDay()]);

        $query = http_build_query([
            'search' => 'security', 'status' => 'pending', 'priority' => 'high',
            'category_id' => $category->id, 'due_date' => 'overdue', 'sort' => 'oldest',
        ]);

        $this->actingAs($user)->get("/tasks?{$query}")
            ->assertOk()
            ->assertViewHas('tasks', fn ($tasks) => $tasks->modelKeys() === [$older->id, $newer->id]);
    }

    public function test_dashboard_statistics_are_accurate_and_zero_safe(): void
    {
        Carbon::setTestNow('2026-08-03 12:00:00');
        $user = User::factory()->create();

        $this->actingAs($user)->get('/dashboard')
            ->assertViewHas('completionPercentage', 0)
            ->assertViewHas('totalTasks', 0);

        Task::factory()->create(['user_id' => $user->id, 'status' => 'completed', 'priority' => 'low', 'due_at' => now()->subDay()]);
        Task::factory()->create(['user_id' => $user->id, 'status' => 'pending', 'priority' => 'high', 'due_at' => now()->subDay()]);
        Task::factory()->create(['user_id' => $user->id, 'status' => 'pending', 'priority' => 'medium', 'due_at' => now()->subMinute()]);

        $this->actingAs($user)->get('/dashboard')
            ->assertViewHas('totalTasks', 3)
            ->assertViewHas('completedTasks', 1)
            ->assertViewHas('pendingTasks', 2)
            ->assertViewHas('highPriorityTasks', 1)
            ->assertViewHas('overdueTasks', 1)
            ->assertViewHas('dueTodayTasks', 1)
            ->assertViewHas('completionPercentage', 33.0);
    }

    public function test_invalid_filter_values_and_foreign_categories_are_rejected(): void
    {
        $user = User::factory()->create();
        $foreignCategory = Category::factory()->create();

        $this->actingAs($user)->get('/tasks?status=invalid&sort=drop-table')
            ->assertSessionHasErrors(['status', 'sort']);
        $this->actingAs($user)->get("/tasks?category_id={$foreignCategory->id}")
            ->assertSessionHasErrors('category_id');
    }

    public function test_demo_seeder_creates_a_large_varied_workspace_only_when_explicitly_called(): void
    {
        putenv('SMART_TODO_DEMO_PASSWORD='.Str::random(20));

        $this->seed(DemoWorkspaceSeeder::class);

        $user = User::query()->where('email', 'demo@smart-todo.test')->firstOrFail();

        $this->assertSame(80, $user->tasks()->count());
        $this->assertSame(10, $user->categories()->count());
        $this->assertTrue($user->tasks()->whereNull('due_at')->exists());
        $this->assertTrue($user->tasks()->where('status', 'completed')->exists());
        $this->assertTrue($user->tasks()->where('status', 'pending')->exists());
        $this->assertTrue($user->categories()->whereDoesntHave('tasks')->exists());
    }

    public function test_custom_error_pages_render_without_debug_details(): void
    {
        foreach ([403, 404, 419, 500] as $status) {
            $html = view("errors.{$status}")->render();

            $this->assertStringContainsString("Error {$status}", $html);
            $this->assertStringNotContainsString(base_path(), $html);
            $this->assertStringNotContainsString('Stack trace', $html);
        }
    }
}
