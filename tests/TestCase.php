<?php

namespace Tests;

use App\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Browsershot\Browsershot;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

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

    protected function fakeJpg(?string $name =  null): UploadedFile
    {
        return UploadedFile::fake()->image($name ?? Str::random(6) . '.jpg');
    }

    protected function fakePng(?string $name =  null): UploadedFile
    {
        return UploadedFile::fake()->image($name ?? Str::random(6) . '.png');
    }

    protected function assertMediaImageExists(Media $media, $conversion = '')
    {
        $media_path = Str::after($media->getPath(), '/media');

        if(empty($conversion)) {
            return Storage::disk('media')->assertExists($media_path);
        }


        $this->assertTrue($media->fresh()->hasGeneratedConversion($conversion), 'conversion does not exist');
        $media_path = Str::after($media->getPath($conversion), '/media');
        Storage::disk('media')->assertExists($media_path);

    }

    protected function assertMediaImageMissing(Media $media)
    {
        $media_path = Str::after($media->getPath(), '/media');
        Storage::disk('media')->assertMissing($media_path);
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
