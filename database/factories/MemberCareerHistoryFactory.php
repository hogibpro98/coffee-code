<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MemberCareerHistoryFactory extends Factory
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
            'find_work' => $this->faker->date(),
            'retirement' => $this->faker->date(),
            'status' => $this->faker->numberBetween(1,2),
            'office_name' => $this->faker->company(),
        ];
    }
}
