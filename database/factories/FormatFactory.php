<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class FormatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'is_private' => $this->faker->boolean(),
            'title' => $this->faker->jobTitle(),
            'content' => $this->faker->paragraphs(),
            'file_name' => $this->faker->domainName(),
            'file_path' => $this->faker->url(),
            'mime_type' => $this->faker->mimeType()
        ];
    }
}
