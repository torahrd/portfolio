<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- カスタムCSS -->
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
</head>

<body class="font-sans antialiased"
    data-authenticated='@json(auth()->check())'
    @auth data-user-id='@json(auth()->id())' @endauth>

    <div class="min-h-screen bg-gray-100">
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
            {{ $slot }}
        </main>
    </div>

    <!-- フォロー申請モーダルを挿入 -->
    @auth
    @include('components.follow-requests-modal')
    @endauth

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- カスタムJS -->
    <script src="{{ asset('js/profile.js') }}"></script>

    <!-- ✅ VS Code安全な記述方法 -->
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