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
        Schema::table('shops', function (Blueprint $table) {
            // Google Places API連携用カラム
            $table->string('google_place_id')->nullable()->after('id')->comment('Google Places APIのPlace ID');
            $table->decimal('latitude', 10, 8)->nullable()->after('address')->comment('緯度');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude')->comment('経度');
            $table->string('formatted_phone_number')->nullable()->after('longitude')->comment('Google Places APIから取得した電話番号');
            $table->string('website')->nullable()->after('formatted_phone_number')->comment('Google Places APIから取得した公式サイトURL');

            // インデックス追加（検索性能向上）
            $table->index('google_place_id');
            $table->index(['latitude', 'longitude']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            // インデックス削除
            $table->dropIndex(['google_place_id']);
            $table->dropIndex(['latitude', 'longitude']);

            // カラム削除
            $table->dropColumn([
                'google_place_id',
                'latitude',
                'longitude',
                'formatted_phone_number',
                'website',
            ]);
        });
    }
};
