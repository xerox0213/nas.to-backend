<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreArticle extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'cover_image' => ['image', 'file', 'mimes:jpeg,jpg,png,webp', 'size:2048'],
            'title' => ['required', 'string'],
            'content' => ['required', 'string']
        ];
    }
}
