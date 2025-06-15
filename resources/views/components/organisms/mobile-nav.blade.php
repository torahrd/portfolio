@props([
'currentRoute' => null
])

@php
$currentRoute = $currentRoute ?? Route::currentRouteName();

$navItems = [
[
'name' => 'home',
'label' => 'ホーム',
'route' => 'home',
'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
</svg>',
'icon_filled' => '<svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
  <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
</svg>'
],
[
'name' => 'search',
'label' => '検索',
'route' => 'search',
'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
</svg>',
'icon_filled' => '<svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
  <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
</svg>'
],
[
'name' => 'posts.create',
'label' => '投稿',
'route' => 'posts.create',
'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
</svg>',
'icon_filled' => '<svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd"></path>
</svg>',
'auth' => true
],
[
'name' => 'profile',
'label' => 'マイページ',
'route' => 'profile.show',
'route_params' => fn() => Auth::user(),
'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
</svg>',
'icon_filled' => '<svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
  <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
</svg>',
'auth' => true
]
];
@endphp

<nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-neutral-200 z-40 md:hidden">
  <div class="grid grid-cols-4 h-16">
    @foreach($navItems as $item)
    @if(!isset($item['auth']) || (isset($item['auth']) && Auth::check()))
    @php
    $isActive = $currentRoute === $item['route'] ||
    (isset($item['alternate_routes']) && in_array($currentRoute, $item['alternate_routes']));

    $routeParams = isset($item['route_params']) && is_callable($item['route_params'])
    ? $item['route_params']()
    : (isset($item['route_params']) ? $item['route_params'] : []);

    $href = $routeParams ? route($item['route'], $routeParams) : route($item['route']);
    @endphp

    <a
      href="{{ $href }}"
      class="flex flex-col items-center justify-center space-y-1 py-2 transition-colors duration-200 {{ $isActive ? 'text-primary-500' : 'text-neutral-600 hover:text-neutral-900' }}">
      <div class="flex items-center justify-center">
        @if($isActive && isset($item['icon_filled']))
        {!! $item['icon_filled'] !!}
        @else
        {!! $item['icon'] !!}
        @endif
      </div>
      <span class="text-xs font-medium">{{ $item['label'] }}</span>
    </a>
    @elseif(!Auth::check() && $item['name'] === 'profile')
    {{-- 未ログイン時はログインページへ --}}
    <a
      href="{{ route('login') }}"
      class="flex flex-col items-center justify-center space-y-1 py-2 transition-colors duration-200 text-neutral-600 hover:text-neutral-900">
      <div class="flex items-center justify-center">
        {!! $item['icon'] !!}
      </div>
      <span class="text-xs font-medium">ログイン</span>
    </a>
    @endif
    @endforeach
  </div>
</nav>

<!-- モバイルナビ分のパディング -->
<div class="h-16 md:hidden"></div>