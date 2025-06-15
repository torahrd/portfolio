<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Tabelog+' }} - 美味しいの発見と共有</title>
    <meta name="description" content="Tabelog+で美味しいお店を発見し、グルメ体験を共有しましょう。">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

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
                            <span class="text-xl font-bold text-neutral-900">Tabelog+</span>
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
                            Tabelog+とは
                        </a>
                    </nav>

                    <!-- 認証ボタン -->
                    <div class="flex items-center space-x-3">
                        <x-atoms.button variant="ghost" href="{{ route('login') }}" size="sm">
                            ログイン
                        </x-atoms.button>
                        <x-atoms.button variant="primary" href="{{ route('register') }}" size="sm">
                            新規登録
                        </x-atoms.button>
                    </div>
                </div>
            </div>
        </header>

        <!-- メインコンテンツ -->
        <main class="flex-1">
            {{ $slot }}
        </main>

        <!-- ゲスト用フッター -->
        <footer class="bg-white border-t border-neutral-200 mt-auto">
            <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div>
                        <div class="flex items-center space-x-2 mb-4">
                            <div class="w-6 h-6 bg-primary-500 rounded flex items-center justify-center">
                                <span class="text-white font-bold text-sm">T+</span>
                            </div>
                            <span class="text-lg font-bold text-neutral-900">Tabelog+</span>
                        </div>
                        <p class="text-sm text-neutral-600 mb-4">
                            美味しいお店を発見し、グルメ体験を共有する新しいプラットフォーム
                        </p>
                        <div class="flex space-x-4">
                            <a href="#" class="text-neutral-400 hover:text-primary-500 transition-colors duration-200">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z" />
                                </svg>
                            </a>
                            <a href="#" class="text-neutral-400 hover:text-primary-500 transition-colors duration-200">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z" />
                                </svg>
                            </a>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-neutral-900 mb-4">サービス</h3>
                        <ul class="space-y-2 text-sm text-neutral-600">
                            <li><a href="{{ route('home') }}" class="hover:text-primary-500 transition-colors duration-200">ホーム</a></li>
                            <li><a href="{{ route('search') }}" class="hover:text-primary-500 transition-colors duration-200">店舗検索</a></li>
                            <li><a href="/about" class="hover:text-primary-500 transition-colors duration-200">Tabelog+とは</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-neutral-900 mb-4">サポート</h3>
                        <ul class="space-y-2 text-sm text-neutral-600">
                            <li><a href="/help" class="hover:text-primary-500 transition-colors duration-200">ヘルプ</a></li>
                            <li><a href="/contact" class="hover:text-primary-500 transition-colors duration-200">お問い合わせ</a></li>
                            <li><a href="/terms" class="hover:text-primary-500 transition-colors duration-200">利用規約</a></li>
                            <li><a href="/privacy" class="hover:text-primary-500 transition-colors duration-200">プライバシーポリシー</a></li>
                        </ul>
                    </div>
                </div>

                <div class="mt-8 pt-8 border-t border-neutral-200 text-center">
                    <p class="text-sm text-neutral-500">© 2025 Tabelog+. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>

    @stack('scripts')
</body>

</html>