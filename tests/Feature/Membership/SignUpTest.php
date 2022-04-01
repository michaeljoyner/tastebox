<?php

namespace Tests\Feature\Membership;

use App\Jobs\RewardSignUp;
use App\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class SignUpTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function a_guest_can_sign_up_with_email_and_password()
    {
        $this->withoutExceptionHandling();
        Notification::fake();
        Bus::fake();

        $response = $this->asGuest()->post('/register', [
            'name'                  => 'test name',
            'email'                 => 'test@test.test',
            'password'              => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect('/me/home');

        $this->assertDatabaseHas('users', [
            'name'     => 'test name',
            'email'    => 'test@test.test',
            'is_admin' => false,
        ]);

        $user = User::where('email', 'test@test.test')->first();
        $this->assertTrue(auth()->check());
        $this->assertTrue(auth()->user()->is($user));
        $this->assertTrue(Hash::check('password', $user->password));

        $this->assertNotNull($user->profile);
        $this->assertSame('test', $user->profile->first_name);
        $this->assertSame('name', $user->profile->last_name);
        $this->assertSame('test@test.test', $user->profile->email);

        Notification::assertSentTo($user, VerifyEmail::class);

        Bus::assertDispatched(function(RewardSignUp $job) use ($user) {
            $this->assertTrue($job->member->is($user));
            return true;
        });


    }

    /**
     * @test
     */
    public function the_name_is_required()
    {
        $this->assertFieldIsInvalid(['name' => null]);
    }

    /**
     * @test
     */
    public function the_email_is_required_as_valid_email()
    {
        $this->assertFieldIsInvalid(['email' => null]);
        $this->assertFieldIsInvalid(['email' => 'not-a-real-email']);
    }

    /**
     * @test
     */
    public function the_password_is_required()
    {
        $this->assertFieldIsInvalid(['password' => '']);
    }

    /**
     * @test
     */
    public function the_password_must_be_at_least_8_characters()
    {
        $this->assertFieldIsInvalid(['password' => 'short']);
    }

    /**
     * @test
     */
    public function the_password_must_be_confirmed()
    {
        $this->assertFieldIsInvalid([
            'password'              => 'good_password',
            'password_confirmation' => 'bad_password',
        ]);
    }


    public function assertFieldIsInvalid($field)
    {
        Notification::fake();

        $valid = [
            'name'                  => 'test name',
            'email'                 => 'test@test.test',
            'password'              => 'password',
            'password_confirmation' => 'password',
        ];
        $response = $this
            ->asGuest()
            ->from('/register')
            ->post('/register', array_merge($valid, $field));

        $response->assertRedirect('/register');
        $response->assertSessionHasErrors(array_key_first($field));
    }
}
