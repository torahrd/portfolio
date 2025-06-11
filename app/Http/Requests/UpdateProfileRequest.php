<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    /**
     * ユーザーがこのリクエストを行う権限があるかを判定
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * バリデーションルールを定義
     */
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

    /**
     * エラーメッセージをカスタマイズ
     */
    public function messages(): array
    {
        return [
            'name.required' => '名前は必須です',
            'name.min' => '名前は2文字以上で入力してください',
            'name.max' => '名前は50文字以内で入力してください',
            'bio.max' => '自己紹介は500文字以内で入力してください',
            'location.max' => '場所は100文字以内で入力してください',
            'website.url' => '有効なURLを入力してください',
            'website.max' => 'ウェブサイトURLは255文字以内で入力してください',
            'avatar.image' => 'プロフィール画像は画像ファイルである必要があります',
            'avatar.mimes' => 'プロフィール画像はJPEG、PNG、WebP形式のみ対応しています',
            'avatar.max' => 'プロフィール画像は2MB以下にしてください',
            'avatar.dimensions' => 'プロフィール画像は100x100px以上、2000x2000px以下にしてください'
        ];
    }

    /**
     * フィールド名をカスタマイズ
     */
    public function attributes(): array
    {
        return [
            'name' => '名前',
            'bio' => '自己紹介',
            'location' => '場所',
            'website' => 'ウェブサイト',
            'avatar' => 'プロフィール画像',
            'is_private' => 'プライバシー設定'
        ];
    }
}
