@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
      <!-- ページヘッダー -->
      <div class="mb-8 text-center">
        <h1 class="text-3xl font-bold text-neutral-900 mb-4">店舗・グルメ検索</h1>
        <p class="text-neutral-600">お気に入りの店舗や料理を見つけよう</p>
      </div>

      <!-- 検索フォーム -->
      <div class="bg-white rounded-xl shadow-card p-6 mb-8">
        <x-molecules.search-bar :placeholder="'店舗名、料理名、エリアで検索...'" :value="request('q')" :action="route('search')" />
      </div>

      <!-- 検索結果 -->
      @if($query)
      <div class="bg-white rounded-xl shadow-card p-6">
        <h2 class="text-xl font-semibold text-neutral-900 mb-4">
          "{{ $query }}" の検索結果
        </h2>

        @if($shop)
        <!-- 店舗情報カード -->
        <div class="mb-8 flex items-center space-x-6">
          @if($shop->image_url)
          <img src="{{ $shop->image_url }}" alt="{{ $shop->name }}" class="w-24 h-24 object-cover rounded-xl border">
          @else
          <div class="w-24 h-24 bg-neutral-100 flex items-center justify-center rounded-xl border text-neutral-400">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
          </div>
          @endif
          <div class="flex-1">
            <div class="text-2xl font-bold text-neutral-900">{{ $shop->name }}</div>
            <div class="text-neutral-600 text-sm mt-1">{{ $shop->address }}</div>
            @if($shop->website)
            <a href="{{ $shop->website }}" class="text-primary-500 text-sm hover:underline" target="_blank">公式サイト</a>
            @endif
          </div>
          <div class="text-right">
            @if($hasPosts)
            <div class="text-sm text-neutral-600">{{ $posts->count() }}件の投稿</div>
            @else
            <div class="text-sm text-neutral-500">投稿なし</div>
            @endif
          </div>
        </div>

        @if($hasPosts)
        <!-- 投稿一覧 -->
        <div>
          <h3 class="text-lg font-semibold text-neutral-900 mb-4">この店舗の投稿</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($posts as $post)
            <x-molecules.post-card :post="$post" />
            @endforeach
          </div>
        </div>
        @else
        <!-- 投稿なしの場合の新規投稿作成誘導 -->
        <div class="text-center py-12 bg-neutral-50 rounded-xl">
          <svg class="mx-auto h-16 w-16 text-neutral-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
          </svg>
          <h3 class="text-lg font-medium text-neutral-900 mb-2">この店舗への投稿はまだありません</h3>
          <p class="text-neutral-600 mb-6">最初の投稿者になって、この店舗の魅力を共有しませんか？</p>
          <a href="{{ route('posts.create') }}?shop_name={{ urlencode($shop->name) }}&shop_address={{ urlencode($shop->address) }}"
            class="inline-flex items-center px-6 py-3 bg-primary-500 text-white font-medium rounded-lg hover:bg-primary-600 transition-colors duration-200">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            この店舗に投稿する
          </a>
        </div>
        @endif
        @else
        <!-- 店舗が見つからない場合 -->
        <div class="text-center py-12">
          <svg class="mx-auto h-12 w-12 text-neutral-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
          </svg>
          <h3 class="text-lg font-medium text-neutral-900 mb-2">該当する店舗が見つかりません</h3>
          <p class="text-neutral-600 mb-6">別のキーワードでお試しください。</p>
          <a href="{{ route('posts.create') }}"
            class="inline-flex items-center px-6 py-3 bg-primary-500 text-white font-medium rounded-lg hover:bg-primary-600 transition-colors duration-200">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            新規投稿を作成
          </a>
        </div>
        @endif
      </div>
      @else
      <!-- 初期状態 -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- 人気のカテゴリ -->
        <div class="bg-white rounded-xl shadow-card p-6">
          <h3 class="text-lg font-semibold text-neutral-900 mb-4">人気のカテゴリ</h3>
          <div class="space-y-2">
            <x-atoms.badge-info class="mr-2">ラーメン</x-atoms.badge-info>
            <x-atoms.badge-info class="mr-2">寿司</x-atoms.badge-info>
            <x-atoms.badge-info class="mr-2">イタリアン</x-atoms.badge-info>
            <x-atoms.badge-info class="mr-2">カフェ</x-atoms.badge-info>
          </div>
        </div>

        <!-- 人気のエリア -->
        <div class="bg-white rounded-xl shadow-card p-6">
          <h3 class="text-lg font-semibold text-neutral-900 mb-4">人気のエリア</h3>
          <div class="space-y-2">
            <x-atoms.badge-success class="mr-2">渋谷</x-atoms.badge-success>
            <x-atoms.badge-success class="mr-2">新宿</x-atoms.badge-success>
            <x-atoms.badge-success class="mr-2">銀座</x-atoms.badge-success>
            <x-atoms.badge-success class="mr-2">表参道</x-atoms.badge-success>
          </div>
        </div>

        <!-- 最近の検索 -->
        <div class="bg-white rounded-xl shadow-card p-6">
          <h3 class="text-lg font-semibold text-neutral-900 mb-4">最近の検索</h3>
          <p class="text-neutral-600 text-sm">検索履歴はありません</p>
        </div>
      </div>
      @endif
    </div>
  </div>
@endsection