<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\Movement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AccountMovementTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_movement_account()
    {
        $user = User::factory()->create();

        $account = Account::create([
            'name' => 'Mi test account',
            'current_balance' => 100,
            'user_id' => $user->id
        ]);

        Movement::Register($account, 99);
        // Reload thing from db
        $account->fresh();
        $this->assertEquals(200, $account->current_balance);
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
