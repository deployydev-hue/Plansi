<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'category_id' => null,

            'title' => fake()->sentence(4),
            'description' => fake()->optional()->paragraph(),

            'priority' => fake()->randomElement([
                'low',
                'medium',
                'high',
            ]),

            'status' => fake()->randomElement([
                'pending',
                'completed',
            ]),

            'due_at' => fake()->optional()->dateTimeBetween('now', '+14 days'),

            'completed_at' => function (array $attributes) {
                return $attributes['status'] === 'completed'
                    ? now()
                    : null;
            },
        ];
    }
}
