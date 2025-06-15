@props([
'tabs' => [],
'activeTab' => null
])

<div class="border-b border-neutral-200">
  <nav class="flex space-x-8" aria-label="Tabs">
    @foreach($tabs as $tab)
    @php
    $isActive = isset($tab['active']) && $tab['active'] || $activeTab === $tab['key'];
    @endphp

    <a
      href="{{ $tab['href'] ?? '#' }}"
      class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200 {{ $isActive 
                    ? 'border-primary-500 text-primary-600' 
                    : 'border-transparent text-neutral-500 hover:text-neutral-700 hover:border-neutral-300' }}"
      aria-current="{{ $isActive ? 'page' : 'false' }}">
      {{ $tab['label'] }}
    </a>
    @endforeach
  </nav>
</div>