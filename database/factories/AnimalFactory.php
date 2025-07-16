<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Animal>
 */
class AnimalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'caravana' => $this->faker->unique()->numerify('####'),
            'caravana_oficial' => $this->faker->optional()->numerify('OF-####'),
            'rodeo_id' => \App\Models\Rodeo::factory(),
        ];
    }
    /**
     * Create animals for a specific rodeo
     */
    public function forRodeo($rodeoId): static
    {
        return $this->state(fn (array $attributes) => [
            'rodeo_id' => $rodeoId,
        ]);
    }
    /**
     * Create animals with sequential caravanas for better testing
     */
    public function sequential($rodeoId): static
    {
        static $counter = 1;
        
        return $this->state(fn (array $attributes) => [
            'caravana' => str_pad($counter++, 4, '0', STR_PAD_LEFT),
            'rodeo_id' => $rodeoId,
        ]);
    }
}
