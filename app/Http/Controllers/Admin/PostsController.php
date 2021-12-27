<?php

namespace App\Http\Controllers\Admin;

use App\Blog\Post;
use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use Illuminate\Http\Request;

class PostsController extends Controller
{

    public function index()
    {
        return Post::latest()->get();
    }

    public function store(PostRequest $request)
    {
        $post = Post::new($request->postInfo());

        $post->logCreateActivity($request->user()->name);

        return $post;
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
