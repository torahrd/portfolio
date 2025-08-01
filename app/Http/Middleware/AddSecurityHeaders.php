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

        return $response;
    }
} 