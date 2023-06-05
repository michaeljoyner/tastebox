<?php

namespace Tests\Feature\Membership;

use App\Memberships\MemberProfile;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class FetchMembersTest extends TestCase
{
    use LazilyRefreshDatabase;

    /**
     *@test
     */
    public function can_fetch_paginated_list_of_members_as_admin()
    {
        factory(MemberProfile::class)->times(46)->create();

        $response = $this->asAdmin()->getJson("/admin/api/members?page=2");
        $response->assertSuccessful();

        $this->assertCount(16, $response->json("data"));
        $this->assertSame(2, $response->json("meta.current_page"));
        $this->assertSame(2, $response->json("meta.last_page"));
        $this->assertSame(46, $response->json("meta.total"));
    }

    /**
     *@test
     */
    public function can_get_members_matching_query_by_name_or_email()
    {
        factory(MemberProfile::class)->times(12)->create();
        $first_name = factory(MemberProfile::class)->create(['first_name' => 'Testy']);
        $last_name = factory(MemberProfile::class)->create(['last_name' => 'MacTesterson']);
        $email = factory(MemberProfile::class)->create(['email' => 'someone@test.test']);

        $response = $this->asAdmin()->getJson("/admin/api/members?q=test");
        $response->assertSuccessful();

        $this->assertCount(3, $response->json("data"));

        $fetched_ids = collect($response->json("data"))->map(fn ($user_data) => $user_data['id']);

        $this->assertTrue($fetched_ids->contains($first_name->user->id));
        $this->assertTrue($fetched_ids->contains($last_name->user->id));
        $this->assertTrue($fetched_ids->contains($email->user->id));
    }
}
