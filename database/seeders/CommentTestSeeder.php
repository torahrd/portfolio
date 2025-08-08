<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class CommentTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('ja_JP');

        // テストユーザーを取得
        $users = User::whereIn('email', [
            'tanaka@test.com',
            'sato@test.com',
            'yamada@test.com',
        ])->get();

        if ($users->isEmpty()) {
            $this->command->error('❌ テストユーザーが見つかりません。先にActionTestSeederを実行してください。');

            return;
        }

        // 全ての投稿を取得
        $posts = Post::with('user', 'shop')->get();

        if ($posts->isEmpty()) {
            $this->command->error('❌ 投稿が見つかりません。先にActionTestSeederを実行してください。');

            return;
        }

        $commentCount = 0;

        foreach ($posts as $post) {
            $this->command->info("📝 投稿「{$post->shop->name}」にコメントを追加中...");

            // 1つ目のコメント（投稿者以外のユーザーが投稿）
            $commenter1 = $users->where('id', '!=', $post->user_id)->random();
            $comment1 = Comment::create([
                'user_id' => $commenter1->id,
                'post_id' => $post->id,
                'parent_id' => null,
                'body' => $this->getRandomComment($post->shop->name, $faker),
            ]);
            $commentCount++;

            // 2つ目のコメント（別のユーザーが投稿）
            $commenter2 = $users->where('id', '!=', $post->user_id)->where('id', '!=', $commenter1->id)->first();
            if ($commenter2) {
                $comment2 = Comment::create([
                    'user_id' => $commenter2->id,
                    'post_id' => $post->id,
                    'parent_id' => null,
                    'body' => $this->getRandomComment($post->shop->name, $faker),
                ]);
                $commentCount++;

                // 3つ目のコメント（1つ目のコメントへの返信）
                $comment3 = Comment::create([
                    'user_id' => $post->user_id, // 投稿者が返信
                    'post_id' => $post->id,
                    'parent_id' => $comment1->id,
                    'body' => $this->getRandomReply($faker),
                ]);
                $commentCount++;
            } else {
                // ユーザーが2人しかいない場合
                $comment2 = Comment::create([
                    'user_id' => $post->user_id,
                    'post_id' => $post->id,
                    'parent_id' => null,
                    'body' => $this->getRandomComment($post->shop->name, $faker),
                ]);
                $commentCount++;

                // 3つ目のコメント（1つ目のコメントへの返信）
                $comment3 = Comment::create([
                    'user_id' => $commenter1->id,
                    'post_id' => $post->id,
                    'parent_id' => $comment1->id,
                    'body' => $this->getRandomReply($faker),
                ]);
                $commentCount++;
            }

            $this->command->info("✅ 投稿「{$post->shop->name}」に3つのコメントを追加しました");
        }

        $this->command->info('');
        $this->command->info('🎯 コメントテストデータ作成完了！');
        $this->command->info('');
        $this->command->info('📝 作成されたデータ:');
        $this->command->info("   📝 投稿数: {$posts->count()}件");
        $this->command->info("   💬 コメント数: {$commentCount}件");
        $this->command->info("   👥 参加ユーザー: {$users->count()}人");
        $this->command->info('');
        $this->command->info('💡 コメントの特徴:');
        $this->command->info('   - 各投稿に3つのコメント');
        $this->command->info('   - 返信機能も含む');
        $this->command->info('   - 店舗名に応じた自然なコメント');
    }

    /**
     * 店舗に応じたランダムなコメントを生成
     */
    private function getRandomComment($shopName, $faker): string
    {
        $comments = [
            'このお店、すごく良さそうですね！',
            '行ってみたいです！',
            '美味しそう！',
            '雰囲気が良さそうですね',
            'また行きたいお店ですね',
            '素敵な投稿をありがとうございます！',
            '参考になります！',
            '今度行ってみます！',
            '写真も綺麗ですね',
            'おすすめメニューは何ですか？',
            '営業時間は何時から何時までですか？',
            '予約は必要ですか？',
            '駐車場はありますか？',
            '子供連れでも大丈夫ですか？',
            'デートに使えそうですね！',
            '友達と行きたいです',
            '家族で行くのに良さそう',
            '記念日におすすめですか？',
            'ランチタイムは混みますか？',
            'ディナータイムは予約必須ですか？',
        ];

        return $faker->randomElement($comments);
    }

    /**
     * ランダムな返信コメントを生成
     */
    private function getRandomReply($faker): string
    {
        $replies = [
            'ありがとうございます！',
            'ぜひ行ってみてください！',
            'お気に入りのお店です',
            'また投稿しますね',
            'おすすめですよ！',
            '楽しんでいただければ嬉しいです',
            'また来てくださいね',
            'お待ちしています！',
            'ご来店お待ちしています',
            'ぜひお試しください',
            '感想を聞かせてください',
            'お気に入りになってもらえると嬉しいです',
            'また違うメニューも試してみてください',
            '季節限定メニューもおすすめです',
            'スタッフも親切ですよ',
        ];

        return $faker->randomElement($replies);
    }
}
