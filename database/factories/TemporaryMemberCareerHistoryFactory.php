<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TemporaryMemberCareerHistoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'temporary_member_career_id' => $this->faker->numberBetween(1,100),
            'find_work' => $this->faker->date(),
            'retirement' => $this->faker->date(),
            'office_name' => $this->faker->company(),
            'status' => $this->faker->numberBetween(1,2),
            'free_entry' => $this->faker->realtext(),
        ];
    }
}
