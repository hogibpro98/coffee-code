<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class InquiryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'member_id' => $this->faker->numberBetween(1,50),
            'user_id' => $this->faker->boolean() ? $this->faker->numberBetween(1,5) : null,
            'status' => $this->faker->numberBetween(1,3),
            'title' => '意見の件名'.$this->faker->unique(true)->numberBetween(1,99),
            'content' => '意見の本文がここに入ります。 \n 改行も出来ます。',
        ];
    }
}
