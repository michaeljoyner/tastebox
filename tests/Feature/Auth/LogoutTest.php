<?php


namespace Tests\Feature\Auth;


use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function a_user_can_logout()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->post("/logout");
        $response->assertRedirect("/");

        $this->assertTrue(Auth::guest());
    }
}
