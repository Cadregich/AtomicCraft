<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Mod;
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition(): array
    {
        $randomModId = Mod::inRandomOrder()->value('id');
        return [
            'name' => $this->faker->word,
            'mod_id' => $randomModId,
            'img' => $this->faker->imageUrl(640, 480),
            'price' => $this->faker->numberBetween(10, 9999),
        ];
    }
}
