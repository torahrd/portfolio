<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
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
            // ルートパラメータの基本的な検証
            // 詳細なビジネスロジックはコントローラー側で処理
        ];
    }

    /**
     * カスタムバリデーション（withValidatorを使用）
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $targetUser = $this->route('user');

            // 自分自身をフォローしようとしていないかチェック
            if ($targetUser && auth()->id() === $targetUser->id) {
                $validator->errors()->add(
                    'user',
                    '自分自身をフォローすることはできません'
                );
            }

            // ユーザーが存在するかチェック
            if (!$targetUser) {
                $validator->errors()->add(
                    'user',
                    '指定されたユーザーが存在しません'
                );
            }
        });
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
     * フィールド名をカスタマイズ
     */
    public function attributes(): array
    {
        return [
            'user' => 'ユーザー'
        ];
    }

    /**
     * 認証済みユーザーを型安全に取得
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

    /**
     * フォロー処理が可能かチェック
     */
    public function canFollow(): bool
    {
        $currentUser = $this->getAuthenticatedUser();
        $targetUser = $this->getTargetUser();

        // 自分自身はフォローできない
        if ($currentUser->id === $targetUser->id) {
            return false;
        }

        // 既にフォロー中または申請中ではないかチェック
        return !$currentUser->isFollowing($targetUser) &&
            !$currentUser->hasSentFollowRequest($targetUser);
    }
}
