<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use function Illuminate\Support\fake;
use Illunate\Support\Carbon;

class My_listSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('my_lists')->insert([
            'user_id' => 1,
            'post_id' => 1,
            'title' => fake()->realText(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
