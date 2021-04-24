<?php

namespace App\Blog;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{

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

    protected $guarded = [];

    public static function new(PostInfo $postInfo): self
    {
        return static::create($postInfo->toArray());
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
}
