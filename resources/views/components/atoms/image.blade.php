@props([
'src' => '',
'alt' => '',
'lazy' => true,
'aspectRatio' => '16/9',
'objectFit' => 'cover',
'rounded' => 'lg',
'placeholder' => '/images/placeholder.jpg'
])

@php
$classes = collect([
'w-full h-full transition-all duration-300',

// アスペクト比
'16/9' => 'aspect-video',
'1/1' => 'aspect-square',
'4/3' => 'aspect-[4/3]',

// オブジェクトフィット
'cover' => 'object-cover',
'contain' => 'object-contain',
'fill' => 'object-fill',

// 角丸
'none' => '',
'sm' => 'rounded-sm',
'md' => 'rounded-md',
'lg' => 'rounded-lg',
'xl' => 'rounded-xl',
'full' => 'rounded-full'
]);

$imageClasses = $classes->only(['w-full'])->merge([
$classes[$aspectRatio] ?? $classes['16/9'],
$classes[$objectFit] ?? $classes['cover'],
$classes[$rounded] ?? $classes['lg']
])->implode(' ');

$containerClasses = 'relative overflow-hidden bg-neutral-200 ' . ($classes[$rounded] ?? $classes['lg']);
@endphp

<div class="{{ $containerClasses }}">
  @if($lazy)
  <img
    data-src="{{ $src }}"
    src="{{ $placeholder }}"
    alt="{{ $alt }}"
    class="{{ $imageClasses }} lazy opacity-0"
    loading="lazy"
    {{ $attributes }}
    onload="this.classList.remove('opacity-0'); this.classList.add('opacity-100');">

  <!-- Lazy Loading Script -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const lazyImages = document.querySelectorAll('img.lazy');

      if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
          entries.forEach(entry => {
            if (entry.isIntersecting) {
              const img = entry.target;
              img.src = img.dataset.src;
              img.classList.remove('lazy');
              imageObserver.unobserve(img);
            }
          });
        });

        lazyImages.forEach(img => imageObserver.observe(img));
      } else {
        // Fallback for browsers without IntersectionObserver
        lazyImages.forEach(img => {
          img.src = img.dataset.src;
          img.classList.remove('lazy');
        });
      }
    });
  </script>
  @else
  <img
    src="{{ $src }}"
    alt="{{ $alt }}"
    class="{{ $imageClasses }}"
    {{ $attributes }}>
  @endif

  <!-- Loading placeholder -->
  <div class="absolute inset-0 bg-neutral-200 animate-pulse lazy-placeholder">
    <div class="w-full h-full flex items-center justify-center">
      <svg class="w-8 h-8 text-neutral-400" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
      </svg>
    </div>
  </div>
</div>

<style>
  .lazy {
    transition: opacity 0.3s;
  }

  .lazy-placeholder {
    display: block;
  }

  img:not(.lazy)+.lazy-placeholder {
    display: none;
  }
</style>