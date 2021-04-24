<?php

namespace App\Http\Controllers\Admin;

use App\Blog\Post;
use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use Illuminate\Http\Request;

class PostsController extends Controller
{
    public function store(PostRequest $request)
    {
        return Post::new($request->postInfo());
    }

    public function update(PostRequest $request, Post $post)
    {
        $post->update($request->postInfo()->toArray());
    }

    public function delete(Post $post)
    {
        $post->delete();
    }
}
