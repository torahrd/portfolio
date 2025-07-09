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

    <button
      type="button"
      data-tab="{{ $tab['key'] }}"
      class="tab-button whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200 {{ $isActive 
                    ? 'border-primary-500 text-primary-600' 
                    : 'border-transparent text-neutral-500 hover:text-neutral-700 hover:border-neutral-300' }}"
      aria-current="{{ $isActive ? 'page' : 'false' }}">
      {{ $tab['label'] }}
    </button>
    @endforeach
  </nav>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  // タブボタンのクリックイベントを設定
  const tabButtons = document.querySelectorAll('.tab-button');
  
  tabButtons.forEach(button => {
    button.addEventListener('click', function() {
      const tabKey = this.getAttribute('data-tab');
      changeTab(tabKey);
    });
  });
});

function changeTab(tabKey) {
  // URLにタブパラメータを追加してリダイレクト
  const url = new URL(window.location);
  url.searchParams.set('tab', tabKey);
  window.location.href = url.toString();
}
</script>