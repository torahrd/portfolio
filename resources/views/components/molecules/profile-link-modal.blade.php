{{-- プロフィールリンクモーダルコンポーネント --}}
@props(['user'])

@php
$modalId = 'profile-link-modal-' . $user->id . '-' . uniqid();
$urlInputId = 'profile-link-url-' . $user->id . '-' . uniqid();
$expiresId = 'profile-link-expires-' . $user->id . '-' . uniqid();
$copyStatusId = 'copy-status-' . $user->id . '-' . uniqid();
$manualGuideId = 'manual-copy-guide-' . $user->id . '-' . uniqid();
@endphp

<x-atoms.modal name="{{ $modalId }}" max-width="md">
  <div class="p-6">
    <div class="flex items-center justify-between mb-4">
      <h3 class="text-lg font-semibold text-gray-900">プロフィールリンク</h3>
      <button
        x-on:click="$dispatch('close-modal', '{{ $modalId }}')"
        class="text-gray-400 hover:text-gray-600">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
      </button>
    </div>

    <div class="space-y-4">
      {{-- リンクURL表示 --}}
      <div>
        <label for="{{ $urlInputId }}" class="block text-sm font-medium text-gray-700 mb-2">リンクURL</label>
        <div class="flex">
          <input
            type="text"
            id="{{ $urlInputId }}"
            class="flex-1 px-3 py-2 border border-gray-300 rounded-l-md bg-gray-50 text-sm"
            readonly>
          <button
            onclick="copyProfileLink('{{ $urlInputId }}', '{{ $copyStatusId }}', '{{ $manualGuideId }}')"
            class="px-4 py-2 bg-blue-500 text-white text-sm font-medium rounded-r-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
            </svg>
          </button>
        </div>
        <p class="text-xs text-gray-500 mt-1">このリンクを共有することで、プロフィールを直接表示できます（3日間有効）</p>
      </div>

      {{-- 有効期限表示 --}}
      <div>
        <div class="block text-sm font-medium text-gray-700 mb-2">有効期限</div>
        <p id="{{ $expiresId }}" class="text-sm text-gray-600"></p>
      </div>

      {{-- コピー状況表示 --}}
      <div id="{{ $copyStatusId }}" class="hidden">
        <div class="flex items-center text-green-600 text-sm">
          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
          </svg>
          リンクをコピーしました！
        </div>
      </div>

      {{-- 手動コピー案内 --}}
      <div id="{{ $manualGuideId }}" class="hidden">
        <div class="bg-yellow-50 border border-yellow-200 rounded-md p-3">
          <div class="flex items-center text-yellow-800 text-sm">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
            自動コピーに失敗しました。上記のリンクを手動でコピーしてください。
          </div>
        </div>
      </div>
    </div>

    {{-- モーダルフッター --}}
    <div class="flex justify-between items-center mt-6 pt-4 border-t border-gray-200">
      <button
        onclick="deactivateProfileLink()"
        class="px-4 py-2 text-red-600 text-sm font-medium hover:bg-red-50 rounded-md">
        リンクを無効化
      </button>
      <button
        x-on:click="$dispatch('close-modal', '{{ $modalId }}')"
        class="px-4 py-2 bg-gray-500 text-white text-sm font-medium rounded-md hover:bg-gray-600">
        閉じる
      </button>
    </div>
  </div>
</x-atoms.modal>