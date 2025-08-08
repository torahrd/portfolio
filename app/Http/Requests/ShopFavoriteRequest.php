<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest; // ← このuse文が重要

class ShopFavoriteRequest extends FormRequest
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
            'shop_id' => [
                'required',
                'integer',
                'exists:shops,id',
                // 既にお気に入りされていないかチェック（追加時のみ）
                function ($attribute, $value, $fail) {
                    // 解決法1: 型キャストでIDEに明示
                    /** @var User $user */
                    $user = auth()->user();

                    if ($this->routeIs('*.store') && $user && $user->hasFavoriteShop($value)) {
                        $fail('この店舗は既にお気に入りに追加されています。');
                    }
                },
            ],
        ];
    }

    /**
     * エラーメッセージをカスタマイズ
     */
    public function messages(): array
    {
        return [
            'shop_id.required' => '店舗IDが必要です',
            'shop_id.integer' => '店舗IDは数値である必要があります',
            'shop_id.exists' => '指定された店舗が存在しません',
        ];
    }

    /**
     * フィールド名をカスタマイズ
     */
    public function attributes(): array
    {
        return [
            'shop_id' => '店舗',
        ];
    }

    /**
     * バリデーション前の前処理
     */
    protected function prepareForValidation(): void
    {
        // ルートパラメータから shop_id を取得する場合
        if ($this->route('shop') && ! $this->has('shop_id')) {
            $this->merge([
                'shop_id' => $this->route('shop')->id ?? $this->route('shop'),
            ]);
        }
    }

    /**
     * 認証済みユーザーを型安全に取得するヘルパーメソッド
     */
    protected function getAuthenticatedUser(): ?User
    {
        $user = auth()->user();

        return $user instanceof User ? $user : null;
    }

    /**
     * より型安全なバリデーション例
     */
    public function rulesAlternative(): array
    {
        return [
            'shop_id' => [
                'required',
                'integer',
                'exists:shops,id',
                function ($attribute, $value, $fail) {
                    // 解決法2: ヘルパーメソッドを使用
                    $user = $this->getAuthenticatedUser();

                    if ($user && $this->routeIs('*.store') && $user->hasFavoriteShop($value)) {
                        $fail('この店舗は既にお気に入りに追加されています。');
                    }
                },
            ],
        ];
    }
}
