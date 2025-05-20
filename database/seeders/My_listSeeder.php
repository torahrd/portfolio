<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use function Illuminate\Support\fake;
use Illuminate\Support\Carbon;
use Faker\Factory as Faker;

class My_listSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        DB::table('my_lists')->insert([
            'user_id' => 1,
            'post_id' => 1,
            'title' => $faker->realText(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
