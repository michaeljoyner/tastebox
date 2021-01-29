<?php


namespace Tests\Unit\Purchases;


use App\PhoneNumber;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PhoneNumbersTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function can_correctly_format_phone_numbers()
    {
        $cases = [
            [
                'case'     => 'regular',
                'value'    => '0721234567',
                'expected' => '27721234567',
            ],
            [
                'case'     => 'has spaces',
                'value'    => ' 072 123 4567',
                'expected' => '27721234567',
            ],
            [
                'case'     => 'has dashes',
                'value'    => '072-123-4567',
                'expected' => '27721234567',
            ],
            [
                'case'     => 'has intl code',
                'value'    => '+27 721234567',
                'expected' => '27721234567',
            ],
        ];

        collect($cases)->each(function($case) {
            $this->assertSame($case['expected'], PhoneNumber::from($case['value']), $case['case']);
        });
    }
}
