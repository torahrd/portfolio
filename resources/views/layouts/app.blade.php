<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="auth-check" content="{{ auth()->check() ? 'true' : 'false' }}">
    @auth
    <meta name="user-id" content="{{ auth()->id() }}">
    @endauth

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        integrity="sha512-Avb2QiuDEEvB4bZJYdft2mNjVShBftLdPG8FJ0V7irTLQ8Uo0qcPxh4Plq7G5tGm0rU+1SPhVotteLpBERwTkw=="
        crossorigin="anonymous" referrerpolicy="no-referrer">

    <link rel="icon" type="image/x-icon" href="/favicon.ico">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="font-sans antialiased text-neutral-900 bg-gradient-to-br from-neutral-50 to-mocha-50"
    data-authenticated="{{ auth()->check() ? 'true' : 'false' }}"
    data-user-id="{{ auth()->check() ? auth()->id() : '' }}"
    data-csrf-token="{{ csrf_token() }}"
    data-locale="{{ app()->getLocale() }}">

    <!-- メインコンテンツ -->
    <div id="app">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @if (isset($header))
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
        @endif

        <!-- Page Content -->
        <main>
            {{ $slot ?? $__env->yieldContent('content') }}
        </main>
    </div>

    <!-- ★重要: 基本設定をJavaScriptで取得する関数 -->
    <script>
        // ★修正: シンプルな設定オブジェクト（@json使用せず）
        window.AppConfig = {
            isAuthenticated: document.body.getAttribute('data-authenticated') === 'true',
            userId: document.body.getAttribute('data-user-id') || null,
            csrfToken: document.body.getAttribute('data-csrf-token'),
            locale: document.body.getAttribute('data-locale')
        };

        // CSRF設定（axios用）
        if (window.axios) {
            window.axios.defaults.headers.common['X-CSRF-TOKEN'] = window.AppConfig.csrfToken;
        }

        // jQuery用のCSRF設定
        if (window.$) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': window.AppConfig.csrfToken
                }
            });
        }
    </script>

    <!-- スクロールアニメーション初期化 -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // スクロールアニメーション初期化
            initScrollAnimations();
        });

        function initScrollAnimations() {
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.animate-on-scroll, .animate-on-scroll-left, .animate-on-scroll-right, .animate-on-scroll-scale').forEach(el => {
                observer.observe(el);
            });
        }
    </script>
</body>

</html>