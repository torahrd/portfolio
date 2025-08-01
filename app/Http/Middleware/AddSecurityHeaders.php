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

        // Cache-Control（開発環境用）
        // ブラウザキャッシュを無効化して最新のJavaScriptファイルを確実に読み込む
        if (app()->environment('local')) {
            $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', '0');
        }

        // Content-Security-Policy-Report-Only
        // 段階的実装のためReport-Onlyモードで開始（違反を監視、ブロックしない）
        $cspReportOnlyHeader = "default-src 'self'; " .
                              "script-src 'self' 'unsafe-inline' https://maps.googleapis.com https://maps.googleapis.com/maps/api/js; " .
                              "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; " .
                              "img-src 'self' data: https://maps.gstatic.com https://*.googleapis.com https://*.cloudinary.com https://res.cloudinary.com; " .
                              "connect-src 'self' https://*.googleapis.com https://*.cloudinary.com; " .
                              "font-src https://fonts.gstatic.com; " .
                              "object-src 'none'; " .
                              "base-uri 'self'; " .
                              "form-action 'self'; " .
                              "media-src 'self'";

        $response->headers->set('Content-Security-Policy-Report-Only', $cspReportOnlyHeader);

        return $response;
    }
} 