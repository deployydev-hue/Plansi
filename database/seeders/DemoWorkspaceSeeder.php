<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class DemoWorkspaceSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->environment(['local', 'testing'])) {
            throw new RuntimeException('DemoWorkspaceSeeder may only run in local or testing environments.');
        }

        $password = env('SMART_TODO_DEMO_PASSWORD');

        if (! is_string($password) || mb_strlen($password) < 8) {
            throw new RuntimeException('Set SMART_TODO_DEMO_PASSWORD to at least 8 characters before seeding.');
        }

        $user = User::query()->updateOrCreate(
            ['email' => 'demo@smart-todo.test'],
            ['name' => 'Demo Workspace', 'password' => Hash::make($password)],
        );

        $user->tasks()->delete();
        $user->categories()->delete();

        $categories = collect([
            'Work', 'Personal', 'Learning', 'Health', 'Finance',
            'Home', 'Planning', 'Errands', 'Ideas', 'Someday',
        ])->mapWithKeys(fn (string $name) => [
            $name => Category::query()->create(['user_id' => $user->id, 'name' => $name]),
        ]);

        $dueDates = [
            null,
            now()->subMonth(),
            now()->subDay(),
            now()->startOfDay()->addHours(8),
            now()->addHours(2),
            now()->addDay(),
            now()->addWeek(),
            now()->addMonth(),
        ];

        $categoryPool = $categories->except('Someday')->values();

        foreach (range(1, 80) as $index) {
            $status = $index % 3 === 0 ? 'completed' : 'pending';

            Task::factory()->create([
                'user_id' => $user->id,
                'category_id' => $index % 9 === 0 ? null : $categoryPool[($index - 1) % $categoryPool->count()]->id,
                'title' => $index % 10 === 0
                    ? "Plan and review the next milestone with every stakeholder involved ({$index})"
                    : fake()->sentence(fake()->numberBetween(3, 7)),
                'description' => match ($index % 4) {
                    0 => null,
                    1 => fake()->sentence(),
                    default => fake()->paragraphs(fake()->numberBetween(1, 3), true),
                },
                'priority' => ['low', 'medium', 'high'][($index - 1) % 3],
                'status' => $status,
                'due_at' => $dueDates[($index - 1) % count($dueDates)],
                'completed_at' => $status === 'completed' ? now()->subDays($index % 7) : null,
            ]);
        }
    }
}
