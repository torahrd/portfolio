<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AddSecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Strict-Transport-Security (HSTS)
        // HTTPSサイトでのみ有効、HTTPリクエストをHTTPSに強制リダイレクト
        // 段階的導入のため3日間（259200秒）で設定
        $response->headers->set('Strict-Transport-Security', 'max-age=259200; includeSubDomains');

        // X-Content-Type-Options
        // MIMEタイプの推測攻撃を防止
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // X-Frame-Options
        // クリックジャッキング攻撃を防止（iframeでの埋め込みを禁止）
        $response->headers->set('X-Frame-Options', 'DENY');

        // X-XSS-Protection
        // 古いブラウザでのXSS攻撃検出（モダンブラウザではCSPが推奨）
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Referrer-Policy
        // リファラー情報の送信ポリシーを制御
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Permissions-Policy
        // ブラウザのAPIと機能の使用を制限（セキュリティとプライバシー向上）
        $response->headers->set('Permissions-Policy',
            'accelerometer=(), '.
            'camera=(), '.
            'geolocation=(self), '.  // 位置情報は自サイトのみ（将来の地図機能用）
            'gyroscope=(), '.
            'magnetometer=(), '.
            'microphone=(), '.
            'payment=(), '.
            'usb=()'
        );

        // Cache-Control（開発環境用）
        // ブラウザキャッシュを無効化して最新のJavaScriptファイルを確実に読み込む
        if (app()->environment('local')) {
            $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', '0');
        }

        // Content-Security-Policy-Report-Only
        // 段階的実装のためReport-Onlyモードで開始（違反を監視、ブロックしない）
        // GA4用のドメインを追加
        $ga4Domains = config('analytics.enabled') ? [
            'script' => implode(' ', config('analytics.csp.script_src', [])),
            'connect' => implode(' ', config('analytics.csp.connect_src', [])),
            'img' => implode(' ', config('analytics.csp.img_src', [])),
        ] : [
            'script' => '',
            'connect' => '',
            'img' => '',
        ];

        // CSP設定（unsafe-eval、unsafe-inline削除済み）
        $connectSources = "'self' https://*.googleapis.com https://*.cloudinary.com {$ga4Domains['connect']}";
        $scriptSources = "'self' https://maps.googleapis.com https://maps.googleapis.com/maps/api/js {$ga4Domains['script']}";
        $styleSources = "'self' https://fonts.googleapis.com";

        // 開発環境ではViteサーバーを許可（複数ポート対応）
        if (app()->environment('local')) {
            // WebSocket接続用（一般的なViteポート範囲）
            $connectSources .= ' ws://localhost:5173 ws://localhost:5174 ws://localhost:5175';
            $connectSources .= ' wss://localhost:5173 wss://localhost:5174 wss://localhost:5175';
            $connectSources .= ' ws://127.0.0.1:5173 ws://127.0.0.1:5174 ws://127.0.0.1:5175';

            // スクリプトとスタイル用
            $scriptSources .= ' http://localhost:5173 http://localhost:5174 http://localhost:5175';
            $styleSources .= ' http://localhost:5173 http://localhost:5174 http://localhost:5175';
        }

        $cspReportOnlyHeader = "default-src 'self'; ".
                              "script-src {$scriptSources}; ".
                              "style-src {$styleSources}; ".
                              "img-src 'self' data: https://maps.gstatic.com https://*.googleapis.com https://*.cloudinary.com https://res.cloudinary.com {$ga4Domains['img']}; ".
                              "connect-src {$connectSources}; ".
                              'font-src https://fonts.gstatic.com; '.
                              "object-src 'none'; ".
                              "base-uri 'self'; ".
                              "form-action 'self'; ".
                              "media-src 'self'";

        $response->headers->set('Content-Security-Policy-Report-Only', $cspReportOnlyHeader);

        return $response;
    }
}
