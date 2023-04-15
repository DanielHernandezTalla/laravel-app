<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'amount' => $this->faker->randomFloat($maxDecimales = 2, $min = 15, $max = 500),
            'payed_at' => $this->faker->dateTimeBetween('-1 year', now())
        ];
    }
}
