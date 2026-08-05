<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Maryam',
            'email' => 'maryam@example.com',
        ]);

        $studyCategory = Category::factory()->create([
            'user_id' => $user->id,
            'name' => 'Study',
        ]);

        $workCategory = Category::factory()->create([
            'user_id' => $user->id,
            'name' => 'Work',
        ]);

        Task::factory()->count(5)->create([
            'user_id' => $user->id,
            'category_id' => $studyCategory->id,
        ]);

        Task::factory()->count(5)->create([
            'user_id' => $user->id,
            'category_id' => $workCategory->id,
        ]);
    }
}
