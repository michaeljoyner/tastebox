<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NoteRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'title' => ['required'],
            'body' => ['required'],
        ];
    }

    public function noteTitle(): string
    {
        return $this->get('title') ?? '';
    }

    public function noteBody(): string
    {
        return $this->get('body') ?? '';
    }
}
