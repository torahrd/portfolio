@props([
'post'
])

<div class="bg-white rounded-xl shadow-card p-6">
    <h2 class="text-xl font-bold text-neutral-900 mb-4">投稿詳細</h2>

    <div class="space-y-4">
        <!-- 基本情報 -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @if($post->visit_time)
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-neutral-900">訪問日時</p>
                    <p class="text-sm text-neutral-600">{{ \Carbon\Carbon::parse($post->visit_time)->format('Y年m月d日 H:i') }}</p>
                </div>
            </div>
            @endif

            @if($post->budget)
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-neutral-900">予算</p>
                    <p class="text-sm text-neutral-600">{{ App\Helpers\BudgetHelper::formatBudget($post->budget) }}</p>
                </div>
            </div>
            @endif
        </div>

        <!-- 訪問ステータス -->
        <div class="flex items-center justify-between p-3 bg-neutral-50 rounded-lg">
            <span class="text-sm font-medium text-neutral-700">訪問ステータス</span>
            <x-atoms.badge-success size="sm">
                {{ $post->visit_status ? '訪問済み' : '訪問予定' }}
            </x-atoms.badge-success>
        </div>

        <!-- メニュー情報 -->
        @if($post->repeat_menu || $post->interest_menu)
        <div class="border-t border-neutral-200 pt-4">
            <h3 class="text-sm font-medium text-neutral-900 mb-3">メニュー情報</h3>
            <div class="space-y-3">
                @if($post->repeat_menu)
                <div>
                    <p class="text-xs font-medium text-neutral-500 uppercase tracking-wide mb-1">リピートメニュー</p>
                    <p class="text-sm text-neutral-900">{{ $post->repeat_menu }}</p>
                </div>
                @endif

                @if($post->interest_menu)
                <div>
                    <p class="text-xs font-medium text-neutral-500 uppercase tracking-wide mb-1">気になるメニュー</p>
                    <p class="text-sm text-neutral-900">{{ $post->interest_menu }}</p>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- メモ -->
        @if($post->memo)
        <div class="border-t border-neutral-200 pt-4">
            <h3 class="text-sm font-medium text-neutral-900 mb-2">メモ</h3>
            <p class="text-sm text-neutral-700 leading-relaxed">{{ $post->memo }}</p>
        </div>
        @endif

        <!-- 参考リンク -->
        @if($post->reference_link)
        <div class="border-t border-neutral-200 pt-4">
            <h3 class="text-sm font-medium text-neutral-900 mb-2">参考リンク</h3>
            <a href="{{ $post->reference_link }}"
                target="_blank"
                rel="noopener noreferrer"
                class="inline-flex items-center space-x-2 text-primary-600 hover:text-primary-700 text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                </svg>
                <span>リンクを開く</span>
            </a>
        </div>
        @endif
    </div>
</div>