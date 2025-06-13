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

    <!-- Font Awesome (アイコン用) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        integrity="sha512-Avb2QiuDEEvB4bZJYdft2mNjVShBftLdPG8FJ0V7irTLQ8Uo0qcPxh4Plq7G5tGm0rU+1SPhVotteLpBERwTkw=="
        crossorigin="anonymous" referrerpolicy="no-referrer">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico">

    <!-- Scripts (Tailwind + Alpine.js) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js (軽量なJavaScriptフレームワーク) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- スクロールアニメーション用スクリプト -->
    <script>
        // スクロール時のアニメーション制御
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
            document.querySelectorAll('.animate-on-scroll, .animate-on-scroll-left, .animate-on-scroll-right, .animate-on-scroll-scale').forEach(el => {
                observer.observe(el);
            });
        });
    </script>
</head>

<body class="font-sans antialiased text-neutral-900 bg-gradient-to-br from-neutral-50 to-mocha-50"
    data-authenticated="{{ auth()->check() ? 'true' : 'false' }}"
    @auth data-user-id="{{ auth()->id() }}" @endauth>

    <!-- Skip Link (アクセシビリティ) -->
    <a href="#main-content" class="skip-link">メインコンテンツへスキップ</a>

    <!-- メイン背景装飾 -->
    <div class="fixed inset-0 -z-10 overflow-hidden">
        <div class="absolute -top-40 -right-40 w-80 h-80 rounded-full bg-mocha-200/20 blur-3xl animate-float"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 rounded-full bg-sage-200/15 blur-3xl animate-float" style="animation-delay: -1s;"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-64 h-64 rounded-full bg-electric-200/10 blur-3xl animate-float" style="animation-delay: -2s;"></div>
    </div>

    <div class="min-h-screen flex flex-col">
        <!-- メインナビゲーション -->
        <nav class="glass-nav sticky top-0 z-40 transition-all duration-300"
            x-data="{ open: false, scrolled: false }"
            x-init="
                 window.addEventListener('scroll', () => {
                     scrolled = window.scrollY > 50;
                 });
             "
            :class="{ 'glass-strong': scrolled }">

            <div class="container-responsive">
                <div class="flex items-center justify-between h-16">
                    <!-- ロゴ・ブランド -->
                    <div class="flex items-center">
                        <a href="{{ route('posts.index') }}"
                            class="flex items-center gap-3 text-xl font-bold text-gradient-mocha hover:text-mocha-700 transition-colors duration-200">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-mocha-500 to-sage-500 flex items-center justify-center text-white shadow-lg">
                                <i class="fas fa-utensils text-lg"></i>
                            </div>
                            <span class="hidden sm:block">{{ config('app.name', 'FoodieConnect') }}</span>
                        </a>
                    </div>

                    <!-- デスクトップナビゲーション -->
                    <div class="hidden md:flex items-center space-x-1">
                        <a href="{{ route('posts.index') }}"
                            class="nav-link {{ request()->routeIs('posts.index') ? 'active' : '' }}">
                            <i class="nav-link-icon fas fa-home"></i>
                            <span>ホーム</span>
                        </a>

                        @auth
                        <a href="{{ route('posts.create') }}"
                            class="nav-link {{ request()->routeIs('posts.create') ? 'active' : '' }}">
                            <i class="nav-link-icon fas fa-plus"></i>
                            <span>投稿作成</span>
                        </a>

                        <a href="{{ route('profile.show', auth()->user()) }}"
                            class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                            <i class="nav-link-icon fas fa-user"></i>
                            <span>プロフィール</span>
                        </a>
                        @endauth
                    </div>

                    <!-- ユーザーメニュー・認証ボタン -->
                    <div class="flex items-center space-x-2">
                        @auth
                        <!-- 通知ボタン -->
                        <div class="relative" x-data="{ showNotifications: false }">
                            <button @click="showNotifications = !showNotifications"
                                class="nav-link p-2 relative">
                                <i class="fas fa-bell text-lg"></i>
                                <!-- 通知バッジ（未読通知がある場合） -->
                                <span class="absolute -top-1 -right-1 w-5 h-5 bg-coral-500 text-white text-xs rounded-full flex items-center justify-center animate-pulse">
                                    3
                                </span>
                            </button>

                            <!-- 通知ドロップダウン -->
                            <div x-show="showNotifications"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 scale-95"
                                x-transition:enter-end="opacity-1 scale-100"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-1 scale-100"
                                x-transition:leave-end="opacity-0 scale-95"
                                @click.away="showNotifications = false"
                                class="glass-dropdown absolute right-0 mt-2 w-80 py-2 z-50">
                                <div class="px-4 py-2 border-b border-neutral-100">
                                    <h3 class="font-semibold text-neutral-900">通知</h3>
                                </div>
                                <div class="max-h-64 overflow-y-auto">
                                    <!-- 通知アイテム（サンプル） -->
                                    <a href="#" class="glass-dropdown-item flex items-start gap-3">
                                        <i class="fas fa-heart text-coral-500 mt-1"></i>
                                        <div>
                                            <p class="text-sm"><strong>田中さん</strong>があなたの投稿にいいねしました</p>
                                            <p class="text-xs text-neutral-500">2分前</p>
                                        </div>
                                    </a>
                                    <a href="#" class="glass-dropdown-item flex items-start gap-3">
                                        <i class="fas fa-comment text-electric-500 mt-1"></i>
                                        <div>
                                            <p class="text-sm"><strong>佐藤さん</strong>があなたの投稿にコメントしました</p>
                                            <p class="text-xs text-neutral-500">10分前</p>
                                        </div>
                                    </a>
                                    <a href="#" class="glass-dropdown-item flex items-start gap-3">
                                        <i class="fas fa-user-plus text-sage-500 mt-1"></i>
                                        <div>
                                            <p class="text-sm"><strong>山田さん</strong>があなたをフォローしました</p>
                                            <p class="text-xs text-neutral-500">1時間前</p>
                                        </div>
                                    </a>
                                </div>
                                <div class="px-4 py-2 border-t border-neutral-100">
                                    <a href="#" class="text-sm text-mocha-600 hover:text-mocha-700">すべての通知を見る</a>
                                </div>
                            </div>
                        </div>

                        <!-- ユーザーメニュー -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open"
                                class="flex items-center gap-2 nav-link">
                                <img src="{{ auth()->user()->avatar_url }}"
                                    alt="{{ auth()->user()->name }}"
                                    class="w-8 h-8 rounded-full object-cover border-2 border-mocha-300">
                                <span class="hidden sm:block">{{ auth()->user()->name }}</span>
                                <i class="fas fa-chevron-down text-xs transition-transform duration-200"
                                    :class="{ 'rotate-180': open }"></i>
                            </button>

                            <!-- ユーザードロップダウン -->
                            <div x-show="open"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 scale-95"
                                x-transition:enter-end="opacity-1 scale-100"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-1 scale-100"
                                x-transition:leave-end="opacity-0 scale-95"
                                @click.away="open = false"
                                class="glass-dropdown absolute right-0 mt-2 w-48 py-2 z-50">

                                <a href="{{ route('profile.show', auth()->user()) }}"
                                    class="glass-dropdown-item">
                                    <i class="fas fa-user w-4"></i>
                                    プロフィール
                                </a>
                                <a href="{{ route('profile.edit') }}"
                                    class="glass-dropdown-item">
                                    <i class="fas fa-cog w-4"></i>
                                    設定
                                </a>
                                <div class="border-t border-neutral-100 my-1"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="glass-dropdown-item w-full text-left">
                                        <i class="fas fa-sign-out-alt w-4"></i>
                                        ログアウト
                                    </button>
                                </form>
                            </div>
                        </div>
                        @else
                        <!-- ゲストユーザー用ボタン -->
                        <a href="{{ route('login') }}" class="btn-ghost">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            ログイン
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-primary">
                            <i class="fas fa-user-plus mr-2"></i>
                            新規登録
                        </a>
                        @endauth

                        <!-- モバイルメニューボタン -->
                        <button @click="open = !open"
                            class="md:hidden nav-link p-2"
                            aria-label="メニューを開く">
                            <i class="fas fa-bars text-lg" x-show="!open"></i>
                            <i class="fas fa-times text-lg" x-show="open"></i>
                        </button>
                    </div>
                </div>

                <!-- モバイルナビゲーション -->
                <div x-show="open"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-2"
                    x-transition:enter-end="opacity-1 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-1 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-2"
                    @click.away="open = false"
                    class="md:hidden glass-medium rounded-2xl mt-4 p-4">

                    <div class="space-y-2">
                        <a href="{{ route('posts.index') }}"
                            class="nav-link {{ request()->routeIs('posts.index') ? 'active' : '' }}">
                            <i class="nav-link-icon fas fa-home"></i>
                            <span>ホーム</span>
                        </a>

                        @auth
                        <a href="{{ route('posts.create') }}"
                            class="nav-link {{ request()->routeIs('posts.create') ? 'active' : '' }}">
                            <i class="nav-link-icon fas fa-plus"></i>
                            <span>投稿作成</span>
                        </a>

                        <a href="{{ route('profile.show', auth()->user()) }}"
                            class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                            <i class="nav-link-icon fas fa-user"></i>
                            <span>プロフィール</span>
                        </a>

                        <div class="border-t border-neutral-200 my-2"></div>

                        <a href="{{ route('profile.edit') }}"
                            class="nav-link">
                            <i class="nav-link-icon fas fa-cog"></i>
                            <span>設定</span>
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="nav-link w-full text-left">
                                <i class="nav-link-icon fas fa-sign-out-alt"></i>
                                <span>ログアウト</span>
                            </button>
                        </form>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- メインコンテンツエリア -->
        <main id="main-content" class="flex-1" role="main">
            <!-- ページヘッダー（必要に応じて表示） -->
            @if (isset($header))
            <header class="glass-card mx-4 mt-4 p-6 animate-slide-down">
                <div class="container-responsive">
                    {{ $header }}
                </div>
            </header>
            @endif

            <!-- メッセージ表示エリア -->
            <div id="flash-messages" class="container-responsive mt-4">
                @if (session('success'))
                <div class="alert alert-success animate-slide-down"
                    x-data="{ show: true }"
                    x-show="show"
                    x-init="setTimeout(() => show = false, 5000)">
                    <i class="alert-icon fas fa-check-circle"></i>
                    <div class="alert-content">
                        {{ session('success') }}
                    </div>
                    <button @click="show = false" class="alert-close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                @endif

                @if (session('error'))
                <div class="alert alert-error animate-slide-down"
                    x-data="{ show: true }"
                    x-show="show"
                    x-init="setTimeout(() => show = false, 5000)">
                    <i class="alert-icon fas fa-exclamation-circle"></i>
                    <div class="alert-content">
                        {{ session('error') }}
                    </div>
                    <button @click="show = false" class="alert-close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                @endif

                @if (session('warning'))
                <div class="alert alert-warning animate-slide-down"
                    x-data="{ show: true }"
                    x-show="show"
                    x-init="setTimeout(() => show = false, 5000)">
                    <i class="alert-icon fas fa-exclamation-triangle"></i>
                    <div class="alert-content">
                        {{ session('warning') }}
                    </div>
                    <button @click="show = false" class="alert-close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                @endif
            </div>

            <!-- ページコンテンツ -->
            <div class="container-responsive py-6">
                {{ $slot }}
            </div>
        </main>

        <!-- フッター -->
        <footer class="glass-footer mt-auto">
            <div class="container-responsive">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                    <!-- ブランド情報 -->
                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-mocha-500 to-sage-500 flex items-center justify-center text-white">
                                <i class="fas fa-utensils"></i>
                            </div>
                            <span class="font-bold text-mocha-700">{{ config('app.name', 'FoodieConnect') }}</span>
                        </div>
                        <p class="text-sm text-neutral-600 leading-relaxed">
                            お気に入りの店舗を発見・共有し、<br>
                            新しい美食体験を楽しみましょう。
                        </p>
                    </div>

                    <!-- サービス -->
                    <div>
                        <h3 class="font-semibold text-neutral-900 mb-4">サービス</h3>
                        <ul class="space-y-2 text-sm">
                            <li><a href="{{ route('posts.index') }}" class="text-neutral-600 hover:text-mocha-600 transition-colors">投稿を見る</a></li>
                            @auth
                            <li><a href="{{ route('posts.create') }}" class="text-neutral-600 hover:text-mocha-600 transition-colors">投稿を作成</a></li>
                            @else
                            <li><a href="{{ route('register') }}" class="text-neutral-600 hover:text-mocha-600 transition-colors">新規登録</a></li>
                            @endauth
                        </ul>
                    </div>

                    <!-- サポート -->
                    <div>
                        <h3 class="font-semibold text-neutral-900 mb-4">サポート</h3>
                        <ul class="space-y-2 text-sm">
                            <li><a href="#" class="text-neutral-600 hover:text-mocha-600 transition-colors">ヘルプ</a></li>
                            <li><a href="#" class="text-neutral-600 hover:text-mocha-600 transition-colors">お問い合わせ</a></li>
                            <li><a href="#" class="text-neutral-600 hover:text-mocha-600 transition-colors">プライバシーポリシー</a></li>
                            <li><a href="#" class="text-neutral-600 hover:text-mocha-600 transition-colors">利用規約</a></li>
                        </ul>
                    </div>

                    <!-- SNS -->
                    <div>
                        <h3 class="font-semibold text-neutral-900 mb-4">フォローする</h3>
                        <div class="flex space-x-3">
                            <a href="#" class="w-10 h-10 rounded-full bg-gradient-to-br from-mocha-400 to-sage-400 flex items-center justify-center text-white hover:from-mocha-500 hover:to-sage-500 transition-all duration-200 hover:-translate-y-0.5">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="w-10 h-10 rounded-full bg-gradient-to-br from-electric-400 to-coral-400 flex items-center justify-center text-white hover:from-electric-500 hover:to-coral-500 transition-all duration-200 hover:-translate-y-0.5">
                                <i class="fab fa-facebook"></i>
                            </a>
                            <a href="#" class="w-10 h-10 rounded-full bg-gradient-to-br from-coral-400 to-mocha-400 flex items-center justify-center text-white hover:from-coral-500 hover:to-mocha-500 transition-all duration-200 hover:-translate-y-0.5">
                                <i class="fab fa-instagram"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- コピーライト -->
                <div class="border-t border-neutral-200 pt-6 flex flex-col md:flex-row justify-between items-center">
                    <p class="text-sm text-neutral-600">
                        © {{ date('Y') }} {{ config('app.name', 'FoodieConnect') }}. All rights reserved.
                    </p>
                    <p class="text-xs text-neutral-500 mt-2 md:mt-0">
                        Made with <i class="fas fa-heart text-coral-500"></i> in Japan
                    </p>
                </div>
            </div>
        </footer>
    </div>

    <!-- フローティングアクションボタン（モバイル用） -->
    @auth
    <a href="{{ route('posts.create') }}"
        class="btn-floating md:hidden"
        aria-label="新しい投稿を作成">
        <i class="fas fa-plus text-lg"></i>
    </a>
    @endauth

    <!-- トップに戻るボタン -->
    <button id="scroll-to-top"
        class="fixed bottom-6 left-6 w-12 h-12 rounded-full bg-neutral-600/80 text-white backdrop-blur-md hidden hover:bg-neutral-700/90 transition-all duration-200 z-40"
        onclick="window.scrollTo({top: 0, behavior: 'smooth'})"
        aria-label="ページの先頭に戻る">
        <i class="fas fa-arrow-up"></i>
    </button>

    <script>
        // スクロールトップボタンの表示制御
        window.addEventListener('scroll', function() {
            const scrollButton = document.getElementById('scroll-to-top');
            if (window.scrollY > 300) {
                scrollButton.classList.remove('hidden');
                scrollButton.classList.add('animate-fade-in');
            } else {
                scrollButton.classList.add('hidden');
                scrollButton.classList.remove('animate-fade-in');
            }
        });
    </script>
</body>

</html>