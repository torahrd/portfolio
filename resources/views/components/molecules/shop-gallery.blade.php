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

    {{-- ★修正：実際の投稿データに基づく写真表示 --}}
    @if($posts && $posts->count() > 0)
    {{-- サンプル画像表示（実際の画像フィールドがない場合の代替） --}}
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
        @foreach($posts->take(8) as $index => $post)
        <div class="aspect-square overflow-hidden rounded-lg group cursor-pointer bg-neutral-100">
            {{--
                        ★注意：実際の画像フィールドに応じて修正が必要
                        現在は仮のプレースホルダー画像を表示
                    --}}
            <div class="w-full h-full flex items-center justify-center text-neutral-400 group-hover:scale-110 transition-transform duration-300">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-4 text-center">
        <p class="text-sm text-neutral-500">{{ $posts->count() }}件の投稿があります</p>
    </div>
    @else
    <div class="text-center py-8 text-neutral-500">
        <svg class="mx-auto h-16 w-16 text-neutral-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
        </svg>
        <p class="text-sm">まだ写真が投稿されていません</p>
    </div>
    @endif
</div>