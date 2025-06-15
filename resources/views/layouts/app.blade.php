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

    <!-- PWA対応 -->
    <meta name="theme-color" content="#D64045">
    <link rel="manifest" href="/manifest.json">

    @stack('head')
</head>

<body class="font-sans antialiased bg-neutral-50 text-neutral-900">
    <div class="min-h-screen flex flex-col">
        <!-- ヘッダー -->
        <x-organisms.header
            :show-search="!isset($hideSearch) || !$hideSearch"
            :transparent="isset($transparentHeader) && $transparentHeader" />

        <!-- メインコンテンツ -->
        <main class="flex-1">
            @if (isset($header))
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
            @endif

            {{ $slot }}
        </main>

        <!-- フッター -->
        <footer class="bg-white border-t border-neutral-200 mt-auto">
            <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                    <div>
                        <h3 class="text-sm font-semibold text-neutral-900 mb-4">サービス</h3>
                        <ul class="space-y-2 text-sm text-neutral-600">
                            <li><a href="{{ route('home') }}" class="hover:text-primary-500 transition-colors duration-200">ホーム</a></li>
                            <li><a href="{{ route('search') }}" class="hover:text-primary-500 transition-colors duration-200">店舗検索</a></li>
                            <li><a href="{{ route('posts.create') }}" class="hover:text-primary-500 transition-colors duration-200">投稿作成</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-neutral-900 mb-4">サポート</h3>
                        <ul class="space-y-2 text-sm text-neutral-600">
                            <li><a href="/help" class="hover:text-primary-500 transition-colors duration-200">ヘルプ</a></li>
                            <li><a href="/contact" class="hover:text-primary-500 transition-colors duration-200">お問い合わせ</a></li>
                            <li><a href="/faq" class="hover:text-primary-500 transition-colors duration-200">よくある質問</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-neutral-900 mb-4">法的情報</h3>
                        <ul class="space-y-2 text-sm text-neutral-600">
                            <li><a href="/terms" class="hover:text-primary-500 transition-colors duration-200">利用規約</a></li>
                            <li><a href="/privacy" class="hover:text-primary-500 transition-colors duration-200">プライバシーポリシー</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-neutral-900 mb-4">フォロー</h3>
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
                            <a href="#" class="text-neutral-400 hover:text-primary-500 transition-colors duration-200">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.042-3.441.219-.937 1.407-5.965 1.407-5.965s-.359-.719-.359-1.782c0-1.668.967-2.914 2.171-2.914 1.023 0 1.518.769 1.518 1.69 0 1.029-.655 2.568-.994 3.995-.283 1.194.599 2.169 1.777 2.169 2.133 0 3.772-2.249 3.772-5.495 0-2.873-2.064-4.882-5.012-4.882-3.414 0-5.418 2.561-5.418 5.207 0 1.031.397 2.138.893 2.738a.36.36 0 01.083.345l-.333 1.36c-.053.22-.174.267-.402.161-1.499-.698-2.436-2.888-2.436-4.649 0-3.785 2.75-7.262 7.929-7.262 4.163 0 7.398 2.967 7.398 6.931 0 4.136-2.607 7.464-6.227 7.464-1.216 0-2.357-.6-3.057-1.566l-.83 3.161c-.301 1.16-1.11 2.616-1.651 3.502 1.24.381 2.551.587 3.917.587 6.624 0 11.99-5.367 11.99-11.987C24.007 5.367 18.641.001 12.017.001z" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-8 border-t border-neutral-200 flex flex-col md:flex-row justify-between items-center">
                    <div class="flex items-center space-x-2">
                        <div class="w-6 h-6 bg-primary-500 rounded flex items-center justify-center">
                            <span class="text-white font-bold text-sm">T+</span>
                        </div>
                        <span class="text-sm text-neutral-600">© 2025 Tabelog+. All rights reserved.</span>
                    </div>
                    <p class="text-sm text-neutral-500 mt-4 md:mt-0">
                        美味しいの発見と共有
                    </p>
                </div>
            </div>
        </footer>

        <!-- モバイルナビゲーション -->
        <x-organisms.mobile-nav :current-route="Route::currentRouteName()" />
    </div>

    <!-- トーストメッセージ -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

    @stack('scripts')

    <!-- Global JavaScript -->
    <script>
        // グローバル関数：トーストメッセージの表示
        function showToast(message, type = 'info', duration = 3000) {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');

            const bgColors = {
                success: 'bg-success-500',
                error: 'bg-error-500',
                warning: 'bg-warning-500',
                info: 'bg-primary-500'
            };

            toast.className = `${bgColors[type] || bgColors.info} text-white px-4 py-3 rounded-lg shadow-lg transform translate-x-full transition-transform duration-300`;
            toast.textContent = message;

            container.appendChild(toast);

            // アニメーション
            setTimeout(() => {
                toast.classList.remove('translate-x-full');
            }, 100);

            // 自動削除
            setTimeout(() => {
                toast.classList.add('translate-x-full');
                setTimeout(() => {
                    container.removeChild(toast);
                }, 300);
            }, duration);
        }

        // CSRFトークンをAjaxリクエストで送信する設定
        if (window.axios) {
            window.axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        }
    </script>
</body>

</html>