<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ZunitTest1Factory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            "text1" => $this->faker->text(),
            "text2" => $this->faker->text(),
            "dropdown1" => rand(1, 100),
            "radio1" => rand(1, 100),
            "boolean1" => rand(0, 1),
        ];
    }
}
