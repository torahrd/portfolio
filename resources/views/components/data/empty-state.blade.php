@props([
'icon' => 'fas fa-inbox',
'title' => 'データがありません',
'description' => null,
'action' => null
])

<div class="text-center py-12" {{ $attributes }}>
  <i class="{{ $icon }} text-6xl text-gray-300 mb-4"></i>
  <h3 class="text-xl font-medium text-gray-600 mb-2">{{ $title }}</h3>

  @if($description)
  <p class="text-gray-500 mb-6 max-w-md mx-auto">{{ $description }}</p>
  @endif

  @if($action)
  <div>{{ $action }}</div>
  @endif
</div>