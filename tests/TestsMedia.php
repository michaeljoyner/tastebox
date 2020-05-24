<?php


namespace Tests;


use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

trait TestsMedia
{
    public function assertStorageHasImage(Media $image, $conversion = '', $disk = 'media')
    {
        Storage::disk($disk)->assertExists(Str::after($image->getPath($conversion), '/media'));
    }

    public function assertStorageDoesNotHaveImage(Media $image, $conversion = '', $disk = 'media')
    {
        Storage::disk($disk)->assertMissing(Str::after($image->getPath($conversion), '/media'));
    }


}
