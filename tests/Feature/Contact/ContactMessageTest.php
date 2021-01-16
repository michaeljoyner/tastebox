<?php

namespace Tests\Feature\Contact;

use App\Mail\ContactMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactMessageTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function sends_a_contact_message_email()
    {
        Mail::fake();
        $this->withoutExceptionHandling();

        $response = $this->asGuest()->postJson("/contact", [
            'name' => 'test name',
            'email' => 'test@test.test',
            'phone' => 'test phone',
            'message' => 'test message',
        ]);

        $response->assertSuccessful();

        Mail::assertQueued(ContactMessage::class, function($mail) {
            $this->assertSame($mail->phone, 'test phone');
            $this->assertSame($mail->sender_name, 'test name');
            $this->assertSame($mail->sender_email, 'test@test.test');
            $this->assertSame($mail->message, 'test message');
//            $this->assertTrue($mail->hasTo(config('mail.addresses.admins')[0]['email']));

            return true;
        });
    }

    /**
     *@test
     */
    public function the_name_is_required()
    {
        $this->assertFieldIsInvalid(['name' => '']);
    }

    /**
     *@test
     */
    public function the_email_is_required_without_the_phone()
    {
        $this->assertFieldIsInvalid([
            'email' => '',
            'phone' => null,
            ]);
    }

    /**
     *@test
     */
    public function the_phone_is_required_without_the_email()
    {
        $this->assertFieldIsInvalid([
            'phone' => null,
            'email' => '',
        ]);
    }

    /**
     *@test
     */
    public function the_email_must_be_a_proper_email()
    {
        $this->assertFieldIsInvalid(['email' => 'not-a-valid-email']);
    }

    /**
     *@test
     */
    public function the_message_is_required()
    {
        $this->assertFieldIsInvalid(['message' => null]);
    }

    private function assertFieldIsInvalid($field)
    {
        Mail::fake();

        $valid = [
            'name' => 'test name',
            'email' => 'test@test.test',
            'phone' => 'test phone',
            'message' => 'test message',
        ];

        $response = $this->asGuest()->postJson("/contact", array_merge($valid, $field));

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(array_key_first($field));
    }
}
