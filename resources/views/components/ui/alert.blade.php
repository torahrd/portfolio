@props([
'type' => 'info',
'dismissible' => false,
'icon' => true
])

@php
$alertClasses = 'alert p-4 rounded-lg border';

$types = [
'success' => 'alert-success',
'warning' => 'alert-warning',
'danger' => 'alert-danger',
'info' => 'alert-info',
];

$icons = [
'success' => 'fas fa-check-circle',
'warning' => 'fas fa-exclamation-triangle',
'danger' => 'fas fa-exclamation-circle',
'info' => 'fas fa-info-circle',
];

$alertClasses .= ' ' . $types[$type];
@endphp

<div class="{{ $alertClasses }}"
  @if($dismissible) x-data="{ show: true }" x-show="show" x-transition @endif
  {{ $attributes }}>
  <div class="flex items-start">
    @if($icon)
    <div class="flex-shrink-0">
      <i class="{{ $icons[$type] }} mr-3"></i>
    </div>
    @endif

    <div class="flex-1">
      {{ $slot }}
    </div>

    @if($dismissible)
    <div class="flex-shrink-0 ml-4">
      <button x-on:click="show = false"
        class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
        <i class="fas fa-times"></i>
      </button>
    </div>
    @endif
  </div>
</div>