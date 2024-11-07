<?php

namespace Database\Factories;

use App\Models\Loan;
use Illuminate\Database\Eloquent\Factories\Factory;

class LoanFactory extends Factory
{
    protected $model = Loan::class;

    public function definition(): array
    {
        return [
            'amount' => $this->faker->randomFloat(2, 1000, 999999),
            'interest_rate' => $this->faker->randomFloat(2, 1, 30),
            'duration_years' => $this->faker->numberBetween(1, 30),
        ];
    }
}