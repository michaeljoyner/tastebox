<?php

namespace App\Http\Controllers\Admin;

use App\Blog\Post;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PostTitleImageController extends Controller
{
    public function store(Post $post)
    {
        $image = $post->setTitleImage(request('image'));

        return ['src' => $image->getUrl('sharing')];
    }
}
