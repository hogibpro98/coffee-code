<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TemporaryMemberEducationHistoryFactory extends Factory
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
            'admission' => $this->faker->date(),
            'graduation' => $this->faker->date(),
            'school_name' => $this->faker->lastName().'XXX大学',
        ];
    }
}
