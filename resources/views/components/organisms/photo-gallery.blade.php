@props([
    'photos' => [],
    'title' => '写真ギャラリー',
    'columns' => 3
])

<div class="bg-white rounded-xl shadow-card p-4">
    <h3 class="text-lg font-semibold text-neutral-900 mb-4">{{ $title }}</h3>
    
    @if(count($photos) > 0)
        <div class="grid grid-cols-2 md:grid-cols-{{ $columns }} gap-2 md:gap-4">
            @foreach($photos as $index => $photo)
                <div class="group cursor-pointer relative overflow-hidden rounded-lg bg-neutral-200 aspect-square">
                    <img 
                        src="{{ $photo['url'] ?? $photo }}" 
                        alt="{{ $photo['alt'] ?? "画像 " . ($index + 1) }}"
                        class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110"
                        loading="lazy"
                        onclick="openPhotoModal({{ $index }})"
                    >
                    
                    <!-- ホバーオーバーレイ -->
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors duration-300 flex items-center justify-center">
                        <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
            @endforeach
        </div>
        
        @if(count($photos) > 6)
            <div class="mt-4 text-center">
                <button class="text-primary-500 hover:text-primary-600 font-medium transition-colors duration-200">
                    すべての写真を見る ({{ count($photos) }}枚)
                </button>
            </div>
        @endif
    @else
        <!-- 空の状態 -->
        <div class="text-center py-8">
            <svg class="mx-auto h-12 w-12 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 48 48">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-neutral-900">写真がありません</h3>
            <p class="mt-1 text-sm text-neutral-500">まだ写真が投稿されていません</p>
        </div>
    @endif
</div>

<!-- 写真モーダル -->
<div id="photo-modal" class="fixed inset-0 bg-black/90 z-50 hidden flex items-center justify-center p-4">
    <div class="relative max-w-4xl max-h-full">
        <!-- 閉じるボタン -->
        <button 
            onclick="closePhotoModal()"
            class="absolute top-4 right-4 text-white hover:text-neutral-300 transition-colors duration-200 z-10"
        >
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        
        <!-- 前へボタン -->
        <button 
            id="photo-prev"
            onclick="previousPhoto()"
            class="absolute left-4 top-1/2 transform -translate-y-1/2 text-white hover:text-neutral-300 transition-colors duration-200"
        >
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </button>
        
        <!-- 次へボタン -->
        <button 
            id="photo-next"
            onclick="nextPhoto()"
            class="absolute right-4 top-1/2 transform -translate-y-1/2 text-white hover:text-neutral-300 transition-colors duration-200"
        >
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </button>
        
        <!-- 画像 -->
        <img 
            id="modal-photo"
            src=""
            alt=""
            class="max-w-full max-h-full object-contain rounded-lg"
        >
        
        <!-- 画像カウンター -->
        <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 text-white bg-black/50 px-3 py-1 rounded-full text-sm">
            <span id="photo-counter">1 / 1</span>
        </div>
    </div>
</div>

<script>
let currentPhotoIndex = 0;
const photos = @json($photos);

function openPhotoModal(index) {
    currentPhotoIndex = index;
    const modal = document.getElementById('photo-modal');
    const modalPhoto = document.getElementById('modal-photo');
    const counter = document.getElementById('photo-counter');
    
    const photo = photos[index];
    modalPhoto.src = photo.url || photo;
    modalPhoto.alt = photo.alt || `画像 ${index + 1}`;
    counter.textContent = `${index + 1} / ${photos.length}`;
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    updateNavigationButtons();
}

function closePhotoModal() {
    const modal = document.getElementById('photo-modal');
    modal.classList.add('hidden');
    document.body.style.overflow = '';
}

function previousPhoto() {
    if (currentPhotoIndex > 0) {
        openPhotoModal(currentPhotoIndex - 1);
    }
}

function nextPhoto() {
    if (currentPhotoIndex < photos.length - 1) {
        openPhotoModal(currentPhotoIndex + 1);
    }
}

function updateNavigationButtons() {
    const prevBtn = document.getElementById('photo-prev');
    const nextBtn = document.getElementById('photo-next');
    
    prevBtn.style.display = currentPhotoIndex === 0 ? 'none' : 'block';
    nextBtn.style.display = currentPhotoIndex === photos.length - 1 ? 'none' : 'block';
}

// ESCキーで閉じる
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closePhotoModal();
    } else if (e.key === 'ArrowLeft') {
        previousPhoto();
    } else if (e.key === 'ArrowRight') {
        nextPhoto();
    }
});

// モーダル背景クリックで閉じる
document.getElementById('photo-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closePhotoModal();
    }
});
</script>