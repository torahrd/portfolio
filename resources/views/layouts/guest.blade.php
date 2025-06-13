<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'FoodieConnect') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        integrity="sha512-Avb2QiuDEEvB4bZJYdft2mNjVShBftLdPG8FJ0V7irTLQ8Uo0qcPxh4Plq7G5tGm0rU+1SPhVotteLpBERwTkw=="
        crossorigin="anonymous" referrerpolicy="no-referrer">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="font-sans text-neutral-900 antialiased bg-gradient-to-br from-neutral-50 via-mocha-50 to-sage-50">

    <!-- Skip Link (アクセシビリティ) -->
    <a href="#main-content" class="skip-link">メインコンテンツへスキップ</a>

    <!-- 背景装飾 -->
    <div class="fixed inset-0 -z-10 overflow-hidden">
        <!-- グラデーション背景 -->
        <div class="absolute inset-0 bg-gradient-to-br from-neutral-50 via-mocha-50/30 to-sage-50/20"></div>

        <!-- フローティング装飾要素 -->
        <div class="absolute -top-40 -right-40 w-80 h-80 rounded-full bg-mocha-200/20 blur-3xl animate-float"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 rounded-full bg-sage-200/15 blur-3xl animate-float" style="animation-delay: -1s;"></div>
        <div class="absolute top-1/4 left-1/4 w-64 h-64 rounded-full bg-electric-200/10 blur-3xl animate-float" style="animation-delay: -2s;"></div>
        <div class="absolute bottom-1/4 right-1/4 w-72 h-72 rounded-full bg-coral-200/10 blur-3xl animate-float" style="animation-delay: -3s;"></div>

        <!-- 幾何学模様 -->
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-full h-full opacity-5">
            <svg viewBox="0 0 100 100" class="w-full h-full">
                <defs>
                    <pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse">
                        <circle cx="5" cy="5" r="1" fill="currentColor" opacity="0.1" />
                    </pattern>
                </defs>
                <rect width="100" height="100" fill="url(#grid)" />
            </svg>
        </div>
    </div>

    <div class="min-h-screen flex flex-col">
        <!-- ヘッダー -->
        <header class="glass-header">
            <div class="container-responsive">
                <div class="flex items-center justify-between py-4">
                    <!-- ロゴ・ブランド -->
                    <a href="/" class="flex items-center gap-3 text-xl font-bold text-gradient-mocha hover:text-mocha-700 transition-colors duration-200">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-mocha-500 to-sage-500 flex items-center justify-center text-white shadow-lg">
                            <i class="fas fa-utensils text-lg"></i>
                        </div>
                        <span>{{ config('app.name', 'FoodieConnect') }}</span>
                    </a>

                    <!-- ナビゲーションリンク -->
                    <nav class="hidden md:flex items-center space-x-1">
                        <a href="{{ route('posts.index') }}"
                            class="nav-link">
                            <i class="nav-link-icon fas fa-home"></i>
                            <span>投稿を見る</span>
                        </a>
                        <a href="{{ route('login') }}"
                            class="nav-link">
                            <i class="nav-link-icon fas fa-sign-in-alt"></i>
                            <span>ログイン</span>
                        </a>
                        <a href="{{ route('register') }}"
                            class="btn btn-primary">
                            <i class="fas fa-user-plus mr-2"></i>
                            <span>新規登録</span>
                        </a>
                    </nav>

                    <!-- モバイルメニューボタン -->
                    <button class="md:hidden nav-link p-2"
                        x-data="{ open: false }"
                        @click="open = !open"
                        aria-label="メニューを開く">
                        <i class="fas fa-bars text-lg" x-show="!open"></i>
                        <i class="fas fa-times text-lg" x-show="open"></i>

                        <!-- モバイルメニュー -->
                        <div x-show="open"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 -translate-y-2"
                            x-transition:enter-end="opacity-1 translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-1 translate-y-0"
                            x-transition:leave-end="opacity-0 -translate-y-2"
                            @click.away="open = false"
                            class="absolute top-full left-0 right-0 glass-medium rounded-2xl mt-4 p-4 z-50">

                            <div class="space-y-2">
                                <a href="{{ route('posts.index') }}" class="nav-link">
                                    <i class="nav-link-icon fas fa-home"></i>
                                    <span>投稿を見る</span>
                                </a>
                                <a href="{{ route('login') }}" class="nav-link">
                                    <i class="nav-link-icon fas fa-sign-in-alt"></i>
                                    <span>ログイン</span>
                                </a>
                                <a href="{{ route('register') }}" class="nav-link">
                                    <i class="nav-link-icon fas fa-user-plus"></i>
                                    <span>新規登録</span>
                                </a>
                            </div>
                        </div>
                    </button>
                </div>
            </div>
        </header>

        <!-- メインコンテンツ -->
        <main id="main-content" class="flex-1 flex flex-col justify-center items-center px-4 py-12" role="main">
            <div class="w-full max-w-md">
                <!-- ロゴセクション -->
                <div class="text-center mb-8 animate-fade-in">
                    <a href="/" class="inline-block">
                        <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-mocha-500 to-sage-500 flex items-center justify-center text-white shadow-2xl mx-auto mb-4 hover:scale-105 transition-transform duration-300">
                            <i class="fas fa-utensils text-3xl"></i>
                        </div>
                    </a>
                    <h1 class="text-2xl font-bold text-gradient-mocha mb-2">{{ config('app.name', 'FoodieConnect') }}</h1>
                    <p class="text-neutral-600 text-sm">お気に入りの店舗を発見・共有しよう</p>
                </div>

                <!-- フォームカード -->
                <div class="glass-card p-8 shadow-2xl animate-slide-up">
                    {{ $slot }}
                </div>

                <!-- 追加リンク -->
                <div class="text-center mt-6 space-y-2 animate-slide-up animation-delay-200">
                    @if (!request()->routeIs('register'))
                    <p class="text-sm text-neutral-600">
                        アカウントをお持ちでない方は
                        <a href="{{ route('register') }}"
                            class="text-mocha-600 hover:text-mocha-700 font-semibold transition-colors duration-200">
                            こちら
                        </a>
                    </p>
                    @endif

                    @if (!request()->routeIs('login'))
                    <p class="text-sm text-neutral-600">
                        すでにアカウントをお持ちの方は
                        <a href="{{ route('login') }}"
                            class="text-mocha-600 hover:text-mocha-700 font-semibold transition-colors duration-200">
                            こちら
                        </a>
                    </p>
                    @endif

                    <p class="text-sm text-neutral-600">
                        <a href="{{ route('posts.index') }}"
                            class="text-sage-600 hover:text-sage-700 font-semibold transition-colors duration-200">
                            <i class="fas fa-eye mr-1"></i>
                            ログインせずに投稿を見る
                        </a>
                    </p>
                </div>
            </div>

            <!-- 機能紹介セクション -->
            <div class="w-full max-w-4xl mt-16 animate-on-scroll">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gradient-mocha mb-4">なぜFoodieConnectなのか</h2>
                    <p class="text-neutral-600 text-lg leading-relaxed max-w-2xl mx-auto">
                        美食家のコミュニティで、お気に入りの店舗を発見し、<br>
                        あなたの素敵な発見を多くの人と共有しましょう。
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- 機能1 -->
                    <div class="glass-card p-6 text-center hover-lift animate-on-scroll" style="animation-delay: 0.1s;">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-mocha-400 to-mocha-600 flex items-center justify-center text-white mx-auto mb-4">
                            <i class="fas fa-store text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-neutral-900 mb-3">店舗を発見</h3>
                        <p class="text-neutral-600 text-sm leading-relaxed">
                            友達やコミュニティメンバーが共有する<br>
                            隠れた名店を発見できます
                        </p>
                    </div>

                    <!-- 機能2 -->
                    <div class="glass-card p-6 text-center hover-lift animate-on-scroll" style="animation-delay: 0.2s;">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-sage-400 to-sage-600 flex items-center justify-center text-white mx-auto mb-4">
                            <i class="fas fa-share-alt text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-neutral-900 mb-3">体験を共有</h3>
                        <p class="text-neutral-600 text-sm leading-relaxed">
                            あなたのお気に入りの店舗や<br>
                            美味しい体験を簡単に共有
                        </p>
                    </div>

                    <!-- 機能3 -->
                    <div class="glass-card p-6 text-center hover-lift animate-on-scroll" style="animation-delay: 0.3s;">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-electric-400 to-electric-600 flex items-center justify-center text-white mx-auto mb-4">
                            <i class="fas fa-users text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-neutral-900 mb-3">つながりを作る</h3>
                        <p class="text-neutral-600 text-sm leading-relaxed">
                            同じ趣味を持つ人々と<br>
                            美食を通じたコミュニティを形成
                        </p>
                    </div>
                </div>
            </div>
        </main>

        <!-- フッター -->
        <footer class="glass-footer mt-16">
            <div class="container-responsive">
                <div class="flex flex-col md:flex-row justify-between items-center py-6">
                    <div class="flex items-center gap-3 mb-4 md:mb-0">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-mocha-500 to-sage-500 flex items-center justify-center text-white">
                            <i class="fas fa-utensils"></i>
                        </div>
                        <span class="font-bold text-mocha-700">{{ config('app.name', 'FoodieConnect') }}</span>
                    </div>

                    <p class="text-sm text-neutral-600 text-center md:text-right">
                        © {{ date('Y') }} {{ config('app.name', 'FoodieConnect') }}. All rights reserved.<br>
                        <span class="text-xs text-neutral-500">Made with <i class="fas fa-heart text-coral-500"></i> in Japan</span>
                    </p>
                </div>
            </div>
        </footer>
    </div>

    <!-- スクロールアニメーション -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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

            // スクロールアニメーション対象要素を監視
            document.querySelectorAll('.animate-on-scroll').forEach(el => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(30px)';
                observer.observe(el);
            });

            // .visible クラスが追加されたときのスタイル
            const style = document.createElement('style');
            style.textContent = `
                .animate-on-scroll.visible {
                    opacity: 1 !important;
                    transform: translateY(0) !important;
                    transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
                }
            `;
            document.head.appendChild(style);
        });
    </script>
</body>

</html>