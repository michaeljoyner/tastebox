<?php

namespace App\Http\Controllers;

use App\Blog\Post;
use App\Blog\PostPresenter;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        return view('front.blog.index', [
            'posts' => Post::latest()
                           ->published()
                           ->limit(20)
                           ->get()
                           ->map(fn($p) => PostPresenter::forIndex($p))
        ]);
    }

    public function show(Post $post)
    {
        abort_if(!$post->is_public, 404);

        return view('front.blog.show', ['post' => PostPresenter::forShow($post)]);
    }
}
