<?php

namespace Tests\Feature\Membership;

use App\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class SignUpTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function a_guest_can_sign_up_with_email_and_password()
    {
        $this->withoutExceptionHandling();
        Notification::fake();

        $response = $this->asGuest()->post('/register', [
            'name' => 'test name',
            'email' => 'test@test.test',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect('/me/home');

        $this->assertDatabaseHas('users', [
            'name' => 'test name',
            'email' => 'test@test.test',
            'is_admin' => false,
        ]);

        $user = User::where('email', 'test@test.test')->first();
        $this->assertTrue(auth()->check());
        $this->assertTrue(auth()->user()->is($user));
        $this->assertTrue(Hash::check('password', $user->password));

        Notification::assertSentTo($user, VerifyEmail::class);


    }
}
