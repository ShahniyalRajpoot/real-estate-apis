<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Listing>
 */
class ListingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

            return [
                'title' => $this->faker->sentence(2),
                'description' => $this->faker->paragraph(3),
                'user_id' => $this->faker->numberBetween(1, 10),
                'is_featured' => 0,
            ];

    }
}
