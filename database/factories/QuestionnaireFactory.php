<?php

namespace Database\Factories;

use App\Models\Calculation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Questionnaire>
 */
class QuestionnaireFactory extends Factory
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
            'answer' => fake()->randomElement([0.2, 0.4, 0.6, 0.8, 1]),
            'symptom_id' => fake()->randomElement([1, 2, 3, 4, 5, 6])
        ];
    }
}
