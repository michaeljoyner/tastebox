<?php

namespace App\Http\Controllers\Admin;

use App\Blog\Post;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PostBodyImagesController extends Controller
{
    public function store(Post $post)
    {
        request()->validate([
            'image' => ['image'],
        ]);
        $image = $post->attachImage(request('image'));

        return ['src' => $image->getUrl('web')];
    }
}
