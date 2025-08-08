<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        DB::table('users')->insert([
            'name' => $faker->name(),
            'email' => $faker->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'age' => $faker->randomNumber(2),
            'sex' => $faker->numberBetween(0, 2),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
