<?php


namespace Tests\Feature\Membership;


use App\Memberships\MemberProfile;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateMemberProfileTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function update_the_member_profile_info()
    {
        $this->withoutExceptionHandling();

        $profile = factory(MemberProfile::class)->create();

        $response = $this->actingAs($profile->user)->post("/me/profile", [
            'first_name' => 'test first name',
            'last_name' => 'test last name',
            'email' => 'test@test.test',
            'phone' => 'test phone',
            'address_line_one' => 'test line one',
            'address_line_two' => 'test line two',
            'address_city' => 'test city',
        ]);
        $response->assertSuccessful();

        $this->assertDatabaseHas('member_profiles', [
            'first_name' => 'test first name',
            'last_name' => 'test last name',
            'email' => 'test@test.test',
            'phone' => 'test phone',
            'address_line_one' => 'test line one',
            'address_line_two' => 'test line two',
            'address_city' => 'test city',
        ]);

    }
}
