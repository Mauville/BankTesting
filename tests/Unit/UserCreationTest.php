<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use phpseclib3\File\ASN1\Maps\UserNotice;
use Tests\TestCase;
use App\Models\User;

class UserCreationTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */

    use RefreshDatabase;
    public function test_example()
    {
        $user = User::factory()->create();
        $userSaved = User::factory()->make();
        $this->assertIsNotInt($userSaved->id);
        $this->assertNotNull($user);
    }
}
