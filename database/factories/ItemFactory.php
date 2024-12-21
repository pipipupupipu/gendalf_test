<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'preview_text' => $this->faker->sentence(),
            'price' => $this->faker->randomFloat(2, 10, 100),
            'category_id' => Category::query()->inRandomOrder()->first()->id
        ];
    }
}
