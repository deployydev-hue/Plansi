<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskFormExperienceTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_page_loads_the_supported_fields_and_only_the_users_categories(): void
    {
        $user = User::factory()->create();
        $ownCategory = Category::factory()->create(['user_id' => $user->id, 'name' => 'Client work']);
        $foreignCategory = Category::factory()->create(['name' => 'Private category']);

        $this->actingAs($user)
            ->get(route('tasks.create'))
            ->assertOk()
            ->assertSee('Create a task')
            ->assertSee('name="title"', false)
            ->assertSee('name="description"', false)
            ->assertSee('name="due_at"', false)
            ->assertSee('name="priority"', false)
            ->assertSee('name="category_id"', false)
            ->assertSee('name="status"', false)
            ->assertSee($ownCategory->name)
            ->assertDontSee($foreignCategory->name);
    }

    public function test_edit_page_loads_existing_values_for_the_owner(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $user->id, 'name' => 'A very long customer research category']);
        $task = Task::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Prepare a detailed product launch readiness review',
            'description' => 'Keep every existing planning detail available while editing.',
            'priority' => 'high',
            'status' => 'pending',
            'due_at' => '2026-09-12 14:30:00',
        ]);

        $this->actingAs($user)
            ->get(route('tasks.edit', $task))
            ->assertOk()
            ->assertSee($task->title)
            ->assertSee($task->description)
            ->assertSee('2026-09-12T14:30')
            ->assertSee($category->name)
            ->assertSee('value="high"', false)
            ->assertSee('checked', false);
    }

    public function test_another_user_cannot_open_the_edit_page(): void
    {
        $task = Task::factory()->create();

        $this->actingAs(User::factory()->create())
            ->get(route('tasks.edit', $task))
            ->assertForbidden();
    }

    public function test_valid_creation_still_succeeds_with_optional_values(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->post(route('tasks.store'), [
                'title' => 'Finalize interview guide',
                'description' => 'Include the accessibility follow-up questions.',
                'priority' => 'high',
                'status' => 'pending',
                'category_id' => $category->id,
                'due_at' => '2026-09-15T09:45',
            ])
            ->assertRedirect(route('tasks.index'));

        $this->assertDatabaseHas('tasks', [
            'user_id' => $user->id,
            'title' => 'Finalize interview guide',
            'priority' => 'high',
            'status' => 'pending',
            'category_id' => $category->id,
            'completed_at' => null,
        ]);
    }

    public function test_invalid_creation_fails_and_preserves_submitted_values(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->from(route('tasks.create'))
            ->post(route('tasks.store'), [
                'title' => '',
                'description' => 'Keep this description after validation.',
                'priority' => 'urgent',
                'status' => 'pending',
                'due_at' => 'not-a-date',
            ])
            ->assertRedirect(route('tasks.create'))
            ->assertSessionHasErrors(['title', 'priority', 'due_at'])
            ->assertSessionHasInput('description', 'Keep this description after validation.');
    }

    public function test_normal_edit_preserves_completed_status_and_completion_timestamp(): void
    {
        $user = User::factory()->create();
        $completedAt = now()->subDays(2)->startOfSecond();
        $task = Task::factory()->create([
            'user_id' => $user->id,
            'status' => 'completed',
            'completed_at' => $completedAt,
            'category_id' => null,
            'due_at' => null,
        ]);

        $this->actingAs($user)
            ->put(route('tasks.update', $task), [
                'title' => 'Updated completed task title',
                'description' => null,
                'priority' => 'low',
                'status' => 'completed',
                'category_id' => null,
                'due_at' => null,
            ])
            ->assertRedirect(route('tasks.index'));

        $task->refresh();

        $this->assertSame('Updated completed task title', $task->title);
        $this->assertSame('completed', $task->status);
        $this->assertTrue($task->completed_at->equalTo($completedAt));
        $this->assertNull($task->category_id);
        $this->assertNull($task->due_at);
    }

    public function test_invalid_update_and_foreign_category_fail_without_changing_the_task(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id, 'title' => 'Original title']);
        $foreignCategory = Category::factory()->create();

        $this->actingAs($user)
            ->put(route('tasks.update', $task), [
                'title' => 'Changed title',
                'description' => null,
                'priority' => 'high',
                'status' => 'pending',
                'category_id' => $foreignCategory->id,
                'due_at' => null,
            ])
            ->assertSessionHasErrors('category_id');

        $this->assertSame('Original title', $task->fresh()->title);
    }

    public function test_delete_from_edit_experience_keeps_existing_authorization(): void
    {
        $owner = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $owner->id]);

        $this->actingAs(User::factory()->create())
            ->delete(route('tasks.destroy', $task))
            ->assertForbidden();

        $this->actingAs($owner)
            ->delete(route('tasks.destroy', $task))
            ->assertRedirect(route('tasks.index'));

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }
}
