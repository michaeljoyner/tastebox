<?php

namespace Tests;

use App\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Storage;
use Spatie\Browsershot\Browsershot;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    public function asAdmin(): self
    {
        $user = factory(User::class)->state('admin')->create();

        $this->actingAs($user);

        return $this;
    }

    public function asGuest(): self
    {
        $this->assertGuest();

        return $this;
    }

    public function fakeMedia()
    {
        Storage::fake('media', config('filesystems.disks.media'));
    }

    public function asJson($array, $column)
    {
        return function ($query) use ($column, $array) {
            $query->select($column);
            foreach ($array as $value) {
                $query->whereJsonContains($column, $value);
            }
        };
    }

    public function fakeBrowsershotPdf()
    {
        $this->partialMock(Browsershot::class)
             ->shouldReceive('savePdf')
             ->andReturnUsing(function($path): void {
                 file_put_contents($path, base64_decode("JVBERi0xLg10cmFpbGVyPDwvUm9vdDw8L1BhZ2VzPDwvS2lkc1s8PC9NZWRpYUJveFswIDAgMyAzXT4+XT4+Pj4+Pg=="));
             });
    }
}
