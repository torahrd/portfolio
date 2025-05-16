<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use function Illuminate\Support\fake;
use Illunate\Support\Carbon;

class ShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('shops')->insert([
            'shop' => fake()->company(),
            'address' => fake()->address(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
