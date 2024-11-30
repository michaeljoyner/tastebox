<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

class ImageUploadRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'image' => ['required', 'image']
        ];
    }

    public function image(): UploadedFile
    {
        return $this->image;
    }
}
