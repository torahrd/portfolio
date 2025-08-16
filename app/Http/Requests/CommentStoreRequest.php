<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentStoreRequest extends FormRequest
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
            'body' => ['required', 'string', 'max:200'],
            'parent_id' => ['nullable', 'exists:comments,id'],
        ];
    }

    /**
     * エラーメッセージをカスタマイズ
     */
    public function messages(): array
    {
        return [
            'body.required' => 'コメント内容を入力してください',
            'body.max' => 'コメントは200文字以内で入力してください',
            'parent_id.exists' => '返信先のコメントが存在しません',
        ];
    }

    /**
     * フィールド名をカスタマイズ
     */
    public function attributes(): array
    {
        return [
            'body' => 'コメント内容',
            'parent_id' => '返信先',
        ];
    }
}
