<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SaleDetail>
 */
class SaleDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sale_id' => 1,
            'item_id' => $this->faker->numberBetween(1,3),
            'quantity' => $this->faker->randomFloat(1, 1, 50),
            'subtotal' => $this->faker->randomFloat(1, 1, 20000),
            'tax' => $this->faker->randomFloat(1, 1, 5000),
            'total' => $this->faker->randomFloat(1, 1, 20000),
        ];
    }
}
