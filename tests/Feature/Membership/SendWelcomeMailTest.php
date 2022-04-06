<?php

namespace Tests\Feature\Membership;

use App\Mail\WelcomeAboard;
use App\Memberships\MemberProfile;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SendWelcomeMailTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function a_welcome_email_is_sent_when_mail_is_verified()
    {
        Mail::fake();

        $profile = factory(MemberProfile::class)->create();

        event(new Verified($profile->user));

        Mail::assertQueued(function(WelcomeAboard $mail) use ($profile) {
            $this->assertTrue($mail->hasTo($profile->user->email));
            $this->assertTrue($profile->user->is($mail->user));

            return true;
        });
    }
}
