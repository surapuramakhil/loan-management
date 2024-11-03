<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test user authentication with valid credentials
     */
    public function test_users_can_authenticate(): void
    {
        $password = 'password123';
        
        $user = User::factory()->create([
            'password' => bcrypt($password)
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => $password,
            'device_name' => 'test_device'
        ]);

        $response->assertOk()
            ->assertJsonStructure([
                'token',
                'user' => [
                    'id',
                    'name',
                    'email'
                ]
            ]);

        // For API authentication, we should verify the token was created
        $this->assertDatabaseHas('personal_access_tokens', [
            'tokenable_type' => User::class,
            'tokenable_id' => $user->id,
            'name' => 'test_device'
        ]);
    }

    /**
     * Test protected route access with token
     */
    public function test_user_can_access_protected_routes_with_token(): void
    {
        $user = User::factory()->create();
        
        Sanctum::actingAs($user, ['*']);

        $response = $this->getJson('/api/user');

        $response->assertOk()
            ->assertJson([
                'id' => $user->id,
                'email' => $user->email
            ]);
    }

    /**
     * Test authentication rejection with invalid credentials
     */
    public function test_users_cannot_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('correct_password')
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'wrong_password',
            'device_name' => 'test_device'
        ]);

        $response->assertStatus(422);
        
        // Verify no token was created
        $this->assertDatabaseCount('personal_access_tokens', 0);
    }

    /**
     * Test user logout
     */
    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();
        
        // Create a token for the user
        $token = $user->createToken('test_device')->plainTextToken;
        
        // Make the request with the token
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/logout');

        $response->assertOk()
            ->assertJson(['message' => 'Logged out successfully']);

        // Verify the token was deleted
        $this->assertDatabaseCount('personal_access_tokens', 0);
    }

//    /**
//      * Test user cannot access protected routes after logout
//      */
//     public function test_user_cannot_access_protected_routes_after_logout(): void
//     {
//         $user = User::factory()->create();
        
//         // Create a token and authenticate the user
//         $token = $user->createToken('test_device')->plainTextToken;
        
//         // First, verify the token works
//         $this->withHeader('Authorization', 'Bearer ' . $token)
//             ->getJson('/api/user')
//             ->assertOk();

//         // Logout using the token
//         $response = $this->withHeader('Authorization', 'Bearer ' . $token)
//             ->postJson('/api/logout');
            
//         $response->assertOk()->assertJson(['message' => 'Logged out successfully']);

//         // Try to access protected route with the revoked token
//         $response = $this->withHeader('Authorization', 'Bearer ' . $token)
//             ->getJson('/api/user');

//         $response->assertStatus(401);
//     }

    /**
     * Test multiple tokens are properly handled during logout
     */
    public function test_only_current_token_is_revoked_on_logout(): void
    {
        $user = User::factory()->create();
        
        // Create multiple tokens
        $token1 = $user->createToken('device_1')->plainTextToken;
        $token2 = $user->createToken('device_2')->plainTextToken;

        // Logout with first token
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token1,
        ])->postJson('/api/logout');

        $response->assertOk();

        // Verify only one token was deleted
        $this->assertDatabaseCount('personal_access_tokens', 1);

        // Verify second token still works
        $this->withHeaders([
            'Authorization' => 'Bearer ' . $token2,
        ])->getJson('/api/user')
            ->assertOk();
    }
}