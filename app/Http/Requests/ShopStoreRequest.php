<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShopStoreRequest extends FormRequest
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
            'name' => 'required|string|max:100',
            'address' => 'required|string|max:200'
        ];
    }

    /**
     * エラーメッセージをカスタマイズ
     */
    public function messages(): array
    {
        return [
            'name.required' => '店舗名を入力してください',
            'name.max' => '店舗名は100文字以内で入力してください',
            'address.required' => '住所を入力してください',
            'address.max' => '住所は200文字以内で入力してください'
        ];
    }
}
