<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Loan;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoanTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_loan(): void
    {
        $loanData = [
            'amount' => 50000,
            'interest_rate' => 15,
            'duration_years' => 3
        ];

        $response = $this->postJson('/api/loans', $loanData);

        $response->assertStatus(201)
                ->assertJsonStructure(['data' => [
                    'id',
                    'amount',
                    'interest_rate',
                    'duration_years',
                    'created_at',
                    'updated_at'
                ]]);
    }

    public function test_can_list_loans(): void
    {
        Loan::factory()->count(3)->create();

        $response = $this->getJson('/api/loans');

        $response->assertStatus(200)
                ->assertJsonCount(3, 'data');
    }

    public function test_can_update_loan(): void
    {
        $loan = Loan::factory()->create();

        $response = $this->putJson("/api/loans/{$loan->id}", [
            'amount' => 60000,
            'interest_rate' => 16,
            'duration_years' => 4
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'data' => [
                        'amount' => '60000.00',
                        'interest_rate' => '16.00',
                        'duration_years' => 4
                    ]
                ]);
    }

    public function test_can_delete_loan(): void
    {
        $loan = Loan::factory()->create();

        $response = $this->deleteJson("/api/loans/{$loan->id}");

        $response->assertStatus(204);
        $this->assertSoftDeleted($loan);
    }
}