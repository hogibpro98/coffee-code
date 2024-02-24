<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Validation\Rules\Unique;

class TemporaryMemberCareerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'temporary_member_id' => $this->faker->unique(true)->numberBetween(1,110),
            'birthdate' => $this->faker->date(),
            'gender' => $this->faker->numberBetween(1,2),
            'office_name' => $this->faker->company(),
            'postal_code' => $this->faker->postcode(),
            'prefecture' => $this->faker->numberBetween(1,47),
            'address1' => 'XXX市YYY町',
            'address2' => '0-0-0-101',
            'tel1' => '000',
            'tel2' => '0000',
            'tel3' => '0000',
            'certified_accountant_number' => $this->faker->numberBetween(10000,99999),
            'tax_accountant_number' => $this->faker->numberBetween(10000,99999),
            'advisory_experience_years' => $this->faker->numberBetween(1,5),
            'other_specialized_field' => $this->faker->realText(200),
            'experience' => $this->faker->realText(600),
        ];
    }
}
