<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use function Illuminate\Support\fake;
use Illuminate\Support\Carbon;
use Faker\Factory as Faker;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        DB::table('posts')->insert([
            'user_id' => 1,
            'shop_id' => 1,
            'visit_time' => $faker->dateTime($max = 'now'),
            'budget' => $faker->numberBetween(500, 50000),
            'repeat_menu' => $faker->sentence(rand(1, 3)),
            'intarest_menu' => $faker->sentence(rand(1, 3)),
            'reference_link' => $faker->url(),
            'memo' => $faker->realText(500),
            'visit_status' => $faker->boolean(),
            'private_status' => $faker->boolean(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
