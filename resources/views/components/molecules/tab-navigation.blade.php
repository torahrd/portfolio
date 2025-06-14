@props([
'tabs' => [
['key' => 'popular', 'label' => '人気', 'active' => true],
['key' => 'recent', 'label' => '新着', 'active' => false]
],
'activeTab' => 'popular'
])

<nav class="flex space-x-1 bg-neutral-100 p-1 rounded-lg">
  @foreach($tabs as $tab)
  @php
  $isActive = $tab['key'] === $activeTab;
  @endphp

  <button
    data-tab="{{ $tab['key'] }}"
    class="flex-1 py-2 px-4 text-sm font-medium rounded-md transition-all duration-200 relative overflow-hidden
                {{ $isActive 
                    ? 'bg-white text-primary-500 shadow-sm' 
                    : 'text-neutral-600 hover:text-neutral-900 hover:bg-white/50' 
                }}">
    <span class="relative z-10">{{ $tab['label'] }}</span>

    @if($isActive)
    <!-- アクティブインジケーター -->
    <div class="absolute inset-0 bg-gradient-to-r from-primary-500/10 to-primary-600/10 opacity-20"></div>
    @endif
  </button>
  @endforeach
</nav>

<!-- Tab Navigation Script -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('[data-tab]');

    tabButtons.forEach(button => {
      button.addEventListener('click', function() {
        const targetTab = this.dataset.tab;

        // Remove active state from all tabs
        tabButtons.forEach(btn => {
          btn.classList.remove('bg-white', 'text-primary-500', 'shadow-sm');
          btn.classList.add('text-neutral-600', 'hover:text-neutral-900', 'hover:bg-white/50');

          // Remove active indicator
          const indicator = btn.querySelector('.absolute.inset-0');
          if (indicator) {
            indicator.remove();
          }
        });

        // Add active state to clicked tab
        this.classList.add('bg-white', 'text-primary-500', 'shadow-sm');
        this.classList.remove('text-neutral-600', 'hover:text-neutral-900', 'hover:bg-white/50');

        // Add active indicator
        const indicator = document.createElement('div');
        indicator.className = 'absolute inset-0 bg-gradient-to-r from-primary-500/10 to-primary-600/10 opacity-20';
        this.appendChild(indicator);

        // Emit custom event for tab change
        const event = new CustomEvent('tabChanged', {
          detail: {
            activeTab: targetTab
          }
        });
        document.dispatchEvent(event);
      });
    });
  });
</script>