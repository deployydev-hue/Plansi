<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class TasksWorkspaceTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    public function test_tasks_are_paginated_at_twenty_and_query_strings_are_preserved(): void
    {
        $user = User::factory()->create();

        Task::factory()->count(45)->create([
            'user_id' => $user->id,
            'status' => 'pending',
            'priority' => 'high',
            'description' => 'release planning',
        ]);

        $response = $this->actingAs($user)->get('/tasks?search=release&status=pending&priority=high&sort=oldest&page=2');

        $response->assertOk()->assertViewHas('tasks', function ($tasks) {
            $this->assertInstanceOf(LengthAwarePaginator::class, $tasks);
            $this->assertSame(20, $tasks->perPage());
            $this->assertSame(2, $tasks->currentPage());
            $this->assertCount(20, $tasks->items());
            $this->assertSame(45, $tasks->total());
            $this->assertStringContainsString('search=release', $tasks->nextPageUrl());
            $this->assertStringContainsString('priority=high', $tasks->nextPageUrl());
            $this->assertStringContainsString('sort=oldest', $tasks->nextPageUrl());

            return true;
        });
    }

    public function test_workspace_handles_zero_one_and_fifteen_task_boundaries(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/tasks')
            ->assertOk()
            ->assertViewHas('tasks', fn ($tasks) => $tasks->total() === 0);

        Task::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)->get('/tasks')
            ->assertOk()
            ->assertViewHas('tasks', fn ($tasks) => $tasks->total() === 1 && $tasks->count() === 1);

        Task::factory()->count(14)->create(['user_id' => $user->id]);

        $this->actingAs($user)->get('/tasks')
            ->assertOk()
            ->assertViewHas('tasks', fn ($tasks) => $tasks->total() === 15 && $tasks->count() === 15 && ! $tasks->hasPages());
    }

    public function test_search_filters_and_sorting_still_combine_with_pagination(): void
    {
        Carbon::setTestNow('2026-09-03 12:00:00');
        $user = User::factory()->create();
        $other = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $user->id, 'name' => 'Product']);

        $later = Task::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Release plan later',
            'description' => 'workspace launch',
            'status' => 'pending',
            'priority' => 'high',
            'due_at' => now()->subDay(),
            'created_at' => now(),
        ]);
        $earlier = Task::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Release plan earlier',
            'description' => 'workspace launch',
            'status' => 'pending',
            'priority' => 'high',
            'due_at' => now()->subDays(2),
            'created_at' => now()->subDay(),
        ]);
        Task::factory()->create([
            'user_id' => $other->id,
            'title' => 'Release plan private',
            'description' => 'workspace launch',
            'status' => 'pending',
            'priority' => 'high',
            'due_at' => now()->subDays(3),
        ]);

        $query = http_build_query([
            'search' => 'workspace',
            'status' => 'pending',
            'priority' => 'high',
            'category_id' => $category->id,
            'due_date' => 'overdue',
            'sort' => 'oldest',
        ]);

        $this->actingAs($user)->get("/tasks?{$query}")
            ->assertOk()
            ->assertViewHas('tasks', fn ($tasks) => $tasks->getCollection()->modelKeys() === [$earlier->id, $later->id]);
    }

    public function test_quick_add_uses_safe_defaults_and_accepts_optional_details(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)->post('/tasks', [
            'quick_add' => '1',
            'title' => 'Capture customer follow-up',
            'status' => 'pending',
            'priority' => 'medium',
            'category_id' => $category->id,
            'due_at' => '2026-09-08 14:30:00',
        ])->assertRedirect('/tasks');

        $this->assertDatabaseHas('tasks', [
            'user_id' => $user->id,
            'title' => 'Capture customer follow-up',
            'status' => 'pending',
            'priority' => 'medium',
            'category_id' => $category->id,
            'completed_at' => null,
        ]);
    }

    public function test_quick_add_rejects_invalid_input_and_preserves_the_title(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->from('/tasks')
            ->post('/tasks', [
                'quick_add' => '1',
                'title' => '',
                'status' => 'pending',
                'priority' => 'medium',
                'due_at' => 'not-a-date',
            ])
            ->assertRedirect('/tasks')
            ->assertSessionHasErrors(['title', 'due_at'])
            ->assertSessionHasInput('quick_add', '1');

        $this->assertDatabaseCount('tasks', 0);
    }

    public function test_ownership_delete_and_completion_behavior_remain_intact(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $task = Task::factory()->create([
            'user_id' => $owner->id,
            'status' => 'pending',
            'completed_at' => null,
        ]);

        $this->actingAs($other)->patch("/tasks/{$task->id}/toggle")->assertForbidden();
        $this->actingAs($other)->delete("/tasks/{$task->id}")->assertForbidden();

        $this->actingAs($owner)->patch("/tasks/{$task->id}/toggle")->assertRedirect();
        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'status' => 'completed']);
        $this->assertNotNull($task->fresh()->completed_at);

        $this->actingAs($owner)->patch("/tasks/{$task->id}/toggle")->assertRedirect();
        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'status' => 'pending', 'completed_at' => null]);

        $this->actingAs($owner)->delete("/tasks/{$task->id}")->assertRedirect('/tasks');
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_full_create_and_edit_routes_remain_available(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)->get('/tasks/create')->assertOk();
        $this->actingAs($user)->get("/tasks/{$task->id}/edit")->assertOk();
    }
}
