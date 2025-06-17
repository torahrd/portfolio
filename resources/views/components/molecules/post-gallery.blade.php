@props([
'images' => [],
'postTitle' => ''
])

<div class="bg-white rounded-xl shadow-card overflow-hidden">
    @if(count($images) > 0)
    <!-- 写真ギャラリー -->
    <div class="relative" x-data="{ currentImage: 0, images: {{ json_encode($images) }} }">
        <!-- メイン画像表示 -->
        <div class="aspect-[4/3] overflow-hidden">
            <template x-for="(image, index) in images" :key="index">
                <img x-show="currentImage === index"
                    :src="image.url"
                    :alt="'{{ $postTitle }} - 写真' + (index + 1)"
                    class="w-full h-full object-cover transition-opacity duration-300">
            </template>
        </div>

        <!-- ナビゲーションボタン -->
        <template x-if="images.length > 1">
            <div>
                <!-- 前へボタン -->
                <button @click="currentImage = currentImage === 0 ? images.length - 1 : currentImage - 1"
                    class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-black/50 text-white rounded-full p-2 hover:bg-black/70 transition-colors duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>

                <!-- 次へボタン -->
                <button @click="currentImage = currentImage === images.length - 1 ? 0 : currentImage + 1"
                    class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-black/50 text-white rounded-full p-2 hover:bg-black/70 transition-colors duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            </div>
        </template>

        <!-- インジケーター -->
        <template x-if="images.length > 1">
            <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2">
                <div class="flex space-x-2">
                    <template x-for="(image, index) in images" :key="index">
                        <button @click="currentImage = index"
                            :class="currentImage === index ? 'bg-white' : 'bg-white/50'"
                            class="w-2 h-2 rounded-full transition-colors duration-200">
                        </button>
                    </template>
                </div>
            </div>
        </template>

        <!-- 画像カウンター -->
        <template x-if="images.length > 1">
            <div class="absolute top-4 right-4 bg-black/50 text-white px-3 py-1 rounded-full text-sm">
                <span x-text="currentImage + 1"></span>/<span x-text="images.length"></span>
            </div>
        </template>
    </div>
    @else
    <!-- 画像がない場合のプレースホルダー -->
    <div class="aspect-[4/3] bg-neutral-100 flex items-center justify-center">
        <div class="text-center text-neutral-400">
            <svg class="w-16 h-16 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <p class="text-sm">写真はありません</p>
        </div>
    </div>
    @endif
</div>