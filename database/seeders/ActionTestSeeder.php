<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Post;
use App\Models\Shop;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class ActionTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('ja_JP');

        // 動作確認用の3人のユーザーを作成
        $testUsers = [
            [
                'name' => '田中太郎',
                'email' => 'tanaka@test.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'bio' => 'グルメ好きの会社員です。',
                'location' => '東京都',
                'is_private' => false,
            ],
            [
                'name' => '佐藤花子',
                'email' => 'sato@test.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'bio' => 'カフェ巡りが趣味です。',
                'location' => '大阪府',
                'is_private' => false,
            ],
            [
                'name' => '山田次郎',
                'email' => 'yamada@test.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'bio' => 'ラーメン大好き！',
                'location' => '福岡県',
                'is_private' => false,
            ]
        ];

        $createdUsers = [];

        foreach ($testUsers as $userData) {
            // 既に存在する場合は取得、存在しない場合は作成
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                $userData
            );
            $createdUsers[] = $user;
            $this->command->info("✅ User ready: {$user->name} ({$user->email})");
        }

        // 店舗データを取得（存在しない場合は作成）
        $shops = Shop::all();
        if ($shops->isEmpty()) {
            // 店舗が存在しない場合は作成
            $shopNames = [
                '美味しいラーメン店',
                'カフェ・ド・パリ',
                '寿司処 海鮮',
                '焼肉 牛角',
                'イタリアン ベラ・ヴィスタ',
                '中華料理 龍門'
            ];

            foreach ($shopNames as $shopName) {
                Shop::create([
                    'name' => $shopName,
                    'address' => $faker->address(),
                    'created_by' => $createdUsers[0]->id,
                ]);
            }
            $shops = Shop::all();
            $this->command->info("✅ Created {$shops->count()} shops");
        }

        // 各ユーザーに2つずつ投稿を作成
        foreach ($createdUsers as $user) {
            for ($i = 0; $i < 2; $i++) {
                $shop = $shops->random();
                
                $post = Post::create([
                    'user_id' => $user->id,
                    'shop_id' => $shop->id,
                    'visit_time' => $faker->dateTimeBetween('-6 months', 'now'),
                    'budget' => $faker->numberBetween(500, 5000),
                    'repeat_menu' => $faker->randomElement([
                        'ラーメン',
                        'カフェラテ',
                        '寿司セット',
                        'カルビ',
                        'パスタ',
                        '麻婆豆腐'
                    ]),
                    'interest_menu' => $faker->randomElement([
                        'つけ麺',
                        'エスプレッソ',
                        '刺身盛り合わせ',
                        'タン',
                        'ピザ',
                        '餃子'
                    ]),
                    'reference_link' => $faker->optional()->url(),
                    'memo' => $faker->realText(200),
                    'visit_status' => $faker->boolean(80), // 80%の確率でtrue
                    'private_status' => $faker->boolean(20), // 20%の確率でtrue
                ]);

                $this->command->info("✅ Created post for {$user->name}: {$shop->name}");
            }
        }

        $this->command->info('');
        $this->command->info('🎯 動作確認用テストデータ作成完了！');
        $this->command->info('');
        $this->command->info('📝 作成されたデータ:');
        $this->command->info("   👥 ユーザー: " . count($createdUsers) . "人");
        $this->command->info("   🏪 店舗: {$shops->count()}店");
        $this->command->info("   📝 投稿: " . (count($createdUsers) * 2) . "件");
        $this->command->info('');
        $this->command->info('🔐 全ユーザーのパスワード: password');
        $this->command->info('');
        $this->command->info('📋 テスト用アカウント:');
        foreach ($createdUsers as $user) {
            $this->command->info("   {$user->name}: {$user->email}");
        }
    }
} 