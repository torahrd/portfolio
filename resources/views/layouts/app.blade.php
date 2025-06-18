<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="font-sans antialiased bg-neutral-50">
    <div class="min-h-screen">
        <!-- Header -->
        @if(isset($header))
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
            {{-- ★修正：$slotの適切な処理 --}}
            @if(isset($slot) && method_exists($slot, 'isEmpty') && !$slot->isEmpty())
            {{ $slot }}
            @elseif(isset($slot) && !empty(trim($slot)))
            {{ $slot }}
            @else
            @yield('content')
            @endif
        </main>

        <!-- Footer -->
        @if(isset($footer))
        <footer class="bg-white border-t border-neutral-200 mt-auto">
            <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
                {{ $footer }}
            </div>
        </footer>
        @endif
    </div>
</body>

</html>