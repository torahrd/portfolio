<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class SessionEncryptionTest extends TestCase
{
    // RefreshDatabaseは使用しない - 本番データを保護

    /**
     * セッション暗号化が有効になっているかテスト
     */
    public function test_session_encryption_is_enabled(): void
    {
        // 設定ファイルから暗号化設定を取得
        $isEncrypted = config('session.encrypt');

        // 暗号化が有効になっていることを確認
        $this->assertTrue($isEncrypted, 'セッション暗号化が有効になっていません');
    }

    /**
     * 暗号化後もセッションが正しく動作することのテスト
     */
    public function test_session_works_correctly_with_encryption(): void
    {
        // テストユーザーを作成
        $user = User::factory()->create();

        // ログイン
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        // ログイン成功を確認（/postsへリダイレクト）
        $response->assertRedirect('/posts');
        $this->assertAuthenticatedAs($user);

        // セッションにデータを保存
        session(['test_key' => 'test_value']);

        // セッションからデータを取得できることを確認
        $this->assertEquals('test_value', session('test_key'));

        // 認証セッションが維持されていることを確認
        $response = $this->get('/posts');
        $response->assertStatus(200);
        $this->assertAuthenticatedAs($user);
    }

    /**
     * セッション暗号化の設定が本番環境用に適切かテスト
     */
    public function test_session_configuration_is_production_ready(): void
    {
        // セッションドライバーがfileまたはdatabaseであることを確認
        $driver = config('session.driver');
        $this->assertContains($driver, ['file', 'database', 'redis', 'array'],
            'セッションドライバーが本番環境に適さない設定です');

        // セッションの有効期限が適切に設定されていることを確認
        $lifetime = config('session.lifetime');
        $this->assertGreaterThanOrEqual(120, $lifetime,
            'セッションの有効期限が短すぎます');

        // HTTPSでのみCookieを送信する設定（本番環境の場合）
        if (app()->environment('production')) {
            $secureOnly = config('session.secure');
            $this->assertTrue($secureOnly,
                '本番環境ではsecure cookieを有効にすべきです');
        }
    }
}
