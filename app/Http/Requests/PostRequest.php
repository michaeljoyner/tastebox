<?php

namespace App\Http\Requests;

use App\Blog\PostInfo;
use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'title' => ['required'],
        ];
    }

    public function postInfo(): PostInfo
    {
        return new PostInfo($this->only([
            'title', 'intro', 'description', 'body'
        ]));
    }
}
