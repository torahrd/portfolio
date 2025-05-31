<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class FolderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->lexify(str_repeat('?', 5)),
        ];
    }
}
