<?php

namespace Database\Factories;

use App\Http\Controllers\Workflow\LibStatuses;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $statuses = array_keys(LibStatuses::getFor('post'));
        // dd($statuses);
        return [
            "owner_id" => User::all()->random()->id,
            "name" => $this->faker->text(50),
            "content" => $this->faker->text(1000),
            "status" => $statuses[random_int(0, sizeof($statuses) - 1)],
        ];
    }
}
