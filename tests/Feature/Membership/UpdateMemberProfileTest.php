<?php


namespace Tests\Feature\Membership;


use App\Memberships\MemberProfile;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
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
        $response->assertRedirect('me/home');

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

    /**
     *@test
     */
    public function the_first_name_is_required_without_the_last_name()
    {
        $this->assertFieldIsInvalid([
            'first_name' => '',
            'last_name' => '',
        ]);
    }

    /**
     *@test
     */
    public function last_name_is_required_without_the_first_name()
    {
        $this->assertFieldIsInvalid([
            'last_name' => '',
            'first_name' => '',
        ]);
    }

    /**
     *@test
     */
    public function the_email_must_be_as_a_valid_email()
    {
        $this->assertFieldIsInvalid(['email' => 'not-a-valid-email']);
    }




    private function assertFieldIsInvalid($field)
    {
        $profile = factory(MemberProfile::class)->create();

        $valid = [
            'first_name' => 'test first name',
            'last_name' => 'test last name',
            'email' => 'test@test.test',
            'phone' => 'test phone',
            'address_line_one' => 'test line one',
            'address_line_two' => 'test line two',
            'address_city' => 'test city',
        ];

        $response = $this
            ->actingAs($profile->user)
            ->from('/me/edit-profile')
            ->post("/me/profile", array_merge($valid, $field));
        $response->assertRedirect('/me/edit-profile');
        $response->assertSessionHasErrors(array_key_first($field));
    }
}
