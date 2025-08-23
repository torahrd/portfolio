<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Shop;
use App\Models\User;
use Tests\TestCase;

class CommentDuplicateFixTest extends TestCase
{
    private User $user;

    private Post $post;

    protected function setUp(): void
    {
        parent::setUp();

        // RefreshDatabase使用禁止のため、手動でデータ作成
        $this->user = User::factory()->create();
        $shop = Shop::factory()->create();
        $this->post = Post::factory()->create([
            'user_id' => $this->user->id,
            'shop_id' => $shop->id,
        ]);
    }

    protected function tearDown(): void
    {
        // テストデータをクリーンアップ
        if (isset($this->post)) {
            Comment::where('post_id', $this->post->id)->forceDelete();
            $this->post->forceDelete();
        }
        if (isset($this->post->shop_id)) {
            Shop::find($this->post->shop_id)?->forceDelete();
        }
        if (isset($this->user)) {
            $this->user->forceDelete();
        }

        parent::tearDown();
    }

    /**
     * コメントが単一送信のみ処理されることを確認
     */
    public function test_comment_is_created_only_once_on_single_submission(): void
    {
        $this->actingAs($this->user);

        $commentData = [
            'body' => 'テストコメント - 単一送信テスト',
        ];

        // コメント投稿
        $response = $this->postJson(
            route('comments.store', $this->post),
            $commentData
        );

        $response->assertStatus(201);

        // データベースに1件のみ保存されているか確認
        $comments = Comment::where('post_id', $this->post->id)
            ->where('body', $commentData['body'])
            ->get();

        $this->assertCount(1, $comments, 'コメントは1件のみ作成されるべき');
    }

    /**
     * 同じ内容のコメントも複数投稿可能であることを確認
     */
    public function test_same_content_can_be_posted_multiple_times(): void
    {
        $this->actingAs($this->user);

        $commentData = [
            'body' => 'ありがとう', // よくある短いコメント
        ];

        // 1回目の送信
        $response1 = $this->postJson(
            route('comments.store', $this->post),
            $commentData
        );
        $response1->assertStatus(201);

        // 2回目の送信（同じ内容を送信 - これは許可されるべき）
        $response2 = $this->postJson(
            route('comments.store', $this->post),
            $commentData
        );
        $response2->assertStatus(201);

        // 同じ内容のコメントが2つ作成されていることを確認
        $comments = Comment::where('post_id', $this->post->id)
            ->where('body', $commentData['body'])
            ->get();

        $this->assertCount(2, $comments, '同じ内容のコメントも複数投稿可能であるべき');
    }

    /**
     * 投稿詳細ページでコメントフォームが正しく表示されることを確認
     */
    public function test_comment_form_is_displayed_correctly(): void
    {
        $this->actingAs($this->user);

        // 投稿詳細ページを表示
        $response = $this->get(route('posts.show', $this->post));
        $response->assertStatus(200);

        // コメントフォームが表示されているか確認
        $response->assertSee('コメント投稿');
        $response->assertSee('id="comment-form"', false);
        $response->assertSee('submitComment', false);
    }
}
