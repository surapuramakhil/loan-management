<?php

namespace Database\Seeders;

use App\Models\Loan;
use Illuminate\Database\Seeder;

class LoanSeeder extends Seeder
{
    public function run()
    {
        Loan::factory()->count(10)->create();
    }
}