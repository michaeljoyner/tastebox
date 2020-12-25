<?php

namespace Tests\Unit\MailingList;

use App\MailingList\MailingListMember;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MailingListTest extends TestCase {

    use RefreshDatabase;

    /**
     *@test
     */
    public function can_add_a_contact_to_the_list()
    {
        $contact = MailingListMember::subscribe('test@test.test', 'test name');

        $this->assertCount(1, MailingListMember::all());

        $this->assertSame('test@test.test', $contact->email);
        $this->assertSame('test name', $contact->name);
    }

    /**
     *@test
     */
    public function subscribing_same_email_address_just_updates_name()
    {
        factory(MailingListMember::class)->create([
            'email' => 'exists@test.test',
            'name' => 'old name'
        ]);

        $contact = MailingListMember::subscribe('exists@test.test', 'new name');

        $this->assertCount(1, MailingListMember::all());

        $this->assertSame('exists@test.test', $contact->email);
        $this->assertSame('new name', $contact->name);
    }
}
