@props(['title' => 'テスト用カウンター'])

<div x-data="testCounter" class="text-center">
    <h3 class="text-lg font-semibold mb-4">{{ $title }}</h3>
    <div class="flex items-center justify-center space-x-4">
        <button 
            @click="decrement" 
            class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition-colors">
            -
        </button>
        <span class="text-2xl font-bold min-w-[3rem] text-center" x-text="count">0</span>
        <button 
            @click="increment" 
            class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition-colors">
            +
        </button>
    </div>
    <p class="text-sm text-gray-600 mt-2">
        CSPビルドのテスト用コンポーネントです。カウンターが正常に動作すれば、CSPビルドが機能しています。
    </p>
</div> 