<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Shop>
 */
class ShopFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'google_place_id' => $this->faker->unique()->uuid(),
            'name' => $this->faker->company().'店',
            'address' => $this->faker->address(),
            'formatted_phone_number' => $this->faker->phoneNumber(),
            'website' => $this->faker->url(),
            'reservation_url' => $this->faker->optional()->url(),
            'latitude' => $this->faker->latitude(35.5, 35.8),
            'longitude' => $this->faker->longitude(139.5, 139.8),
            'created_by' => null, // 外部キー制約を避けるためnullに
        ];
    }
}
