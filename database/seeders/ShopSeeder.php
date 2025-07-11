<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use function Illuminate\Support\fake;
use Illuminate\Support\Carbon;
use Faker\Factory as Faker;

class ShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $shops = [];
        for ($i = 0; $i < 10; $i++) {
            $shops[] = [
                'name' => $faker->company(),
                'address' => $faker->address(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('shops')->insert($shops);
    }
}
