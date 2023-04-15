<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(2),
            'description' => $this->faker->paragraph(1),
            'price' => $this->faker->randomFloat($maxDecimales = 2, $min = 3, $max = 100),
            'stock' => $this->faker->numberBetween(1,10),
            'status' => $this->faker->randomElement(['available', 'unavailable'])
        ];
    }
}
