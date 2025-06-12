<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased dark-mode-bg">
    <div class="relative min-h-screen flex flex-col selection:bg-primary-500 selection:text-white">
        <!-- ナビゲーション -->
        <nav class="flex items-center justify-between p-6 lg:px-8">
            <div class="flex lg:flex-1">
                <a href="/" class="-m-1.5 p-1.5">
                    <span class="text-2xl font-bold text-primary-600">{{ config('app.name', 'Laravel') }}</span>
                </a>
            </div>
            <div class="flex lg:flex-1 lg:justify-end space-x-4">
                @if (Route::has('login'))
                @auth
                <a href="{{ url('/dashboard') }}" class="btn btn-primary">
                    ダッシュボード
                </a>
                @else
                <a href="{{ route('login') }}" class="btn btn-outline-primary">
                    ログイン
                </a>

                @if (Route::has('register'))
                <a href="{{ route('register') }}" class="btn btn-primary">
                    新規登録
                </a>
                @endif
                @endauth
                @endif
            </div>
        </nav>

        <!-- メインコンテンツ -->
        <main class="flex-1">
            <!-- ヒーローセクション -->
            <section class="relative overflow-hidden bg-gradient-to-br from-primary-50 to-purple-50 py-24 sm:py-32">
                <div class="mx-auto max-w-7xl px-6 lg:px-8">
                    <div class="mx-auto max-w-2xl text-center">
                        <h1 class="text-4xl font-bold tracking-tight text-gray-900 sm:text-6xl animate-fade-in">
                            お気に入りの店舗を
                            <span class="text-primary-600">発見・共有</span>
                            しよう
                        </h1>
                        <p class="mt-6 text-lg leading-8 text-gray-600 animate-slide-up">
                            友達とお気に入りの店舗を共有し、新しいお店を発見しましょう。
                            あなたの美食の旅をより豊かにするプラットフォームです。
                        </p>
                        <div class="mt-10 flex items-center justify-center gap-x-6 animate-slide-up">
                            @auth
                            <a href="{{ route('posts.index') }}" class="btn btn-primary btn-lg">
                                投稿を見る
                            </a>
                            <a href="{{ route('posts.create') }}" class="btn btn-outline-primary btn-lg">
                                投稿を作成
                            </a>
                            @else
                            <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
                                今すぐ始める
                            </a>
                            <a href="{{ route('posts.index') }}" class="btn btn-outline-primary btn-lg">
                                投稿を見る
                            </a>
                            @endauth
                        </div>
                    </div>
                </div>

                <!-- 装飾的背景 -->
                <div class="absolute inset-x-0 top-[calc(100%-13rem)] -z-10 transform-gpu overflow-hidden blur-3xl sm:top-[calc(100%-30rem)]">
                    <div class="relative left-[calc(50%+3rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 bg-gradient-to-tr from-primary-400 to-purple-400 opacity-30 sm:left-[calc(50%+36rem)] sm:w-[72.1875rem]"
                        style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"></div>
                </div>
            </section>

            <!-- 機能紹介セクション -->
            <section class="py-24 sm:py-32">
                <div class="mx-auto max-w-7xl px-6 lg:px-8">
                    <div class="mx-auto max-w-2xl lg:text-center">
                        <h2 class="text-base font-semibold leading-7 text-primary-600">機能紹介</h2>
                        <p class="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
                            あなたの美食体験をより豊かに
                        </p>
                        <p class="mt-6 text-lg leading-8 text-gray-600">
                            シンプルで使いやすいインターフェースで、お気に入りの店舗を管理し、
                            友達と共有することができます。
                        </p>
                    </div>

                    <div class="mx-auto mt-16 max-w-2xl sm:mt-20 lg:mt-24 lg:max-w-4xl">
                        <dl class="grid max-w-xl grid-cols-1 gap-x-8 gap-y-10 lg:max-w-none lg:grid-cols-2 lg:gap-y-16">
                            <div class="relative pl-16 animate-on-scroll">
                                <dt class="text-base font-semibold leading-7 text-gray-900">
                                    <div class="absolute left-0 top-0 flex h-10 w-10 items-center justify-center rounded-lg bg-primary-600">
                                        <i class="fas fa-store text-white"></i>
                                    </div>
                                    店舗情報の管理
                                </dt>
                                <dd class="mt-2 text-base leading-7 text-gray-600">
                                    お気に入りの店舗の詳細情報、訪問記録、予算などを簡単に管理できます。
                                </dd>
                            </div>

                            <div class="relative pl-16 animate-on-scroll">
                                <dt class="text-base font-semibold leading-7 text-gray-900">
                                    <div class="absolute left-0 top-0 flex h-10 w-10 items-center justify-center rounded-lg bg-primary-600">
                                        <i class="fas fa-users text-white"></i>
                                    </div>
                                    友達との共有
                                </dt>
                                <dd class="mt-2 text-base leading-7 text-gray-600">
                                    フォロー機能により、友達のおすすめ店舗を発見し、あなたの発見も共有できます。
                                </dd>
                            </div>

                            <div class="relative pl-16 animate-on-scroll">
                                <dt class="text-base font-semibold leading-7 text-gray-900">
                                    <div class="absolute left-0 top-0 flex h-10 w-10 items-center justify-center rounded-lg bg-primary-600">
                                        <i class="fas fa-comments text-white"></i>
                                    </div>
                                    コメント機能
                                </dt>
                                <dd class="mt-2 text-base leading-7 text-gray-600">
                                    投稿にコメントを残して、みんなでお店の情報を共有しましょう。
                                </dd>
                            </div>

                            <div class="relative pl-16 animate-on-scroll">
                                <dt class="text-base font-semibold leading-7 text-gray-900">
                                    <div class="absolute left-0 top-0 flex h-10 w-10 items-center justify-center rounded-lg bg-primary-600">
                                        <i class="fas fa-mobile-alt text-white"></i>
                                    </div>
                                    レスポンシブ対応
                                </dt>
                                <dd class="mt-2 text-base leading-7 text-gray-600">
                                    スマートフォン、タブレット、PCなど、どのデバイスからでも快適に利用できます。
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </section>

            <!-- CTA セクション -->
            <section class="bg-primary-600">
                <div class="px-6 py-24 sm:px-6 sm:py-32 lg:px-8">
                    <div class="mx-auto max-w-2xl text-center">
                        <h2 class="text-3xl font-bold tracking-tight text-white sm:text-4xl">
                            今すぐ始めましょう
                        </h2>
                        <p class="mx-auto mt-6 max-w-xl text-lg leading-8 text-primary-200">
                            無料でアカウントを作成して、あなたの美食の旅を記録し、友達と共有しましょう。
                        </p>
                        <div class="mt-10 flex items-center justify-center gap-x-6">
                            @guest
                            <a href="{{ route('register') }}" class="btn bg-white text-primary-600 hover:bg-gray-100">
                                無料で始める
                            </a>
                            <a href="{{ route('login') }}" class="btn btn-outline text-white border-white hover:bg-white hover:text-primary-600">
                                ログイン
                            </a>
                            @else
                            <a href="{{ route('posts.create') }}" class="btn bg-white text-primary-600 hover:bg-gray-100">
                                投稿を作成
                            </a>
                            <a href="{{ route('posts.index') }}" class="btn btn-outline text-white border-white hover:bg-white hover:text-primary-600">
                                投稿を見る
                            </a>
                            @endguest
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <!-- フッター -->
        <footer class="bg-gray-50 py-12">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="flex flex-col items-center justify-between sm:flex-row">
                    <div class="flex items-center space-x-4">
                        <span class="text-2xl font-bold text-primary-600">{{ config('app.name', 'Laravel') }}</span>
                    </div>
                    <div class="mt-4 flex space-x-6 sm:mt-0">
                        <p class="text-sm text-gray-500">
                            Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
                        </p>
                    </div>
                </div>
                <div class="mt-8 border-t border-gray-200 pt-8 text-center">
                    <p class="text-sm text-gray-500">
                        &copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.
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

            document.querySelectorAll('.animate-on-scroll').forEach(el => {
                observer.observe(el);
            });
        });
    </script>
</body>

</html>