<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    @if(config('analytics.enabled'))
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('analytics.measurement_id') }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        
        // デフォルトの同意状態（Cookie同意前）
        // 一時的にコメントアウト - Cookie同意機能が動作していないため
        // gtag('consent', 'default', {
        //     'analytics_storage': 'denied',
        //     'ad_storage': 'denied',
        //     'wait_for_update': 500
        // });
        
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
    <!-- 削除: <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script> -->
</head>

<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen flex flex-col">
        {{-- @include('layouts.navigation') --}}

        <!-- Page Heading -->
        @if (isset($header))
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
        @else
        <x-organisms.header />
        @endif

        <!-- Page Content -->
        <main class="flex-1">
            @yield('content')
        </main>

        <!-- Footer -->
        <x-organisms.footer type="default" />
    </div>
    
    <!-- Cookie同意バナー -->
    <x-cookie-consent />
    
    @stack('scripts')
</body>

</html>