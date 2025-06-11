<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;

class FollowRequest extends FormRequest
{
    /**
     * ユーザーがこのリクエストを行う権限があるかを判定
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * バリデーションルールを定義
     */
    public function rules(): array
    {
        return [
            // ルートパラメータの検証は自動的に行われるため、ここでは特別なルールは不要
        ];
    }

    /**
     * バリデーション前の前処理
     */
    protected function prepareForValidation(): void
    {
        // 自分自身をフォローしようとしていないかチェック
        $targetUser = $this->route('user');

        if ($targetUser && auth()->id() === $targetUser->id) {
            $this->failedValidation(
                $this->getValidatorInstance()->errors()->add(
                    'user',
                    '自分自身をフォローすることはできません'
                )
            );
        }
    }

    /**
     * エラーメッセージをカスタマイズ
     */
    public function messages(): array
    {
        return [
            'user.exists' => '指定されたユーザーが存在しません'
        ];
    }

    /**
     * 認証済みユーザーを取得
     */
    public function getAuthenticatedUser(): User
    {
        /** @var User $user */
        $user = auth()->user();
        return $user;
    }

    /**
     * ターゲットユーザーを取得
     */
    public function getTargetUser(): User
    {
        return $this->route('user');
    }
}
