@props([
'name',
'show' => false,
'maxWidth' => 'md',
'closeable' => true,
'title' => null
])

@php
$maxWidthClasses = [
'sm' => 'max-w-sm',
'md' => 'max-w-md',
'lg' => 'max-w-lg',
'xl' => 'max-w-xl',
'2xl' => 'max-w-2xl',
'3xl' => 'max-w-3xl',
'4xl' => 'max-w-4xl',
'5xl' => 'max-w-5xl',
'6xl' => 'max-w-6xl',
'7xl' => 'max-w-7xl',
];

$maxWidthClass = $maxWidthClasses[$maxWidth] ?? $maxWidthClasses['md'];
@endphp

<div x-data="modal(@js($show))"
  x-on:open-modal.window="$event.detail == '{{ $name }}' ? show = true : null"
  x-on:close-modal.window="$event.detail == '{{ $name }}' ? show = false : null"
  x-on:close.stop="show = false"
  x-on:keydown.escape.window="show = false"
  x-show="show"
  x-transition:enter="transition ease-out duration-300"
  x-transition:enter-start="opacity-0"
  x-transition:enter-end="opacity-100"
  x-transition:leave="transition ease-in duration-200"
  x-transition:leave-start="opacity-100"
  x-transition:leave-end="opacity-0"
  class="fixed inset-0 z-50 overflow-y-auto"
  style="display: none;"
  x-cloak>

  <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
    <!-- Background overlay -->
    <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75"
      x-on:click="show = false"></div>

    <!-- Modal panel -->
    <div class="inline-block w-full {{ $maxWidthClass }} p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-gray-800 shadow-xl rounded-lg"
      x-on:click.stop>

      @if($title || $closeable)
      <div class="flex items-center justify-between mb-4">
        @if($title)
        <h3 class="text-lg font-medium text-gray-900 dark:text-white">
          {{ $title }}
        </h3>
        @endif

        @if($closeable)
        <button x-on:click="show = false"
          class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-200">
          <i class="fas fa-times"></i>
        </button>
        @endif
      </div>
      @endif

      {{ $slot }}
    </div>
  </div>
</div>