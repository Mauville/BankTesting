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
     * A basic unit test example.
     *
     * @return void
     */
    public function test_firstparty_transfer()
    {
        $user = User::factory()->create();
        $source_acc = Account::create([
            'name' => 'Source Test Account',
            'current_balance' => 100,
            'user_id' => $user->id
        ]);
        $dest_acc = Account::create([
            'name' => 'Destination Test Account',
            'current_balance' => 200,
            'user_id' => $user->id
        ]);
        Account::transfer($source_acc, $dest_acc, 20);
        $this->assertEquals(220, $dest_acc->current_balance);
    }
}
