<?php


namespace App\Blog;


use App\DatePresenter;

class PostPresenter
{
    public static function forIndex(Post $post): array
    {
        return [
            'slug'            => $post->slug,
            'title'           => $post->title,
            'intro'           => $post->intro,
            'description'     => $post->description,
            'body'            => $post->body,
            'is_public'       => $post->is_public,
            'title_image'     => [
                'web'     => $post->getTitleImage('web'),
                'sharing' => $post->getTitleImage('sharing'),
            ],
            'first_created'   => DatePresenter::pretty($post->created_at),
            'first_published' => DatePresenter::pretty($post->first_published),
        ];
    }

    public static function forShow(Post $post): array
    {
        return [
            'slug'            => $post->slug,
            'title'           => $post->title,
            'intro'           => $post->intro,
            'description'     => $post->description,
            'body'            => $post->body,
            'is_public'       => $post->is_public,
            'title_image'     => [
                'web'     => $post->getTitleImage('web'),
                'sharing' => $post->getTitleImage('sharing'),
            ],
            'first_created'   => DatePresenter::pretty($post->created_at),
            'first_published' => DatePresenter::pretty($post->first_published),
        ];
    }
}
