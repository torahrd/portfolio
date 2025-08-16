<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class PrivatePostAuthorizationTest extends TestCase
{
    use DatabaseTransactions; // RefreshDatabaseの代わりにDatabaseTransactionsを使用

    private User $postOwner;

    private User $otherUser;

    private Shop $shop;

    protected function setUp(): void
    {
        parent::setUp();

        // テスト用データの準備
        $this->postOwner = User::factory()->create();
        $this->otherUser = User::factory()->create();
        $this->shop = Shop::factory()->create();
    }

    /**
     * 公開投稿は誰でも閲覧できることをテスト
     */
    public function test_public_post_can_be_viewed_by_anyone(): void
    {
        $publicPost = Post::factory()->create([
            'user_id' => $this->postOwner->id,
            'shop_id' => $this->shop->id,
            'private_status' => false, // 公開
        ]);

        $this->actingAs($this->otherUser);

        $response = $this->get("/posts/{$publicPost->id}");

        $response->assertStatus(200);
    }

    /**
     * 非公開投稿は投稿者本人のみ閲覧できることをテスト
     */
    public function test_private_post_can_only_be_viewed_by_owner(): void
    {
        $privatePost = Post::factory()->create([
            'user_id' => $this->postOwner->id,
            'shop_id' => $this->shop->id,
            'private_status' => true, // 非公開
        ]);

        // 投稿者本人は閲覧可能
        $this->actingAs($this->postOwner);
        $response = $this->get("/posts/{$privatePost->id}");
        $response->assertStatus(200);

        // 他のユーザーは閲覧不可（403エラー）
        $this->actingAs($this->otherUser);
        $response = $this->get("/posts/{$privatePost->id}");
        $response->assertStatus(403);
        
        // 403エラーページが表示される
        $response->assertSee('403');
    }

    /**
     * 非公開投稿は投稿者本人のみ閲覧できることをテスト（追加テスト）
     */
    public function test_private_post_can_only_be_viewed_by_owner_additional(): void
    {
        $privatePost2 = Post::factory()->create([
            'user_id' => $this->postOwner->id,
            'shop_id' => $this->shop->id,
            'private_status' => true, // 非公開
        ]);

        // 投稿者本人は閲覧可能
        $this->actingAs($this->postOwner);
        $response = $this->get("/posts/{$privatePost2->id}");
        $response->assertStatus(200);

        // 他のユーザーは閲覧不可
        $this->actingAs($this->otherUser);
        $response = $this->get("/posts/{$privatePost2->id}");
        $response->assertStatus(403);
        $response->assertSee('403');
    }

    /**
     * 投稿一覧に非公開投稿が表示されないことをテスト（HTTPレスポンスでの確認）
     */
    public function test_private_posts_not_shown_in_index(): void
    {
        // ユニークなメモ内容を生成してテストを確実にする
        $publicMemo = 'PUBLIC_POST_MEMO_'.time();
        $privateMemo = 'PRIVATE_POST_MEMO_'.time();

        // 異なる店舗を作成してテストの精度を上げる
        $publicShop = Shop::factory()->create();
        $privateShop = Shop::factory()->create();

        // 投稿作成者が作成した公開投稿
        $publicPost = Post::factory()->create([
            'user_id' => $this->postOwner->id,
            'shop_id' => $publicShop->id,
            'private_status' => false, // 公開
            'memo' => $publicMemo,
        ]);

        // 投稿作成者が作成した非公開投稿
        $privatePost = Post::factory()->create([
            'user_id' => $this->postOwner->id,
            'shop_id' => $privateShop->id,
            'private_status' => true, // 非公開
            'memo' => $privateMemo,
        ]);

        // 第三者として投稿一覧を確認（HTTPレスポンステスト）
        $this->actingAs($this->otherUser);
        
        // まずデータベースレベルで期待通りのデータが作成されているか確認
        $visiblePosts = Post::where(function ($query) {
            $query->where('private_status', false)
                ->orWhereNull('private_status')
                ->orWhere('user_id', auth()->id());
        })->get();
        
        // データベースレベルで期待通りのフィルタリングが動作することを確認
        $this->assertTrue($visiblePosts->contains($publicPost), 'Public post should be visible in query');
        $this->assertFalse($visiblePosts->contains($privatePost), 'Private post should not be visible in query');
        
        $response = $this->get('/posts');
        
        $response->assertStatus(200);
        // 公開投稿の店舗名は表示される
        $response->assertSee($publicPost->shop->name);
        // 非公開投稿の店舗名は表示されない
        $response->assertDontSee($privatePost->shop->name);

        // 投稿作成者として投稿一覧を確認
        $this->actingAs($this->postOwner);
        $response = $this->get('/posts');
        
        $response->assertStatus(200);
        // 投稿者には両方の店舗名が表示される
        $response->assertSee($publicPost->shop->name);
        $response->assertSee($privatePost->shop->name);
    }

    /**
     * 非公開投稿を編集できるのは投稿者本人のみであることをテスト
     */
    public function test_private_post_can_only_be_updated_by_owner(): void
    {
        $privatePost = Post::factory()->create([
            'user_id' => $this->postOwner->id,
            'shop_id' => $this->shop->id,
            'private_status' => true, // 非公開
        ]);

        // 投稿者本人は編集可能（GETでテスト）
        $this->actingAs($this->postOwner);
        $response = $this->get("/posts/{$privatePost->id}/edit");
        $response->assertStatus(200);

        // 他のユーザーは編集不可
        $this->actingAs($this->otherUser);
        $response = $this->get("/posts/{$privatePost->id}/edit");
        $response->assertStatus(403);
        $response->assertSee('403');
    }

    /**
     * 非公開投稿を削除できるのは投稿者本人のみであることをテスト
     */
    public function test_private_post_can_only_be_deleted_by_owner(): void
    {
        $privatePost = Post::factory()->create([
            'user_id' => $this->postOwner->id,
            'shop_id' => $this->shop->id,
            'private_status' => true, // 非公開
        ]);

        // 他のユーザーは削除不可
        $this->actingAs($this->otherUser);
        $response = $this->delete("/posts/{$privatePost->id}");
        $response->assertStatus(403);
        $response->assertSee('403');

        // 投稿者本人は削除可能
        $this->actingAs($this->postOwner);
        $response = $this->delete("/posts/{$privatePost->id}");
        $response->assertRedirect('/posts');
    }
}
