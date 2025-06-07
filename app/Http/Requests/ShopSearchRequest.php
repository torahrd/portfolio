<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShopSearchRequest extends FormRequest
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
            'query' => 'required|string|min:2|max:50'
        ];
    }

    /**
     * エラーメッセージをカスタマイズ
     */
    public function messages(): array
    {
        return [
            'query.required' => '検索キーワードを入力してください',
            'query.min' => '2文字以上入力してください',
            'query.max' => '50文字以内で入力してください'
        ];
    }
}
