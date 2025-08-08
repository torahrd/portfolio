<?php

// database/seeders/UpdateExistingFollowersSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateExistingFollowersSeeder extends Seeder
{
    public function run()
    {
        // 既存のfollowersテーブルのデータを'active'ステータスに更新
        DB::table('followers')
            ->whereNull('status')
            ->update([
                'status' => 'active',
                'updated_at' => now(),
            ]);

        // ユーザーのフォロワー・フォロー数を再計算
        $users = DB::table('users')->get();

        foreach ($users as $user) {
            $followersCount = DB::table('followers')
                ->where('followed_id', $user->id)
                ->where('status', 'active')
                ->count();

            $followingCount = DB::table('followers')
                ->where('following_id', $user->id)
                ->where('status', 'active')
                ->count();

            $postsCount = DB::table('posts')
                ->where('user_id', $user->id)
                ->count();

            DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'followers_count' => $followersCount,
                    'following_count' => $followingCount,
                    'posts_count' => $postsCount,
                ]);
        }
    }
}
