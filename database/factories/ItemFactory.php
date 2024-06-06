<?php

namespace Database\Factories;

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
            'description' => $this->faker->word(),
            //'quantity' => $this->faker->randomNumber(6),
            'price' => $this->faker->randomFloat(2,1,100000),
            'tax_id' => $this->faker->numberBetween(1,2),
        ];
    }
}
