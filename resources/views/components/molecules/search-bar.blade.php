@props([
'placeholder' => '店舗やグルメを検索...',
'value' => '',
'suggestions' => [],
'action' => '/search'
])

<div class="relative"
  x-data="searchBar()"
  data-initial-value="{{ $value }}"
  data-suggestions="{{ json_encode($suggestions) }}">
  <form action="{{ $action }}" method="GET" class="relative">
    <div class="relative">
      <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
        <svg class="h-5 w-5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
        </svg>
      </div>

      <input
        type="text"
        name="q"
        value="{{ $value }}"
        placeholder="{{ $placeholder }}"
        class="block w-full pl-12 pr-12 py-3 border border-neutral-300 rounded-xl bg-white text-neutral-900 placeholder-neutral-500 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200"
        x-model="query"
        x-on:input="updateSuggestions()"
        x-on:focus="showSuggestions = true"
        autocomplete="off" />

      <div class="absolute inset-y-0 right-0 pr-4 flex items-center">
        <button
          type="submit"
          class="p-1 rounded-lg text-neutral-400 hover:text-primary-500 focus:outline-none focus:text-primary-500 transition-colors duration-200">
          <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
          </svg>
        </button>
      </div>
    </div>
  </form>

  <!-- 検索候補 -->
  <div
    x-show="showSuggestions && suggestions.length > 0"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0 translate-y-1"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 translate-y-1"
    class="absolute top-full left-0 right-0 mt-2 bg-white rounded-xl shadow-lg border border-neutral-200 overflow-hidden z-50"
    x-on:click.outside="showSuggestions = false">
    <template x-for="(suggestion, index) in suggestions" :key="index">
      <a
        :href="suggestion.url"
        class="block px-4 py-3 hover:bg-neutral-50 transition-colors duration-200 border-b border-neutral-100 last:border-b-0"
        x-on:click="showSuggestions = false">
        <div class="flex items-center space-x-3">
          <svg class="h-4 w-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
          </svg>
          <span x-text="suggestion.title" class="text-neutral-900"></span>
          <span x-text="suggestion.category" class="text-sm text-neutral-500" x-show="suggestion.category"></span>
        </div>
      </a>
    </template>
  </div>
</div>