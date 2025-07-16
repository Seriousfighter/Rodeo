<?php

namespace Database\Factories;

use App\Models\Animal;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Group>
 */
class GroupFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(2, true) . ' Group',
            'description' => $this->faker->optional(0.7)->sentence(),
        ];
    }
    /**
     * Create a group with specific animals
     */
    public function withAnimals($animalIds): static
    {
        return $this->afterCreating(function ($group) use ($animalIds) {
            $group->Animals()->attach($animalIds);
        });
    }

    /**
     * Create a group with random animals from a rodeo
     */
    public function withRandomAnimals($rodeoId, $count = null): static
    {
        return $this->afterCreating(function ($group) use ($rodeoId, $count) {
            $animals = Animal::where('rodeo_id', $rodeoId)->get();
            
            if ($animals->count() > 0) {
                $animalCount = $count ?? $this->faker->numberBetween(2, min(5, $animals->count()));
                $selectedAnimals = $animals->random(min($animalCount, $animals->count()));
                $group->Animals()->attach($selectedAnimals->pluck('id'));
            }
        });
    }
}
