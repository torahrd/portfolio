<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // メンション機能テスト用ユーザーを作成
        $testUsers = [
            [
                'name' => 'アリス',
                'email' => 'alice@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'ボブ',
                'email' => 'bob@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'チャーリー',
                'email' => 'charlie@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'ダイアナ',
                'email' => 'diana@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        ];

        foreach ($testUsers as $userData) {
            // 既に存在する場合はスキップ
            if (!User::where('email', $userData['email'])->exists()) {
                User::create($userData);
                $this->command->info("✅ Created user: {$userData['name']} ({$userData['email']})");
            } else {
                $this->command->info("⚠️  User already exists: {$userData['name']}");
            }
        }

        $this->command->info('');
        $this->command->info('🎯 メンション機能テストの準備完了！');
        $this->command->info('📝 テスト手順:');
        $this->command->info('   1. アリスでログインしてコメントを投稿');
        $this->command->info('   2. ボブでログインして返信フォームを開く');
        $this->command->info('   3. @ア と入力してアリスを検索');
        $this->command->info('   4. @チ と入力してチャーリーを検索');
        $this->command->info('');
        $this->command->info('🔐 全ユーザーのパスワード: password');
    }
}
