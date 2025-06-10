<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProfileFieldsToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // プロフィール関連のカラムを追加
            $table->string('avatar')->nullable()->after('email');
            $table->text('bio')->nullable()->after('avatar');
            $table->string('location')->nullable()->after('bio');
            $table->string('website')->nullable()->after('location');
            $table->boolean('is_private')->default(false)->after('website');

            // カウンター用のカラムを追加
            $table->integer('followers_count')->default(0)->after('is_private');
            $table->integer('following_count')->default(0)->after('followers_count');
            $table->integer('posts_count')->default(0)->after('following_count');

            // 検索パフォーマンス向上のためのインデックス
            $table->index('is_private');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['is_private']);
            $table->dropColumn([
                'avatar',
                'bio',
                'location',
                'website',
                'is_private',
                'followers_count',
                'following_count',
                'posts_count'
            ]);
        });
    }
}
