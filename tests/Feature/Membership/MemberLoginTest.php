<?php


namespace Tests\Feature\Membership;


use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MemberLoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function a_member_can_login()
    {

        $member = factory(User::class)->state('member')->create(['password' => Hash::make('password')]);

        $response = $this->asGuest()->post('/login', [
            'email' => $member->email,
            'password' => 'password',
        ]);

        $response->assertRedirect('/me/home');
        $this->assertTrue(auth()->user()->is($member));

    }
}
