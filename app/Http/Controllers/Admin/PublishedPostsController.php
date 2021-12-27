<?php

namespace App\Http\Controllers\Admin;

use App\Blog\Post;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PublishedPostsController extends Controller
{
    public function store()
    {
        /* @var \App\Blog\Post $post */
        $post = Post::findOrFail(request('post_id'));
        $post->publish();

        $post->logPublishActivity(request()->user()->name);
    }

    public function destroy(Post $post)
    {
        $post->retract();

        $post->logRetractActivity(request()->user()->name);
    }
}
