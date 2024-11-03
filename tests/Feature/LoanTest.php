<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Loan;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class LoanTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private User $lender;
    private User $borrower;
    private User $otherUser;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test users
        $this->lender = User::factory()->create();
        $this->borrower = User::factory()->create();
        $this->otherUser = User::factory()->create();
    }

    /**
     * Test authenticated lender can create loan
     */
    public function test_authenticated_lender_can_create_loan(): void
    {
        Sanctum::actingAs($this->lender);

        $loanData = [
            'amount' => 50000,
            'interest_rate' => 15,
            'duration_years' => 3,
            'borrower_id' => $this->borrower->id
        ];

        $response = $this->postJson('/api/loans', $loanData);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'data' => [
                        'id',
                        'amount',
                        'interest_rate',
                        'duration_years',
                        'lender_id',
                        'borrower_id',
                        'created_at',
                        'updated_at'
                    ]
                ])
                ->assertJson([
                    'data' => [
                        'lender_id' => $this->lender->id,
                        'borrower_id' => $this->borrower->id
                    ]
                ]);
    }

    /**
     * Test unauthenticated user can list loans
     */
    public function test_unauthenticated_user_can_list_loans(): void
    {
        // Create some loans
        Loan::factory()->count(3)->create([
            'lender_id' => $this->lender->id,
            'borrower_id' => $this->borrower->id
        ]);

        $response = $this->getJson('/api/loans');

        $response->assertStatus(200)
                ->assertJsonCount(3, 'data');
    }

    /**
     * Test only original lender can update their loan
     */
    public function test_only_original_lender_can_update_loan(): void
    {
        $loan = Loan::factory()->create([
            'lender_id' => $this->lender->id,
            'borrower_id' => $this->borrower->id
        ]);

        // Try updating with different user
        Sanctum::actingAs($this->otherUser);
        
        $response = $this->putJson("/api/loans/{$loan->id}", [
            'amount' => 60000,
            'interest_rate' => 16,
            'duration_years' => 4,
            'borrower_id' => $this->borrower->id,
            'lender_id' => $this->lender->id
        ]);

        $response->assertStatus(403);

        // Update with original lender
        Sanctum::actingAs($this->lender);
        
        $response = $this->putJson("/api/loans/{$loan->id}", [
            'amount' => 60000,
            'interest_rate' => 16,
            'duration_years' => 4,
            'borrower_id' => $this->borrower->id,
            'lender_id' => $this->lender->id
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'data' => [
                        'amount' => '60000.00',
                        'interest_rate' => '16.00',
                        'duration_years' => 4,
                        'lender_id' => $this->lender->id
                    ]
                ]);
    }

    /**
     * Test only original lender can delete their loan
     */
    public function test_only_original_lender_can_delete_loan(): void
    {
        $loan = Loan::factory()->create([
            'lender_id' => $this->lender->id,
            'borrower_id' => $this->borrower->id
        ]);

        // Try deleting with different user
        Sanctum::actingAs($this->otherUser);
        
        $response = $this->deleteJson("/api/loans/{$loan->id}");
        $response->assertStatus(403);

        // Delete with original lender
        Sanctum::actingAs($this->lender);
        
        $response = $this->deleteJson("/api/loans/{$loan->id}");
        $response->assertStatus(204);
        
        $this->assertSoftDeleted($loan);
    }

    /**
     * Test loan creation requires valid borrower
     */
    public function test_loan_creation_requires_valid_borrower(): void
    {
        Sanctum::actingAs($this->lender);

        $response = $this->postJson('/api/loans', [
            'amount' => 50000,
            'interest_rate' => 15,
            'duration_years' => 3,
            'borrower_id' => 99999 // Invalid ID
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['borrower_id']);
    }

    /**
     * Test unauthenticated user cannot create loan
     */
    public function test_unauthenticated_user_cannot_create_loan(): void
    {
        $response = $this->postJson('/api/loans', [
            'amount' => 50000,
            'interest_rate' => 15,
            'duration_years' => 3,
            'borrower_id' => $this->borrower->id
        ]);

        $response->assertStatus(401);
    }
}