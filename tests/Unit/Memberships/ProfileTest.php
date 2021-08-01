<?php

namespace Tests\Unit\Memberships;

use App\Memberships\MemberProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function a_profile_knows_if_it_is_complete()
    {
        $complete = factory(MemberProfile::class)->state('complete')->create();
        $incomplete = factory(MemberProfile::class)->state('incomplete')->create();

        $this->assertTrue($complete->isComplete());
        $this->assertFalse($incomplete->isComplete());

        $complete->update([
            'email' => '',
            'phone' => '',
        ]);

        $this->assertFalse($complete->isComplete());
    }

    /**
     *@test
     */
    public function a_profile_without_address_line_one_is_incomplete()
    {
        $complete = factory(MemberProfile::class)->state('complete')->create();
        $complete->update(['address_line_one' => '']);

        $this->assertFalse($complete->isComplete());
    }
}
