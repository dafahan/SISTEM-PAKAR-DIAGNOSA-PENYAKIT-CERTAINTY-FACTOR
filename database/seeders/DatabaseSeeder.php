<?php

namespace Database\Seeders;

use App\Models\Calculation;
use App\Models\CalculationDetail;
use App\Models\Questionnaire;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(SymptomSeeder::class);
        $this->call(RuleSeeder::class);

        User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        Calculation::factory(1)->create();
        CalculationDetail::factory(4)->create();
        Questionnaire::factory(10)->create();
    }
}
