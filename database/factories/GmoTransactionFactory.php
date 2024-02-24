<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class GmoTransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'billing_id' => $this->faker->numberBetween(1,110),
            'status' => $this->faker->numberBetween(1,500),
            'message' => $this->faker->realText(20),
        ];
    }
}
