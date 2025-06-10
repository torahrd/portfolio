<?php
// app/Http/Requests/UpdateProfileRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:50'],
            'bio' => ['nullable', 'string', 'max:500'],
            'location' => ['nullable', 'string', 'max:100'],
            'website' => ['nullable', 'url', 'max:255'],
            'avatar' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
                'dimensions:min_width=100,min_height=100,max_width=2000,max_height=2000'
            ],
            'is_private' => ['boolean']
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => '名前は必須です',
            'name.min' => '名前は2文字以上で入力してください',
            'name.max' => '名前は50文字以内で入力してください',
            'bio.max' => '自己紹介は500文字以内で入力してください',
            'location.max' => '場所は100文字以内で入力してください',
            'website.url' => '有効なURLを入力してください',
            'avatar.image' => 'プロフィール画像は画像ファイルである必要があります',
            'avatar.mimes' => 'プロフィール画像はJPEG、PNG、WebP形式のみ対応しています',
            'avatar.max' => 'プロフィール画像は2MB以下にしてください',
            'avatar.dimensions' => 'プロフィール画像は100x100px以上、2000x2000px以下にしてください'
        ];
    }
}
