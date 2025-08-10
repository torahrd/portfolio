<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'TasteRetreat' }} - 美味しいの発見と共有</title>
    <meta name="description" content="TasteRetreatで美味しいお店を発見し、グルメ体験を共有しましょう。">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;600;700&display=swap" rel="stylesheet">

    @if(config('analytics.enabled'))
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('analytics.measurement_id') }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        
        // デフォルトの同意状態（Cookie同意前）
        gtag('consent', 'default', {
            'analytics_storage': 'denied',
            'ad_storage': 'denied',
            'wait_for_update': 500
        });
        
        gtag('js', new Date());
        
        gtag('config', '{{ config('analytics.measurement_id') }}', {
            'anonymize_ip': {{ config('analytics.tracking.anonymize_ip') ? 'true' : 'false' }},
            'debug_mode': {{ config('analytics.debug_mode') ? 'true' : 'false' }}
        });
    </script>
    @endif

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Google Maps API -->
    @if(config('services.google.maps_api_key'))
    <script>
        // グローバルなinitMap関数を定義
        window.initMap = function() {
            // この関数は各ページで必要に応じてオーバーライドされる
        }
    </script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&libraries=places&callback=initMap"></script>
    @endif

    <!-- Google Analytics 4 -->
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-XXXXXXXXXX"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-XXXXXXXXXX'); // 実際の測定IDに置き換えてください
    </script>

    @stack('head')
</head>

<body class="font-sans text-neutral-900 antialiased">
    <div class="min-h-screen flex flex-col bg-gradient-to-br from-cream-100 via-white to-neutral-50">
        <!-- ゲスト用ヘッダー -->
        <header class="bg-white/80 backdrop-blur-sm border-b border-neutral-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <!-- ロゴ -->
                    <div class="flex items-center">
                        <a href="{{ route('home') }}" class="flex items-center space-x-2">
                            <div class="w-8 h-8 bg-primary-500 rounded-lg flex items-center justify-center">
                                <span class="text-white font-bold text-lg">T+</span>
                            </div>
                            <span class="text-xl font-bold text-neutral-900">TasteRetreat</span>
                        </a>
                    </div>

                    <!-- ナビゲーション -->
                    <nav class="hidden md:flex items-center space-x-8">
                        <a href="{{ route('home') }}" class="text-neutral-600 hover:text-primary-500 transition-colors duration-200">
                            ホーム
                        </a>
                        <a href="{{ route('search') }}" class="text-neutral-600 hover:text-primary-500 transition-colors duration-200">
                            店舗検索
                        </a>
                        <a href="/about" class="text-neutral-600 hover:text-primary-500 transition-colors duration-200">
                            TasteRetreatとは
                        </a>
                    </nav>

                    <!-- 認証ボタン -->
                    <div class="flex items-center space-x-3">
                        <x-atoms.button-secondary href="{{ route('login') }}" size="sm">
                            ログイン
                        </x-atoms.button-secondary>
                        <x-atoms.button-primary href="{{ route('register') }}" size="sm">
                            新規登録
                        </x-atoms.button-primary>
                    </div>
                </div>
            </div>
        </header>

        <!-- メインコンテンツ -->
        <main class="flex-1">
            @yield('content')
        </main>

        <!-- ゲスト用フッター -->
        <x-organisms.footer type="default" />
    </div>

    @stack('scripts')
    <!-- Cookie同意バナー -->
    <x-cookie-consent />
</body>

</html>