<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="お気に入りの店舗を発見・共有し、新しい美食体験を楽しみましょう。FoodieConnectで美食家のコミュニティに参加しよう。">
    <meta name="keywords" content="グルメ, レストラン, 食べ物, 共有, コミュニティ, 美食">
    <meta name="author" content="{{ config('app.name', 'FoodieConnect') }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:title" content="{{ config('app.name', 'FoodieConnect') }} - 美食体験を共有しよう">
    <meta property="og:description" content="お気に入りの店舗を発見・共有し、新しい美食体験を楽しみましょう。">
    <meta property="og:image" content="{{ asset('images/og-image.jpg') }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url('/') }}">
    <meta property="twitter:title" content="{{ config('app.name', 'FoodieConnect') }} - 美食体験を共有しよう">
    <meta property="twitter:description" content="お気に入りの店舗を発見・共有し、新しい美食体験を楽しみましょう。">
    <meta property="twitter:image" content="{{ asset('images/og-image.jpg') }}">

    <title>{{ config('app.name', 'FoodieConnect') }} - 美食体験を共有しよう</title>

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

    <!-- Structured Data -->
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "WebApplication",
            "name": "{{ config('app.name', 'FoodieConnect') }}",
            "description": "お気に入りの店舗を発見・共有し、新しい美食体験を楽しみましょう。",
            "url": "{{ url('/') }}",
            "applicationCategory": "SocialNetworking",
            "operatingSystem": "Web"
        }
    </script>
</head>

<body class="antialiased bg-gradient-to-br from-neutral-50 via-mocha-50 to-sage-50 text-neutral-900">

    <!-- Skip Link -->
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

        <!-- パーティクル効果 -->
        <div class="absolute inset-0 opacity-30" id="particles-container"></div>
    </div>

    <div class="relative min-h-screen flex flex-col">
        <!-- ナビゲーション -->
        <nav class="glass-nav fixed top-4 left-1/2 transform -translate-x-1/2 z-50 w-full max-w-4xl mx-4"
            x-data="{ open: false }">
            <div class="flex items-center justify-between px-6 py-4">
                <!-- ロゴ -->
                <a href="/" class="flex items-center gap-3 text-xl font-bold text-gradient-mocha">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-mocha-500 to-sage-500 flex items-center justify-center text-white shadow-lg">
                        <i class="fas fa-utensils text-lg"></i>
                    </div>
                    <span class="hidden sm:block">{{ config('app.name', 'FoodieConnect') }}</span>
                </a>

                <!-- デスクトップナビゲーション -->
                <div class="hidden md:flex items-center space-x-1">
                    <a href="{{ route('posts.index') }}" class="nav-link">
                        <i class="nav-link-icon fas fa-home"></i>
                        <span>投稿を見る</span>
                    </a>
                    @if (Route::has('login'))
                    @auth
                    <a href="{{ route('posts.create') }}" class="nav-link">
                        <i class="nav-link-icon fas fa-plus"></i>
                        <span>投稿作成</span>
                    </a>
                    <a href="{{ url('/dashboard') }}" class="btn btn-primary">
                        <i class="fas fa-tachometer-alt mr-2"></i>
                        ダッシュボード
                    </a>
                    @else
                    <a href="{{ route('login') }}" class="nav-link">
                        <i class="nav-link-icon fas fa-sign-in-alt"></i>
                        <span>ログイン</span>
                    </a>
                    @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn btn-primary">
                        <i class="fas fa-user-plus mr-2"></i>
                        <span>新規登録</span>
                    </a>
                    @endif
                    @endauth
                    @endif
                </div>

                <!-- モバイルメニューボタン -->
                <button @click="open = !open" class="md:hidden nav-link p-2">
                    <i class="fas fa-bars" x-show="!open"></i>
                    <i class="fas fa-times" x-show="open"></i>
                </button>
            </div>

            <!-- モバイルメニュー -->
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
                    <a href="{{ route('posts.index') }}" class="nav-link">
                        <i class="nav-link-icon fas fa-home"></i>
                        <span>投稿を見る</span>
                    </a>
                    @auth
                    <a href="{{ route('posts.create') }}" class="nav-link">
                        <i class="nav-link-icon fas fa-plus"></i>
                        <span>投稿作成</span>
                    </a>
                    <a href="{{ url('/dashboard') }}" class="nav-link">
                        <i class="nav-link-icon fas fa-tachometer-alt"></i>
                        <span>ダッシュボード</span>
                    </a>
                    @else
                    <a href="{{ route('login') }}" class="nav-link">
                        <i class="nav-link-icon fas fa-sign-in-alt"></i>
                        <span>ログイン</span>
                    </a>
                    <a href="{{ route('register') }}" class="nav-link">
                        <i class="nav-link-icon fas fa-user-plus"></i>
                        <span>新規登録</span>
                    </a>
                    @endauth
                </div>
            </div>
        </nav>

        <!-- メインコンテンツ -->
        <main id="main-content" class="flex-1" role="main">
            <!-- ヒーローセクション -->
            <section class="relative min-h-screen flex items-center justify-center px-4 py-20">
                <div class="container-responsive text-center">
                    <div class="max-w-4xl mx-auto">
                        <!-- メインタイトル -->
                        <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold text-gradient-mocha mb-6 animate-fade-in">
                            お気に入りの店舗を<br>
                            <span class="text-gradient-warm">発見・共有</span>しよう
                        </h1>

                        <!-- サブタイトル -->
                        <p class="text-lg md:text-xl lg:text-2xl text-neutral-600 mb-8 leading-relaxed max-w-3xl mx-auto animate-slide-up">
                            友達とお気に入りの店舗を共有し、新しいお店を発見しましょう。<br>
                            あなたの美食の旅をより豊かにするプラットフォームです。
                        </p>

                        <!-- CTA ボタン -->
                        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-12 animate-slide-up animation-delay-200">
                            @auth
                            <a href="{{ route('posts.index') }}" class="btn btn-primary btn-xl hover-lift">
                                <i class="fas fa-eye mr-2"></i>
                                投稿を見る
                            </a>
                            <a href="{{ route('posts.create') }}" class="btn btn-outline-primary btn-xl hover-lift">
                                <i class="fas fa-plus mr-2"></i>
                                投稿を作成
                            </a>
                            @else
                            <a href="{{ route('register') }}" class="btn btn-primary btn-xl hover-lift">
                                <i class="fas fa-rocket mr-2"></i>
                                今すぐ始める
                            </a>
                            <a href="{{ route('posts.index') }}" class="btn btn-outline-primary btn-xl hover-lift">
                                <i class="fas fa-eye mr-2"></i>
                                投稿を見る
                            </a>
                            @endauth
                        </div>

                        <!-- 統計情報 -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-3xl mx-auto animate-on-scroll">
                            <div class="glass-card p-6 text-center">
                                <div class="text-3xl font-bold text-mocha-600 mb-2">1,000+</div>
                                <div class="text-sm text-neutral-600">登録ユーザー</div>
                            </div>
                            <div class="glass-card p-6 text-center">
                                <div class="text-3xl font-bold text-sage-600 mb-2">5,000+</div>
                                <div class="text-sm text-neutral-600">共有された店舗</div>
                            </div>
                            <div class="glass-card p-6 text-center">
                                <div class="text-3xl font-bold text-electric-600 mb-2">10,000+</div>
                                <div class="text-sm text-neutral-600">投稿数</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- スクロールインジケーター -->
                <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
                    <div class="w-6 h-10 border-2 border-neutral-400 rounded-full flex justify-center">
                        <div class="w-1 h-3 bg-neutral-400 rounded-full mt-2 animate-pulse"></div>
                    </div>
                </div>
            </section>

            <!-- 機能紹介セクション -->
            <section class="py-20 bg-gradient-to-br from-white/50 to-mocha-50/50 backdrop-blur-sm">
                <div class="container-responsive">
                    <div class="text-center mb-16 animate-on-scroll">
                        <h2 class="text-3xl md:text-4xl font-bold text-gradient-mocha mb-6">
                            なぜFoodieConnectなのか
                        </h2>
                        <p class="text-lg text-neutral-600 leading-relaxed max-w-3xl mx-auto">
                            シンプルで使いやすいインターフェースで、お気に入りの店舗を管理し、<br>
                            友達と共有することができます。
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                        <!-- 機能1: 店舗管理 -->
                        <div class="glass-card p-8 text-center hover-lift animate-on-scroll">
                            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-mocha-500 to-mocha-600 flex items-center justify-center text-white mx-auto mb-6 shadow-lg">
                                <i class="fas fa-store text-2xl"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-neutral-900 mb-4">店舗情報の管理</h3>
                            <p class="text-neutral-600 leading-relaxed">
                                お気に入りの店舗の詳細情報、訪問記録、予算などを簡単に管理できます。
                            </p>
                        </div>

                        <!-- 機能2: 友達との共有 -->
                        <div class="glass-card p-8 text-center hover-lift animate-on-scroll" style="animation-delay: 0.1s;">
                            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-sage-500 to-sage-600 flex items-center justify-center text-white mx-auto mb-6 shadow-lg">
                                <i class="fas fa-users text-2xl"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-neutral-900 mb-4">友達との共有</h3>
                            <p class="text-neutral-600 leading-relaxed">
                                フォロー機能により、友達のおすすめ店舗を発見し、あなたの発見も共有できます。
                            </p>
                        </div>

                        <!-- 機能3: コメント機能 -->
                        <div class="glass-card p-8 text-center hover-lift animate-on-scroll" style="animation-delay: 0.2s;">
                            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-electric-500 to-electric-600 flex items-center justify-center text-white mx-auto mb-6 shadow-lg">
                                <i class="fas fa-comments text-2xl"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-neutral-900 mb-4">コメント機能</h3>
                            <p class="text-neutral-600 leading-relaxed">
                                投稿にコメントを残して、みんなでお店の情報を共有しましょう。
                            </p>
                        </div>

                        <!-- 機能4: レスポンシブ対応 -->
                        <div class="glass-card p-8 text-center hover-lift animate-on-scroll" style="animation-delay: 0.3s;">
                            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-coral-500 to-coral-600 flex items-center justify-center text-white mx-auto mb-6 shadow-lg">
                                <i class="fas fa-mobile-alt text-2xl"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-neutral-900 mb-4">レスポンシブ対応</h3>
                            <p class="text-neutral-600 leading-relaxed">
                                スマートフォン、タブレット、PCなど、どのデバイスからでも快適に利用できます。
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 使い方セクション -->
            <section class="py-20">
                <div class="container-responsive">
                    <div class="text-center mb-16 animate-on-scroll">
                        <h2 class="text-3xl md:text-4xl font-bold text-gradient-mocha mb-6">
                            簡単3ステップで始めよう
                        </h2>
                        <p class="text-lg text-neutral-600 leading-relaxed max-w-2xl mx-auto">
                            FoodieConnectの使い方はとても簡単。<br>
                            たった3つのステップで美食体験の共有を始められます。
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-12 max-w-5xl mx-auto">
                        <!-- ステップ1 -->
                        <div class="text-center animate-on-scroll">
                            <div class="relative mb-8">
                                <div class="w-24 h-24 rounded-full bg-gradient-to-br from-mocha-500 to-sage-500 flex items-center justify-center text-white text-2xl font-bold mx-auto shadow-2xl">
                                    1
                                </div>
                                <div class="absolute -top-2 -right-2 w-8 h-8 rounded-full bg-coral-500 flex items-center justify-center text-white text-sm animate-pulse">
                                    <i class="fas fa-star"></i>
                                </div>
                            </div>
                            <h3 class="text-xl font-semibold text-neutral-900 mb-4">アカウント作成</h3>
                            <p class="text-neutral-600 leading-relaxed">
                                無料でアカウントを作成し、<br>
                                あなたのプロフィールを設定しましょう。
                            </p>
                        </div>

                        <!-- ステップ2 -->
                        <div class="text-center animate-on-scroll" style="animation-delay: 0.2s;">
                            <div class="relative mb-8">
                                <div class="w-24 h-24 rounded-full bg-gradient-to-br from-sage-500 to-electric-500 flex items-center justify-center text-white text-2xl font-bold mx-auto shadow-2xl">
                                    2
                                </div>
                                <div class="absolute -top-2 -right-2 w-8 h-8 rounded-full bg-warning-500 flex items-center justify-center text-white text-sm animate-pulse">
                                    <i class="fas fa-plus"></i>
                                </div>
                            </div>
                            <h3 class="text-xl font-semibold text-neutral-900 mb-4">店舗を投稿</h3>
                            <p class="text-neutral-600 leading-relaxed">
                                お気に入りの店舗を投稿し、<br>
                                あなたの体験を共有しましょう。
                            </p>
                        </div>

                        <!-- ステップ3 -->
                        <div class="text-center animate-on-scroll" style="animation-delay: 0.4s;">
                            <div class="relative mb-8">
                                <div class="w-24 h-24 rounded-full bg-gradient-to-br from-electric-500 to-coral-500 flex items-center justify-center text-white text-2xl font-bold mx-auto shadow-2xl">
                                    3
                                </div>
                                <div class="absolute -top-2 -right-2 w-8 h-8 rounded-full bg-success-500 flex items-center justify-center text-white text-sm animate-pulse">
                                    <i class="fas fa-heart"></i>
                                </div>
                            </div>
                            <h3 class="text-xl font-semibold text-neutral-900 mb-4">コミュニティ参加</h3>
                            <p class="text-neutral-600 leading-relaxed">
                                他のユーザーをフォローし、<br>
                                美食コミュニティに参加しましょう。
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- CTA セクション -->
            <section class="py-20 bg-gradient-to-br from-mocha-500 to-sage-500 text-white relative overflow-hidden">
                <!-- 背景装飾 -->
                <div class="absolute inset-0 bg-black/20"></div>
                <div class="absolute -top-40 -right-40 w-80 h-80 rounded-full bg-white/10 blur-3xl"></div>
                <div class="absolute -bottom-40 -left-40 w-96 h-96 rounded-full bg-white/5 blur-3xl"></div>

                <div class="container-responsive relative z-10">
                    <div class="text-center max-w-3xl mx-auto animate-on-scroll">
                        <h2 class="text-3xl md:text-4xl font-bold mb-6">
                            今すぐ始めましょう
                        </h2>
                        <p class="text-lg mb-8 leading-relaxed opacity-90">
                            あなたの美食体験をコミュニティと共有し、<br>
                            新しい発見と出会いを楽しみませんか？
                        </p>
                        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                            @auth
                            <a href="{{ route('posts.create') }}" class="btn btn-lg bg-white text-mocha-600 hover:bg-neutral-100 hover:text-mocha-700 border-white hover:border-neutral-100">
                                <i class="fas fa-plus mr-2"></i>
                                投稿を作成
                            </a>
                            <a href="{{ route('posts.index') }}" class="btn btn-outline-primary btn-lg border-white text-white hover:bg-white hover:text-mocha-600">
                                <i class="fas fa-eye mr-2"></i>
                                投稿を見る
                            </a>
                            @else
                            <a href="{{ route('register') }}" class="btn btn-lg bg-white text-mocha-600 hover:bg-neutral-100 hover:text-mocha-700 border-white hover:border-neutral-100">
                                <i class="fas fa-rocket mr-2"></i>
                                無料で始める
                            </a>
                            <a href="{{ route('posts.index') }}" class="btn btn-outline-primary btn-lg border-white text-white hover:bg-white hover:text-mocha-600">
                                <i class="fas fa-eye mr-2"></i>
                                投稿を見る
                            </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <!-- フッター -->
        <footer class="glass-footer">
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
                            <li><a href="#" class="text-neutral-600 hover:text-mocha-600 transition-colors">API</a></li>
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

                    <!-- 会社情報 -->
                    <div>
                        <h3 class="font-semibold text-neutral-900 mb-4">会社情報</h3>
                        <ul class="space-y-2 text-sm">
                            <li><a href="#" class="text-neutral-600 hover:text-mocha-600 transition-colors">私たちについて</a></li>
                            <li><a href="#" class="text-neutral-600 hover:text-mocha-600 transition-colors">ブログ</a></li>
                            <li><a href="#" class="text-neutral-600 hover:text-mocha-600 transition-colors">採用情報</a></li>
                            <li><a href="#" class="text-neutral-600 hover:text-mocha-600 transition-colors">パートナー</a></li>
                        </ul>
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

    <!-- スクロール時アニメーション用スクリプト -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // スクロールアニメーション
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -100px 0px'
            };

            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.animate-on-scroll').forEach(el => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(30px)';
                observer.observe(el);
            });

            // .visible クラスのスタイル適用
            const style = document.createElement('style');
            style.textContent = `
                .animate-on-scroll.visible {
                    opacity: 1 !important;
                    transform: translateY(0) !important;
                    transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
                }
            `;
            document.head.appendChild(style);

            // パーティクル効果（軽量版）
            const particlesContainer = document.getElementById('particles-container');
            if (particlesContainer) {
                for (let i = 0; i < 50; i++) {
                    const particle = document.createElement('div');
                    particle.className = 'absolute w-1 h-1 bg-mocha-300 rounded-full opacity-20';
                    particle.style.left = Math.random() * 100 + '%';
                    particle.style.top = Math.random() * 100 + '%';
                    particle.style.animationDelay = Math.random() * 20 + 's';
                    particle.style.animation = 'float 20s infinite linear';
                    particlesContainer.appendChild(particle);
                }
            }
        });
    </script>
</body>

</html>