<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GuruMapel>
 */
class GuruMapelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //nip', 'nama guru', 'mapel', 'kelas'
            /*
             'name' => $this->faker->name(),
            'description' => $this->faker->text(),
            'image' => $this->faker->imageUrl(640, 480, $this->faker->unique(true)->randomElement([1, 2, 3, 4, 5, 6, 7, 8]), true),
            'status' => $this->faker->randomElement(["active", "inactive"]),
            'ingridients' => $this->faker->text(),
            'restaurant_id' => Restaurant::factory(),
            */
            'nip' => $this->faker->numerify(),
            'nama guru' => $this->faker->name(),
            'mapel' => $this->faker->words(3, true),
            'kelas' => $this->faker->numerify('user-####')

        ];
    }
}
