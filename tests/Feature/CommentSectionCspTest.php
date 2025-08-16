<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CommentSectionCspTest extends TestCase
{
    use DatabaseTransactions; // RefreshDatabaseの代わりにDatabaseTransactionsを使用

    private User $user;

    private Post $post;

    private Shop $shop;

    protected function setUp(): void
    {
        parent::setUp();

        // テスト用データの準備
        $this->user = User::factory()->create();
        $this->shop = Shop::factory()->create();
        $this->post = Post::factory()->create([
            'user_id' => $this->user->id,
            'shop_id' => $this->shop->id,
        ]);
    }

    /**
     * 新規コメントが投稿できることをテスト
     */
    public function test_user_can_post_new_comment(): void
    {
        $this->actingAs($this->user);

        $response = $this->postJson("/posts/{$this->post->id}/comments", [
            'body' => 'これはテストコメントです',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'html',
                'message',
                'comment_count',
            ]);

        $this->assertDatabaseHas('comments', [
            'post_id' => $this->post->id,
            'user_id' => $this->user->id,
            'body' => 'これはテストコメントです',
            'parent_id' => null,
        ]);
    }

    /**
     * コメントに返信できることをテスト
     */
    public function test_user_can_reply_to_comment(): void
    {
        $this->actingAs($this->user);

        // 親コメントを作成
        $parentComment = Comment::factory()->create([
            'post_id' => $this->post->id,
            'user_id' => $this->user->id,
            'body' => '親コメント',
        ]);

        $response = $this->postJson("/posts/{$this->post->id}/comments", [
            'parent_id' => $parentComment->id,
            'body' => 'これは返信コメントです',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'html',
                'message',
                'comment_count',
            ]);

        $this->assertDatabaseHas('comments', [
            'post_id' => $this->post->id,
            'user_id' => $this->user->id,
            'parent_id' => $parentComment->id,
            'body' => 'これは返信コメントです',
        ]);
    }

    /**
     * 自分のコメントを削除できることをテスト
     */
    public function test_user_can_delete_own_comment(): void
    {
        $this->actingAs($this->user);

        $comment = Comment::factory()->create([
            'post_id' => $this->post->id,
            'user_id' => $this->user->id,
            'body' => '削除するコメント',
        ]);

        $response = $this->deleteJson("/comments/{$comment->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'コメントを削除しました',
            ]);

        $this->assertDatabaseMissing('comments', [
            'id' => $comment->id,
        ]);
    }

    /**
     * 投稿作者がコメント削除できることをテスト
     */
    public function test_post_owner_can_delete_comments(): void
    {
        // 投稿作者としてログイン
        $this->actingAs($this->user);

        // 他のユーザーのコメントを作成
        $otherUser = User::factory()->create();
        $comment = Comment::factory()->create([
            'post_id' => $this->post->id,
            'user_id' => $otherUser->id,
            'body' => '他人のコメント',
        ]);

        // 投稿作者として削除（成功するはず）
        $response = $this->deleteJson("/comments/{$comment->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'コメントを削除しました',
            ]);

        $this->assertDatabaseMissing('comments', [
            'id' => $comment->id,
        ]);
    }

    /**
     * 第三者はコメント削除できないことをテスト
     */
    public function test_third_party_cannot_delete_comments(): void
    {
        // 第三者のユーザーとして削除を試みる
        $thirdUser = User::factory()->create();
        $this->actingAs($thirdUser);

        $otherUser = User::factory()->create();
        $comment = Comment::factory()->create([
            'post_id' => $this->post->id,
            'user_id' => $otherUser->id,
            'body' => '他人のコメント',
        ]);

        $response = $this->deleteJson("/comments/{$comment->id}");

        $response->assertStatus(403);

        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
        ]);
    }

    /**
     * 空のコメントは投稿できないことをテスト
     */
    public function test_cannot_post_empty_comment(): void
    {
        $this->actingAs($this->user);

        $response = $this->postJson("/posts/{$this->post->id}/comments", [
            'body' => '',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['body']);
    }

    /**
     * 長すぎるコメントは投稿できないことをテスト
     */
    public function test_cannot_post_too_long_comment(): void
    {
        $this->actingAs($this->user);

        $response = $this->postJson("/posts/{$this->post->id}/comments", [
            'body' => str_repeat('a', 201), // 200文字制限を超える
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['body']);
    }
}
