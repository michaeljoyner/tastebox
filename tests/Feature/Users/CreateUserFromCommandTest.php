<?php

namespace Tests\Feature\Users;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CreateUserFromCommandTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function create_a_user_via_artisan()
    {
        $this->withoutExceptionHandling();

        $this->assertCount(0, User::all());

        $exit = Artisan::call('users:add', [
            "--name" => "test user",
            "--email" => "test@test.test",
            "--password" => "password",
        ]);

        $this->assertEquals(0, $exit);

        $this->assertCount(1, User::all());
        $user = User::first();

        $this->assertEquals('test user', $user->name);
        $this->assertEquals('test@test.test', $user->email);
        $this->assertTrue(Hash::check('password', $user->password));
    }
}
