<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class User_time_keep_typeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            "name" => 'TSW',
            "slug" => 'tsw',
        ];
    }
}
