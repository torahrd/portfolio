<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Google Analytics 4 設定
    |--------------------------------------------------------------------------
    |
    | GA4の測定IDと関連設定を管理します。
    | 本番環境でのみ有効化し、開発環境ではデバッグモードを使用可能です。
    |
    */

    // 基本設定
    'enabled' => env('GA4_ENABLED', false) && env('APP_ENV') !== 'testing',
    'measurement_id' => env('GA4_MEASUREMENT_ID', ''),
    'debug_mode' => env('ANALYTICS_DEBUG', false) && env('APP_ENV') === 'local',

    // CSP設定用のドメイン
    'csp' => [
        'script_src' => [
            'https://www.googletagmanager.com',
            'https://www.google-analytics.com',
        ],
        'connect_src' => [
            'https://www.google-analytics.com',
            'https://region1.google-analytics.com',
            'https://analytics.google.com',
            'https://stats.g.doubleclick.net',
        ],
        'img_src' => [
            'https://www.google-analytics.com',
            'https://www.googletagmanager.com',
        ],
    ],

    // トラッキング設定
    'tracking' => [
        // IPアドレスの匿名化
        'anonymize_ip' => true,
        // セッションタイムアウト（分）
        'session_timeout' => 30,
        // エンゲージメントタイム閾値（秒）
        'engagement_time_msec' => 10000,
    ],

    // カスタムイベント定義
    'events' => [
        // 店舗関連
        'view_restaurant' => 'view_restaurant',
        'create_post' => 'create_post',
        'add_to_favorites' => 'add_to_favorites',
        'remove_from_favorites' => 'remove_from_favorites',

        // 24節気関連
        'shop_limit_reached' => 'shop_limit_reached',
        'seasonal_activity' => 'seasonal_activity',

        // ユーザー行動
        'scroll_depth' => 'scroll_depth',
        'form_submit' => 'form_submit',
        'search' => 'search',

        // ソーシャル機能
        'follow_user' => 'follow_user',
        'unfollow_user' => 'unfollow_user',
        'comment_posted' => 'comment_posted',
    ],
];
