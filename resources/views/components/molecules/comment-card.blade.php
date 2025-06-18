@props([
'comment',
'post',
'level' => 0
])

@php
$levelClass = $level > 0 ? 'ml-4' : '';
$commentId = $comment->id;
@endphp

<div class="comment-card {{ $levelClass }}">
    <div class="flex space-x-3 p-4 bg-white rounded-lg border border-neutral-200 mb-4">
        <!-- アバター -->
        <div class="w-8 h-8 bg-neutral-300 rounded-full overflow-hidden">
            @if($comment->user->avatar_url)
            <img src="{{ $comment->user->avatar_url }}" alt="{{ $comment->user->name }}" class="w-full h-full object-cover">
            @else
            <div class="w-full h-full flex items-center justify-center text-neutral-600">
                {{ substr($comment->user->name, 0, 1) }}
            </div>
            @endif
        </div>

        <div class="flex-1 min-w-0">
            <!-- ヘッダー -->
            <div class="flex items-center space-x-2 mb-2">
                <a href="{{ route('profile.show', $comment->user) }}"
                    class="font-medium text-neutral-900 hover:text-primary-600">
                    {{ $comment->user->name }}
                </a>

                <span class="text-neutral-500 text-xs">
                    {{ $comment->created_at->diffForHumans() }}
                </span>
            </div>

            <!-- コメント本文 -->
            <div class="text-sm text-neutral-700 mb-3">
                {{ $comment->body }}
            </div>

            <!-- アクション -->
            <div class="flex items-center space-x-4">
                @auth
                <button class="text-neutral-500 hover:text-primary-600 text-sm">
                    返信
                </button>
                @endauth

                @auth
                @if(auth()->id() === $comment->user_id || auth()->id() === $post->user_id)
                <form action="{{ route('comments.destroy', $comment->id) }}"
                    method="POST"
                    class="inline"
                    onsubmit="return confirm('削除しますか？')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="text-neutral-500 hover:text-red-600 text-sm">
                        削除
                    </button>
                </form>
                @endif
                @endauth
            </div>
        </div>
    </div>
</div>