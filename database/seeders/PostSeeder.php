<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use function Illuminate\Support\fake;
use Illuminate\Support\Carbon;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('posts')->insert([
            'user_id' => 1,
            'review' => fake()->numberBetween(0, 5),
            'visit_time' => fake()->dateTime($max = 'now'),
            'budget' => fake()->numberBetween(500, 50000),
            'repeat_menu' => fake()->sentence(rand(1, 3)),
            'intarest_menu' => fake()->sentence(rand(1, 3)),
            'reference_link' => fake()->url(),
            'memo' => fake()->realText(500),
            'visit_status' => fake()->boolean(),
            'private_status' => fake()->boolean(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
