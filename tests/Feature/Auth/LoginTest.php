<?php


namespace Tests\Feature\Auth;


use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function an_existing_user_can_log_in()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->state('admin')->create([
            'email'    => 'test@test.test',
            'password' => Hash::make('password'),
        ]);

        $this->assertTrue(Auth::guest());

        $response = $this->post("/admin/login", [
            'email'    => 'test@test.test',
            'password' => 'password',
        ]);
        $response->assertRedirect("/admin");

        $this->assertTrue(Auth::check());
        $this->assertEquals(Auth::user()->id, $user->id);
    }
}
