<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
{
    public function authorize()
    {
        $post = $this->route('post');
        return $this->user()->id === $post->user_id;
    }

    public function rules()
    {
        return [
            'title' => 'sometimes|required|string|max:255',
            'body' => 'sometimes|required|string',
            'image' => 'nullable|image|mimes:jpg,png,gif,webp|max:2048',
        ];
    }
}
