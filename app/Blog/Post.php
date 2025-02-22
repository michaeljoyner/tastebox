<?php

namespace App\Blog;

use App\DatePresenter;
use App\Loggable;
use App\LogsActivities;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Spatie\Image\Enums\Fit;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Post extends Model implements HasMedia, Loggable
{

    use InteractsWithMedia, LogsActivities;

    const BODY_IMAGES = 'body-images';
    const TITLE_IMAGES = 'title-images';
    const DEFAULT_IMG = '/images/default-placeholder.svg';

    protected $casts = [
        'first_published' => 'date:Y-m-d',
        'is_public' => 'boolean',
    ];

    protected static function booted()
    {

        static::saving(function ($post) {
            if($post->first_published && $post->slug) {
                return;
            }

            $slug = Str::slug($post->title);
            $key = 1;
            while (Post::where('id', '<>', $post->id)->where('slug', $slug)->count() > 0) {
                $slug = sprintf("%s-%s", $slug, $key);
                $key++;
            }
            $post->slug = $slug;
        });
    }

    public function scopePublished(Builder $query)
    {
        return $query->where('is_public', true);
    }

    protected $guarded = [];

    public static function new(PostInfo $postInfo): self
    {
        $post = static::create($postInfo->toArray());


        return $post;
    }

    public function publish()
    {
        if(!$this->first_published) {
            $this->first_published = now();
        }

        $this->is_public = true;
        $this->save();
    }

    public function retract()
    {
        $this->is_public = false;
        $this->save();
    }

    public function attachImage(UploadedFile $upload): Media
    {
        return $this->addMedia($upload)
            ->usingFileName($upload->hashName())
            ->toMediaCollection(static::BODY_IMAGES);
    }

    public function setTitleImage(UploadedFile $upload): Media
    {
        $this->clearTitleImage();

        return $this->addMedia($upload)
            ->usingFileName($upload->hashName())
            ->toMediaCollection(static::TITLE_IMAGES);
    }

    public function clearTitleImage()
    {
        $this->clearMediaCollection(static::TITLE_IMAGES);
    }

    public function getTitleImage($conversion = ''): string
    {
        $image = $this->getFirstMedia(static::TITLE_IMAGES);
        return $image ? $image->getUrl($conversion) : static::DEFAULT_IMG;
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('web')
             ->fit(Fit::Max, 1200, 800)
             ->optimize()
             ->performOnCollections(self::BODY_IMAGES, static::TITLE_IMAGES);

        $this->addMediaConversion('sharing')
             ->fit(Fit::Max, 1200, 630)
             ->optimize()
             ->performOnCollections(static::TITLE_IMAGES);
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'intro' => $this->intro,
            'description' => $this->description,
            'body' => $this->body,
            'is_public' => $this->is_public,
            'title_image' => [
                'web' => $this->getTitleImage('web'),
                'sharing' => $this->getTitleImage('sharing'),
            ],
            'first_created' => DatePresenter::pretty($this->created_at),
            'first_published' => DatePresenter::pretty($this->first_published),
            'slug' => $this->slug,
        ];
    }

    public function logCreateActivity($name)
    {
        $action = sprintf("%s started a new post!", $name, $this->title);
        $this->logActivity($name, $action, $this->getActivityItemUrl());
    }

    public function logPublishActivity($name)
    {
        $action = sprintf("%s published '%s'", $name, $this->title);
        $this->logActivity($name, $action, $this->getActivityItemUrl());
    }

    public function logRetractActivity($name)
    {
        $action = sprintf("%s retracted '%s'", $name, $this->title);
        $this->logActivity($name, $action, $this->getActivityItemUrl());
    }

    public function getActivityItemUrl(): string
    {
        return "/blog/posts/{$this->id}/edit";
    }
}
