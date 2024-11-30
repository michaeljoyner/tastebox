<?php

namespace App\AddOns;

use App\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\UploadedFile;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class AddOn extends Model implements HasMedia
{
    use HasUuid, InteractsWithMedia;

    const IMAGE = 'image';

    protected $guarded = [];

    public function category(): BelongsTo
    {
        return $this->belongsTo(AddOnCategory::class, 'add_on_category_id');
    }

    public function setImage(UploadedFile $upload): Media
    {
        $this->clearImage();

        return $this->addMedia($upload)
            ->usingFileName($upload->hashName())
            ->toMediaCollection(self::IMAGE);
    }

    public function clearImage()
    {
        $this->clearMediaCollection(self::IMAGE);
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion("web")
             ->fit(Fit::Max, 1200, 1200)
             ->keepOriginalImageFormat()
             ->optimize()
             ->performOnCollections(self::IMAGE);

        $this->addMediaConversion("thumb")
             ->fit(Fit::Max, 600, 600)
             ->keepOriginalImageFormat()
             ->optimize()
             ->performOnCollections(self::IMAGE);
    }
}
