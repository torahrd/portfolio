@props([
'rating' => 0,
'maxRating' => 5,
'showValue' => true,
'size' => 'sm',
'readonly' => true
])

@php
$starSizes = [
'xs' => 'w-3 h-3',
'sm' => 'w-4 h-4',
'md' => 'w-5 h-5',
'lg' => 'w-6 h-6'
];

$starSize = $starSizes[$size] ?? $starSizes['sm'];
$rating = max(0, min($maxRating, $rating));
$ratingPercentage = ($rating - floor($rating)) * 100;
@endphp

<div class="flex items-center space-x-1"
  x-data="ratingComponent()"
  data-rating-percentage="{{ $ratingPercentage }}">
  <div class="flex items-center space-x-0.5">
    @for($i = 1; $i <= $maxRating; $i++)
      @if($i <=floor($rating))
      {{-- 満点の星 --}}
      <svg class="{{ $starSize }} text-yellow-400 fill-current" viewBox="0 0 20 20">
      <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
      </svg>
      @elseif($i == ceil($rating) && $rating != floor($rating))
      {{-- 半分の星 --}}
      <div class="relative">
        <svg class="{{ $starSize }} text-neutral-300 fill-current" viewBox="0 0 20 20">
          <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
        </svg>
        <div class="absolute inset-0 overflow-hidden" x-ref="partialStar" :style="partialStarStyle">
          <svg class="{{ $starSize }} text-yellow-400 fill-current" viewBox="0 0 20 20">
            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
          </svg>
        </div>
      </div>
      @else
      {{-- 空の星 --}}
      <svg class="{{ $starSize }} text-neutral-300 fill-current" viewBox="0 0 20 20">
        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
      </svg>
      @endif
      @endfor
  </div>

  @if($showValue)
  <span class="text-sm text-neutral-600 ml-2">
    {{ number_format($rating, 1) }}
  </span>
  @endif
</div>

@verbatim
<script>
  function ratingComponent() {
    return {
      partialStarStyle: '',

      init() {
        const percentage = this.$el.dataset.ratingPercentage;
        this.partialStarStyle = `width: ${percentage}%`;
      }
    }
  }
</script>
@endverbatim