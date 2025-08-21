<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SriImplementationTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * CDNリソースにintegrity属性が設定されているか確認
     */
    public function test_cdn_resources_have_integrity_attributes(): void
    {
        // テスト用ユーザーを作成
        $user = \App\Models\User::factory()->create();

        // プロフィール編集画面のチェック
        $response = $this->actingAs($user)->get('/profile');
        $response->assertStatus(200);
        $html = $response->getContent();

        // CDNリソースの存在とintegrity属性をチェック
        $this->assertStringContainsString('cdn.jsdelivr.net/npm/bootstrap', $html);
        $this->assertStringContainsString('integrity=', $html);
        $this->assertStringContainsString('crossorigin=', $html);

        // 各CDNリソースのintegrity属性を確認
        $this->assertMatchesRegularExpression(
            '/link[^>]+href="https:\/\/cdn\.jsdelivr\.net\/npm\/bootstrap[^"]+".+?integrity="[^"]+"/s',
            $html,
            'Bootstrap CSSにintegrity属性が必要です'
        );

        $this->assertMatchesRegularExpression(
            '/script[^>]+src="https:\/\/cdn\.jsdelivr\.net\/npm\/bootstrap[^"]+".+?integrity="[^"]+"/s',
            $html,
            'Bootstrap JSにintegrity属性が必要です'
        );

        $this->assertMatchesRegularExpression(
            '/script[^>]+src="https:\/\/code\.jquery\.com[^"]+".+?integrity="[^"]+"/s',
            $html,
            'jQueryにintegrity属性が必要です'
        );

        $this->assertMatchesRegularExpression(
            '/link[^>]+href="https:\/\/cdnjs\.cloudflare\.com\/ajax\/libs\/font-awesome[^"]+".+?integrity="[^"]+"/s',
            $html,
            'Font Awesomeにintegrity属性が必要です'
        );
    }

    /**
     * crossorigin属性も同時に設定されているか確認
     */
    public function test_cdn_resources_have_crossorigin_attributes(): void
    {
        // テスト用ユーザーを作成
        $user = \App\Models\User::factory()->create();

        $response = $this->actingAs($user)->get('/profile');
        $response->assertStatus(200);
        $html = $response->getContent();

        // CDNリソースにはcrossorigin="anonymous"が必要
        $this->assertMatchesRegularExpression(
            '/crossorigin=["\'](anonymous)["\']/',
            $html,
            'CDNリソースにはcrossorigin属性が必要です'
        );

        // 各CDNリソースにcrossorigin属性があることを確認
        $cdnPatterns = [
            'cdn.jsdelivr.net/npm/bootstrap',
            'code.jquery.com',
            'cdnjs.cloudflare.com/ajax/libs/font-awesome',
        ];

        foreach ($cdnPatterns as $pattern) {
            $this->assertStringContainsString($pattern, $html, "{$pattern}が存在しません");
        }
    }
}
