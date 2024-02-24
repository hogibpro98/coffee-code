<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MemberEducationHistoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'member_id' => $this->faker->unique(true)->numberBetween(1,100),
            'admission' => $this->faker->date(),
            'graduation' => $this->faker->date(),
            'school_name' => $this->faker->lastName().'XXX大学',
        ];
    }
}
