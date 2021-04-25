<?php

namespace App\Http\Controllers\Admin;

use App\Blog\Post;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PostTitleImageController extends Controller
{
    public function store(Post $post)
    {
        $post->setTitleImage(request('image'));
    }
}
