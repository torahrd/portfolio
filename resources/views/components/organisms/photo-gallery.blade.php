@props([
'photos' => [],
'initialIndex' => 0,
'showThumbnails' => true
])

<div x-data="photoGallery({{ json_encode($photos) }}, {{ $initialIndex }})" class="relative">
  <!-- メイン画像表示 -->
  <div class="relative bg-black rounded-xl overflow-hidden">
    <div class="aspect-w-16 aspect-h-9">
      <img
        :src="currentPhoto.url"
        :alt="currentPhoto.alt || 'ギャラリー画像'"
        class="w-full h-full object-contain"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" />
    </div>

    <!-- 前へ・次へボタン -->
    <template x-if="photos.length > 1">
      <div>
        <button
          x-on:click="previousPhoto()"
          x-show="currentIndex > 0"
          class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white p-2 rounded-full hover:bg-opacity-75 transition-all duration-200">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
          </svg>
        </button>

        <button
          x-on:click="nextPhoto()"
          x-show="currentIndex < photos.length - 1"
          class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white p-2 rounded-full hover:bg-opacity-75 transition-all duration-200">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
          </svg>
        </button>
      </div>
    </template>

    <!-- 画像カウンター -->
    <template x-if="photos.length > 1">
      <div class="absolute top-4 right-4 bg-black bg-opacity-50 text-white px-3 py-1 rounded-full text-sm">
        <span x-text="currentIndex + 1"></span> / <span x-text="photos.length"></span>
      </div>
    </template>

    <!-- フルスクリーンボタン -->
    <button
      x-on:click="toggleFullscreen()"
      class="absolute top-4 left-4 bg-black bg-opacity-50 text-white p-2 rounded-full hover:bg-opacity-75 transition-all duration-200">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
      </svg>
    </button>
  </div>

  <!-- サムネイル表示 -->
  <template x-if="showThumbnails && photos.length > 1">
    <div class="mt-4 flex space-x-2 overflow-x-auto pb-2">
      <template x-for="(photo, index) in photos" :key="index">
        <button
          x-on:click="setCurrentIndex(index)"
          class="flex-shrink-0 w-16 h-16 rounded-lg overflow-hidden border-2 transition-all duration-200"
          :class="currentIndex === index ? 'border-primary-500' : 'border-transparent hover:border-neutral-300'">
          <img
            :src="photo.thumbnail || photo.url"
            :alt="photo.alt || 'サムネイル'"
            class="w-full h-full object-cover" />
        </button>
      </template>
    </div>
  </template>
</div>

<script>
  function photoGallery(photos, initialIndex = 0) {
    return {
      photos: photos,
      currentIndex: initialIndex,
      showThumbnails: true,

      get currentPhoto() {
        return this.photos[this.currentIndex] || {};
      },

      nextPhoto() {
        if (this.currentIndex < this.photos.length - 1) {
          this.currentIndex++;
        }
      },

      previousPhoto() {
        if (this.currentIndex > 0) {
          this.currentIndex--;
        }
      },

      setCurrentIndex(index) {
        if (index >= 0 && index < this.photos.length) {
          this.currentIndex = index;
        }
      },

      toggleFullscreen() {
        // フルスクリーン機能の実装
        const element = this.$el.querySelector('img');
        if (element.requestFullscreen) {
          element.requestFullscreen();
        } else if (element.webkitRequestFullscreen) {
          element.webkitRequestFullscreen();
        } else if (element.msRequestFullscreen) {
          element.msRequestFullscreen();
        }
      },

      init() {
        // キーボードナビゲーション
        document.addEventListener('keydown', (e) => {
          if (e.key === 'ArrowLeft') {
            this.previousPhoto();
          } else if (e.key === 'ArrowRight') {
            this.nextPhoto();
          } else if (e.key === 'Escape') {
            // フルスクリーン終了など
          }
        });

        // タッチイベント（スワイプ）
        let startX = 0;
        let endX = 0;

        const handleTouchStart = (e) => {
          startX = e.touches[0].clientX;
        };

        const handleTouchEnd = (e) => {
          endX = e.changedTouches[0].clientX;
          const diff = startX - endX;

          if (Math.abs(diff) > 50) { // 50px以上のスワイプで反応
            if (diff > 0) {
              this.nextPhoto(); // 左スワイプで次の画像
            } else {
              this.previousPhoto(); // 右スワイプで前の画像
            }
          }
        };

        this.$el.addEventListener('touchstart', handleTouchStart);
        this.$el.addEventListener('touchend', handleTouchEnd);
      }
    }
  }
</script>