<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CalculationDetail>
 */
class CalculationDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'calculation_id' => 1,
            'disease_id' => fake()->randomElement([1, 2, 3, 4]),
            'value' => 0.9
        ];
    }
}
