<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'shop_id' => \App\Models\Shop::factory(),
            'visit_time' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'budget' => $this->faker->numberBetween(1000, 5000),
            'repeat_menu' => $this->faker->sentence(),
            'interest_menu' => $this->faker->sentence(),
            'reference_link' => $this->faker->optional()->url(),
            'memo' => $this->faker->text(200), // 短いテキストに変更
            'image_url' => $this->faker->optional()->imageUrl(640, 480, 'food'),
            'visit_status' => $this->faker->randomElement([0, 1]), // 0: want_to_visit, 1: visited
            'private_status' => $this->faker->boolean(), // false: public, true: private
        ];
    }
}
