<?php

namespace Tests\Feature;

use Tests\TestCase;

class CspComplianceTest extends TestCase
{
    /**
     * CSPヘッダーにunsafe-inlineが含まれていないことを確認
     */
    public function test_csp_header_does_not_contain_unsafe_inline(): void
    {
        $response = $this->get('/');

        $cspHeader = $response->headers->get('Content-Security-Policy-Report-Only');

        // unsafe-inlineが含まれていないことを確認
        $this->assertStringNotContainsString('unsafe-inline', $cspHeader);
    }

    /**
     * HTMLにインラインスタイルが使用されていないことを確認
     */
    public function test_no_inline_styles_in_html(): void
    {
        // ゲストでアクセス可能なルートをテスト
        $routes = [
            '/',
            '/posts',
            '/landing',
        ];

        foreach ($routes as $route) {
            $response = $this->get($route);
            $html = $response->getContent();

            // style属性が存在しないことを確認（メールテンプレート以外）
            // メールテンプレートは外部CSSが使えないため除外
            if (! str_contains($route, 'mail')) {
                $this->assertDoesNotMatchRegularExpression(
                    '/style\s*=\s*["\']/i',
                    $html,
                    "Route {$route} contains inline styles"
                );
            }
        }
    }

    /**
     * 各画面が正常に表示されることを確認
     * 注: CSPエラーはブラウザコンソールで手動確認が必要
     */
    public function test_pages_render_without_csp_errors(): void
    {
        // トップページ
        $this->get('/')
            ->assertOk()
            ->assertSee('TasteRetreat');

        // 投稿一覧（認証が必要なページのためログイン後にアクセス）
        $user = \App\Models\User::first();
        if ($user) {
            $this->actingAs($user)->get('/posts')
                ->assertOk();
        }

        // ランディングページ
        $this->get('/landing')
            ->assertOk()
            ->assertSee('TasteRetreat');
    }

    /**
     * ドロップダウンコンポーネントがインラインスタイルを使用していないことを確認
     */
    public function test_dropdown_components_no_inline_styles(): void
    {
        // ログインユーザーが必要なページをテスト
        $user = \App\Models\User::first();

        if ($user) {
            $response = $this->actingAs($user)->get('/');
            $html = $response->getContent();

            // ドロップダウンメニューにstyle="display: none;"が含まれていないことを確認
            $this->assertStringNotContainsString(
                'style="display: none;"',
                $html,
                'Dropdown contains inline display:none style'
            );
        }
    }
}
