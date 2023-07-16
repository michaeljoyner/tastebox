<?php

namespace Tests\Feature\Contact;

use App\Mail\ContactDietitianMessage;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactDietitianTest extends TestCase
{
    use LazilyRefreshDatabase;

    /**
     * @test
     */
    public function send_contact_message_for_dietitian()
    {
        Mail::fake();

        config(['mail.addresses.dietitian' => ['name' => 'Dietitian', 'email' => 'diet@test.test']]);

        $response = $this->asGuest()->postJson("/contact-dietitian", [
            'name'     => 'test name',
            'phone'    => 'test phone',
            'email'    => 'test@test.test',
            'message'  => 'test message',
            'location' => 'test location',
        ]);
        $response->assertSuccessful();

        Mail::assertQueued(
            fn(ContactDietitianMessage $mail) => (
                $mail->hasTo('diet@test.test') &&
                $mail->hasReplyTo('test@test.test') &&
                $mail->sender === 'test name' &&
                $mail->phone === 'test phone' &&
                $mail->email === 'test@test.test' &&
                $mail->location === 'test location' &&
                $mail->message === 'test message'
            )
        );
    }

    /**
     * @test
     */
    public function the_name_is_required()
    {
        $this->assertFieldIsInvalid(['name' => '']);
    }

    /**
     * @test
     */
    public function the_email_must_be_valid_if_present()
    {
        $this->assertFieldIsInvalid(['email' => 'not-an-email']);
    }


    /**
     * @test
     */
    public function the_email_is_required_when_no_phone()
    {
        $this->assertFieldIsInvalid([
            'email' => null,
            'phone' => null,
        ]);
    }

    /**
     * @test
     */
    public function the_phone_is_required_if_no_email()
    {
        $this->assertFieldIsInvalid([
            'phone' => null,
            'email' => null,
        ]);
    }

    /**
     *@test
     */
    public function the_message_is_required()
    {
        $this->assertFieldIsInvalid(['message' => '']);
    }

    private function assertFieldIsInvalid(array $field)
    {
        config(['mail.addresses.dietitian' => ['name' => 'Dietitian', 'email' => 'diet@test.test']]);

        $valid = [
            'name'     => 'test name',
            'phone'    => 'test phone',
            'email'    => 'test@test.test',
            'message'  => 'test message',
            'location' => 'test location',
        ];
        $response = $this
            ->asGuest()
            ->postJson("/contact-dietitian", array_merge($valid, $field));

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(array_key_first($field));
    }
}
