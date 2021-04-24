<?php


namespace Tests;


use Illuminate\Support\Facades\ParallelTesting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

trait TestsMedia
{
    public function assertStorageHasImage(Media $image, $conversion = '', $disk = 'media')
    {
        Storage::disk($disk)->assertExists(Str::after($image->getPath($conversion), $this->mediaDirectory()));
    }

    public function assertStorageDoesNotHaveImage(Media $image, $conversion = '', $disk = 'media')
    {
        Storage::disk($disk)->assertMissing(Str::after($image->getPath($conversion), $this->mediaDirectory()));
    }

    private function mediaDirectory()
    {
        if(ParallelTesting::token()) {
            return sprintf("/media_test_%s", ParallelTesting::token());
        }

        return '/media';
    }


}
