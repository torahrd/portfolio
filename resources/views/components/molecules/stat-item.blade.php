@props([
'title',
'value',
'change' => null,
'changeType' => 'neutral', // 'positive', 'negative', 'neutral'
'icon' => null,
'href' => null
])

@php
$changeClasses = [
'positive' => 'text-success-600',
'negative' => 'text-error-600',
'neutral' => 'text-neutral-600'
];

$changeClass = $changeClasses[$changeType] ?? $changeClasses['neutral'];
@endphp

<div class="bg-white rounded-xl shadow-card p-6 hover:shadow-card-hover transition-shadow duration-300">
  @if($href)
  <a href="{{ $href }}" class="block">
    @endif

    <div class="flex items-center">
      @if($icon)
      <div class="flex-shrink-0">
        <div class="w-8 h-8 bg-primary-100 rounded-lg flex items-center justify-center">
          {!! $icon !!}
        </div>
      </div>
      @endif

      <div class="{{ $icon ? 'ml-4' : '' }} flex-1 min-w-0">
        <dl>
          <dt class="text-sm font-medium text-neutral-600 truncate">
            {{ $title }}
          </dt>
          <dd class="flex items-baseline">
            <div class="text-2xl font-bold text-neutral-900">
              {{ $value }}
            </div>

            @if($change)
            <div class="ml-2 flex items-baseline text-sm font-semibold {{ $changeClass }}">
              @if($changeType === 'positive')
              <svg class="self-center flex-shrink-0 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
              </svg>
              @elseif($changeType === 'negative')
              <svg class="self-center flex-shrink-0 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
              </svg>
              @endif
              <span class="sr-only">
                {{ $changeType === 'positive' ? '増加' : ($changeType === 'negative' ? '減少' : '変化') }}
              </span>
              {{ $change }}
            </div>
            @endif
          </dd>
        </dl>
      </div>
    </div>

    @if($href)
  </a>
  @endif
</div>