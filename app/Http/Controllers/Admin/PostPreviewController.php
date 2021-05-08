<?php

namespace App\Http\Controllers\Admin;

use App\Blog\Post;
use App\Blog\PostPresenter;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PostPreviewController extends Controller
{
    public function show(Post $post)
    {
        return view('front.blog.show', ['post' => PostPresenter::forShow($post)]);
    }
}
