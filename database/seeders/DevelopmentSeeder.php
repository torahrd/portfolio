<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DevelopmentSeeder extends Seeder
{
    /**
     * 開発環境用のテストデータを生成
     */
    public function run(): void
    {
        // テストユーザー作成（1人のみ）
        $user = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'テストユーザー',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );

        // テスト店舗作成
        $shop = Shop::firstOrCreate(
            ['name' => 'テストレストラン'],
            [
                'address' => '東京都渋谷区1-1-1',
                'formatted_phone_number' => '03-1234-5678',
                'website' => 'https://example.com',
                'latitude' => 35.6585,
                'longitude' => 139.7014,
                'created_by' => $user->id,
            ]
        );

        // テスト投稿作成（シンプルに1件のみ）
        $post = Post::firstOrCreate(
            ['memo' => 'CSPテスト用投稿'],
            [
                'shop_id' => $shop->id,
                'user_id' => $user->id,
                'visit_time' => now()->subDays(1),
                'budget' => 3000,
                'repeat_menu' => 'パスタセット',
                'interest_menu' => 'デザートプレート',
                'visit_status' => 1,
                'private_status' => 0,
            ]
        );

        // テストコメント作成
        Comment::firstOrCreate(
            ['body' => 'テストコメントです'],
            [
                'post_id' => $post->id,
                'user_id' => $user->id,
                'parent_id' => null,
            ]
        );

        $this->command->info('開発用データのシード完了');
        $this->command->info('ログイン情報: test@example.com / password123');
        $this->command->info('投稿数: '.Post::count());
        $this->command->info('コメント数: '.Comment::count());
    }
}
