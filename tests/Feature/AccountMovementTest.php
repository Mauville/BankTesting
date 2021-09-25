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

        Movement::Register($account, 100);
        // Reload thing from db
        $account->fresh();
        $this->assertEquals(200, $account->current_balance);
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_same_transfer()
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

    public function test_same_transfer_movement()
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
        $movements = Account::transfer($source_acc, $dest_acc, 20);
        $this->assertEquals(Movement::TRANSFER, $movements[0]->type);
    }

    public function test_third_transfer()
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $source_acc = Account::create([
            'name' => 'Source Test Account',
            'current_balance' => 100,
            'user_id' => $user->id
        ]);
        $dest_acc = Account::create([
            'name' => 'Destination Test Account',
            'current_balance' => 200,
            'user_id' => $user2->id
        ]);
        Account::transfer($source_acc, $dest_acc, 20);
        $this->assertEquals(220, $dest_acc->current_balance);
    }

    public function test_third_transfer_movement()
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $source_acc = Account::create([
            'name' => 'Source Test Account',
            'current_balance' => 100,
            'user_id' => $user->id
        ]);
        $dest_acc = Account::create([
            'name' => 'Destination Test Account',
            'current_balance' => 200,
            'user_id' => $user2->id
        ]);
        $movements  = Account::transfer($source_acc, $dest_acc, 20);
        $this->assertEquals(20, $dest_acc->current_balance);
    }
}
