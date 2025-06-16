@props([
'posts'
])

<div class="bg-white rounded-xl shadow-card p-6">
    <h3 class="text-lg font-bold text-neutral-900 mb-4 flex items-center">
        <svg class="w-5 h-5 mr-2 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
        </svg>
        写真
    </h3>

    @php
    $imagesFound = false;
    $allImages = collect();

    // 投稿から画像を収集（実際のフィールド名に応じて調整が必要）
    foreach($posts as $post) {
    if(isset($post->images) && $post->images->count() > 0) {
    $allImages = $allImages->merge($post->images);
    $imagesFound = true;
    }
    }
    @endphp

    @if($imagesFound && $allImages->count() > 0)
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
        @foreach($allImages->take(12) as $image)
        <div class="aspect-square overflow-hidden rounded-lg group cursor-pointer">
            <img src="{{ $image->url ?? '/images/placeholder.jpg' }}"
                alt="店舗写真"
                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
                loading="lazy">
        </div>
        @endforeach
    </div>

    @if($allImages->count() > 12)
    <div class="mt-4 text-center">
        <button class="text-primary-600 hover:text-primary-700 font-medium text-sm">
            さらに {{ $allImages->count() - 12 }}枚の写真を見る
        </button>
    </div>
    @endif
    @else
    <div class="text-center py-8 text-neutral-500">
        <svg class="mx-auto h-16 w-16 text-neutral-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
        </svg>
        <p class="text-sm">まだ写真が投稿されていません</p>
    </div>
    @endif
</div>