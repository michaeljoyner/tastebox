<?php

namespace App\Http\Controllers;

use App\Blog\Post;
use App\Blog\PostPresenter;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class BlogArchivesController extends Controller
{
    public function show()
    {
        $months = Post
            ::latest()
            ->published()
            ->get()
            ->groupBy(fn(Post $post) => $post->first_published->format('F Y'))
            ->mapWithKeys(fn(Collection $posts, string $month) => [
                $month => $posts->map(fn(Post $post) => PostPresenter::forIndex($post))
            ]);

        return view('front.blog.archive', [
            'months' => $months,
        ]);

    }
}
