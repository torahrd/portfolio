<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use function Illumate\Support\fake;
use Illunate\Support\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => fake()->name,
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'age' => fake()->randomNumber(2),
            'sex' => fake()->numberBetween(0, 2),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
