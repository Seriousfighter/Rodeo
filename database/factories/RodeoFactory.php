<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rodeo>
 */
class RodeoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company() . ' Rodeo',
            'location' => $this->faker->city() . ', ' . $this->faker->state(),
            'description' => $this->faker->paragraph(),
            'renspa' => $this->faker->numerify('RENSPA-####'),
            'client_id' => \App\Models\Client::factory(),
        ];
    }
}
