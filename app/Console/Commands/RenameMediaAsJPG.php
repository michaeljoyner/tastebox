<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class RenameMediaAsJPG extends Command
{

    protected $signature = 'images:rename-jpg';


    protected $description = 'Rename images as jpg';


    public function __construct()
    {
        parent::__construct();
    }


    public function handle()
    {
        Media::withoutEvents(function () {
            Media::all()->each(function (Media $media) {
                if ($media->mime_type === 'image/jpeg' && !Str::contains($media->file_name, '.')) {
                    $media->file_name = sprintf("%s.jpg", $media->file_name);
                    $media->save();
                }
            });
        });


        $media_files = Storage::disk('media')->allFiles();
        collect($media_files)->each(function ($file) {
            if (!Str::contains($file, '.jpg')) {
                $this->info($file);
                Storage::disk('media')->move($file, sprintf("%s.jpg", $file));
            }
        });

        return 0;
    }
}
