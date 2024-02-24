<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TemporaryMemberFieldTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'temporary_member_career_id' => $this->faker->numberBetween(1,110),
            'field_id' => $this->faker->numberBetween(1,40),
            'type' => $this->faker->numberBetween(1,2),
        ];
    }
}
