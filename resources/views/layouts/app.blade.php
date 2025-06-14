<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="美味しいお店を発見・共有するグルメSNS">
    <meta name="keywords" content="グルメ, レストラン, カフェ, 食べログ, 口コミ">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <!-- Google Fonts - Noto Sans JP -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-neutral-50"
    data-authenticated='@json(auth()->check())'
    @auth data-user-id='@json(auth()->id())' @endauth>

    <div class="min-h-screen">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @if (isset($header))
        <header class="bg-white shadow-sm border-b border-neutral-200">
            <div class="container mx-auto py-6">
                {{ $header }}
            </div>
        </header>
        @endif

        <!-- Page Content -->
        <main class="pb-20 md:pb-0">
            {{ $slot }}
        </main>
    </div>

    <!-- フォロー申請モーダルを挿入 -->
    <!-- フォロー申請機能は将来実装予定 -->

    <!-- VS Code安全な記述方法 -->
    @verbatim
    <script>
        // データ属性から安全に取得
        const bodyElement = document.body;
        window.isAuthenticated = JSON.parse(bodyElement.dataset.authenticated || 'false');
        window.currentUserId = bodyElement.dataset.userId ?
            parseInt(bodyElement.dataset.userId) : null;

        // デバッグ用（開発時のみ使用）
        console.log('認証状態:', window.isAuthenticated);
        console.log('ユーザーID:', window.currentUserId);
    </script>
    @endverbatim
</body>

</html>