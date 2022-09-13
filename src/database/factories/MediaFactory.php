<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Media>
 */
class MediaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            "url_folder" => $this->faker->text(),
            "url_thumbnail" => $this->faker->text(),
            "url_media" => $this->faker->text(),
            "filename" => $this->faker->text(),
            "owner_id" => User::all()->random()->id,
            "extension" => Str::random(3),
        ];
    }
}
