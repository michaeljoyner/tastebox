<?php

namespace App\Http\Controllers\Admin;

use App\Blog\Post;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PublishedPostsController extends Controller
{
    public function store()
    {
        $post = Post::findOrFail(request('post_id'));
        $post->publish();
    }

    public function destroy(Post $post)
    {
        $post->retract();
    }
}
