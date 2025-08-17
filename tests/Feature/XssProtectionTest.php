<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class XssProtectionTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;

    protected $post;

    protected $shop;

    protected function setUp(): void
    {
        parent::setUp();

        // テスト用データ作成
        $this->user = User::factory()->create();
        $this->shop = Shop::factory()->create();
        $this->post = Post::factory()->create([
            'user_id' => $this->user->id,
            'shop_id' => $this->shop->id,
        ]);
    }

    /**
     * 危険なHTMLタグが除去されることを確認
     */
    public function test_dangerous_html_tags_are_removed()
    {
        $dangerousComment = Comment::create([
            'user_id' => $this->user->id,
            'post_id' => $this->post->id,
            'body' => '<script>alert("XSS")</script>これは普通のテキスト<iframe src="evil.com"></iframe>',
        ]);

        // getBodyWithMentionsAttributeで処理された結果を取得
        $processedBody = $dangerousComment->body_with_mentions;

        // scriptタグが除去されていることを確認
        $this->assertStringNotContainsString('<script>', $processedBody);
        $this->assertStringNotContainsString('</script>', $processedBody);

        // iframeタグが除去されていることを確認
        $this->assertStringNotContainsString('<iframe', $processedBody);
        $this->assertStringNotContainsString('</iframe>', $processedBody);

        // 通常のテキストは残っていることを確認
        $this->assertStringContainsString('これは普通のテキスト', $processedBody);
    }

    /**
     * メンション機能が正常に動作することを確認
     */
    public function test_mentions_are_properly_formatted()
    {
        $commentWithMention = Comment::create([
            'user_id' => $this->user->id,
            'post_id' => $this->post->id,
            'body' => '@田中さん こんにちは！ @yamada_123 さんもどうぞ',
        ]);

        $processedBody = $commentWithMention->body_with_mentions;

        // メンションがspan要素でラップされていることを確認
        $this->assertStringContainsString('<span style="color: #1976d2', $processedBody);
        $this->assertStringContainsString('@田中さん', $processedBody);
        $this->assertStringContainsString('@yamada_123', $processedBody);
    }

    /**
     * XSS攻撃を含むメンションが安全に処理されることを確認
     */
    public function test_xss_in_mentions_is_prevented()
    {
        $xssComment = Comment::create([
            'user_id' => $this->user->id,
            'post_id' => $this->post->id,
            'body' => '@<script>alert("XSS")</script> この人にメンション',
        ]);

        $processedBody = $xssComment->body_with_mentions;

        // scriptタグが実行されないことを確認
        $this->assertStringNotContainsString('<script>', $processedBody);
        $this->assertStringNotContainsString('alert("XSS")', $processedBody);
    }

    /**
     * HTMLエンティティが適切にエスケープされることを確認
     */
    public function test_html_entities_are_escaped()
    {
        $htmlComment = Comment::create([
            'user_id' => $this->user->id,
            'post_id' => $this->post->id,
            'body' => '<div onclick="alert(1)">クリックしてください</div>',
        ]);

        $processedBody = $htmlComment->body_with_mentions;

        // divタグとonclick属性が除去されていることを確認
        $this->assertStringNotContainsString('<div', $processedBody);
        $this->assertStringNotContainsString('onclick=', $processedBody);

        // テキスト内容は残っていることを確認
        $this->assertStringContainsString('クリックしてください', $processedBody);
    }

    /**
     * 複雑なXSS攻撃パターンが防御されることを確認
     */
    public function test_complex_xss_patterns_are_prevented()
    {
        $complexXss = Comment::create([
            'user_id' => $this->user->id,
            'post_id' => $this->post->id,
            'body' => '<img src=x onerror="alert(1)"><svg onload="alert(2)">普通のテキスト',
        ]);

        $processedBody = $complexXss->body_with_mentions;

        // 危険なタグと属性が除去されていることを確認
        $this->assertStringNotContainsString('<img', $processedBody);
        $this->assertStringNotContainsString('onerror=', $processedBody);
        $this->assertStringNotContainsString('<svg', $processedBody);
        $this->assertStringNotContainsString('onload=', $processedBody);

        // 安全なテキストは残っていることを確認
        $this->assertStringContainsString('普通のテキスト', $processedBody);
    }
}
