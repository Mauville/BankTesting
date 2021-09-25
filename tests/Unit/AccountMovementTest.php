<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Account;
use App\Models\User;

class AccountMovementTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Tests that the account can hold a balance.
     *
     * @return void
     */
    public function test_correct_balance()
    {
        $balance = 200;
        $user = User::factory()->create();
        $account = Account::create([
            'name' => 'Source Test Account',
            'current_balance' => $balance,
            'user_id' => $user->id
        ]);
        $this->assertEquals($balance, $account->current_balance);
    }
}
