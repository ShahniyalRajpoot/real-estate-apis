<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Image>
 */
class ImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $imagableType =[
            \app\Models\User::class,
            \app\Models\listing::class,
        ];
        return [
            'path' => $this->faker->imageUrl(640,480),
            'imagable_type' => $this->faker->randomElement($imagableType),
            'imagable_id' => $this->faker->numberBetween(1, 20),
        ];
    }
}
