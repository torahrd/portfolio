<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shop_favorites', function (Blueprint $table) {
            $table->id();

            // 外部キー制約
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('shop_id')->constrained()->onDelete('cascade');

            // タイムスタンプ
            $table->timestamps();

            // 複合ユニーク制約（重複防止）
            $table->unique(['user_id', 'shop_id'], 'user_shop_unique');

            // パフォーマンス用インデックス
            $table->index('user_id', 'idx_shop_favorites_user_id');
            $table->index('shop_id', 'idx_shop_favorites_shop_id');
            $table->index('created_at', 'idx_shop_favorites_created_at');

            // 複合インデックス（よく使われるクエリ用）
            $table->index(['user_id', 'created_at'], 'idx_user_favorites_recent');
            $table->index(['shop_id', 'created_at'], 'idx_shop_favorites_recent');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_favorites');
    }
};
