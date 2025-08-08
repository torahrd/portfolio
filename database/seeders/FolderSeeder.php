<?php

namespace Database\Seeders;

use App\Models\Folder;
use Illuminate\Database\Seeder;

class FolderSeeder extends Seeder
{
    public function run(): void
    {
        Folder::factory()->count(10)->create([
            'user_id' => 1,
        ]);
    }
}
