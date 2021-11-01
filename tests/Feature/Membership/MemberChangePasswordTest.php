<?php

namespace Tests\Feature\Membership;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MemberChangePasswordTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function member_can_reset_their_password()
    {
        $this->withoutExceptionHandling();

        //member password is 'password'
        $member = factory(User::class)->state('member')->create();

        $response = $this->actingAs($member)->post("/me/reset-password", [
            'current_password'      => 'password',
            'password'              => 'new_password',
            'password_confirmation' => 'new_password',
        ]);
        $response->assertRedirect('/me/home');

        $this->assertTrue(Hash::check('new_password', $member->fresh()->password));
    }

    /**
     *@test
     */
    public function the_current_password_must_be_correct()
    {
        $member = factory(User::class)->state('member')->create();

        $response = $this
            ->actingAs($member)
            ->from('/me/reset-password')
            ->post("/me/reset-password", [
            'current_password'      => 'bad_password',
            'password'              => 'new_password',
            'password_confirmation' => 'new_password',
        ]);
        $response->assertRedirect('/me/reset-password');
        $response->assertSessionHasErrors('current_password');
    }

    /**
     *@test
     */
    public function the_new_password_must_be_confirmed()
    {
        $member = factory(User::class)->state('member')->create();

        $response = $this
            ->actingAs($member)
            ->from('/me/reset-password')
            ->post("/me/reset-password", [
                'current_password'      => 'password',
                'password'              => 'new_password',
                'password_confirmation' => 'non_matching_password',
            ]);
        $response->assertRedirect('/me/reset-password');
        $response->assertSessionHasErrors('password');
    }

    /**
     *@test
     */
    public function the_password_must_be_at_least_8_characters_long()
    {
        $member = factory(User::class)->state('member')->create();

        $response = $this
            ->actingAs($member)
            ->from('/me/reset-password')
            ->post("/me/reset-password", [
                'current_password'      => 'password',
                'password'              => 'short',
                'password_confirmation' => 'short',
            ]);
        $response->assertRedirect('/me/reset-password');
        $response->assertSessionHasErrors('password');
    }
}
