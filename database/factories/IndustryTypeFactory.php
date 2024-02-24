<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class IndustryTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->jobTitle(),
            'note' => $this->faker->realText(200),
        ];
    }
}
