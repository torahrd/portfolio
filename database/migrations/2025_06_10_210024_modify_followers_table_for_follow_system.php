<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyFollowersTableForFollowSystem extends Migration
{
    public function up()
    {
        Schema::table('followers', function (Blueprint $table) {
            // status列を追加（フォロー状態管理用）
            $table->enum('status', ['active', 'pending', 'blocked'])->default('active')->after('followed_id');

            // updated_at列を追加（created_atは既に存在）
            $table->timestamp('updated_at')->nullable()->after('created_at');

            // パフォーマンス最適化のためのインデックスを追加
            $table->index(['following_id', 'status'], 'idx_following_status');
            $table->index(['followed_id', 'status'], 'idx_followed_status');
            $table->index('created_at');
            $table->index('status');

            // 複合ユニーク制約を追加（重複フォロー防止）
            $table->unique(['following_id', 'followed_id'], 'unique_follow_relationship');
        });
    }

    public function down()
    {
        Schema::table('followers', function (Blueprint $table) {
            // インデックスを削除
            $table->dropIndex('idx_following_status');
            $table->dropIndex('idx_followed_status');
            $table->dropIndex(['created_at']);
            $table->dropIndex(['status']);
            $table->dropUnique('unique_follow_relationship');

            // 追加した列を削除
            $table->dropColumn(['status', 'updated_at']);
        });
    }
}
