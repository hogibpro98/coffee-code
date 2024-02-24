<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class LeaveReasonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'member_id' => $this->faker->unique(true)->numberBetween(1,110),
            'reason' => $this->faker->numberBetween(1,5),
            'detail' => $this->faker->realText(400),
        ];
    }
}
