@props([
'comment',
'post',
'level' => 0
])

@php
$levelClass = $level > 0 ? 'ml-' . min($level * 4, 16) : '';
@endphp

<div class="comment-card {{ $levelClass }}">
    <div class="flex space-x-3 p-4 {{ $level > 0 ? 'bg-neutral-50' : 'bg-white' }} rounded-lg">
        <!-- アバター -->
        <x-atoms.avatar
            :src="$comment->user->avatar_url"
            :alt="$comment->user->name"
            size="sm" />

        <div class="flex-1 min-w-0">
            <!-- ヘッダー -->
            <div class="flex items-center space-x-2 mb-2">
                <a href="{{ route('profile.show', $comment->user) }}"
                    class="font-medium text-neutral-900 hover:text-primary-600 transition-colors duration-200">
                    {{ $comment->user->name }}
                </a>

                @if($comment->parent)
                <span class="text-neutral-400 text-sm">→</span>
                <span class="text-neutral-600 text-sm">{{ $comment->parent->user->name }}さんへの返信</span>
                @endif

                <span class="text-neutral-500 text-xs">
                    {{ $comment->created_at->diffForHumans() }}
                </span>
            </div>

            <!-- コメント本文 -->
            <div class="prose prose-sm max-w-none">
                {!! $comment->body_with_mentions !!}
            </div>

            <!-- アクション -->
            <div class="flex items-center space-x-4 mt-3">
                @auth
                <button onclick="toggleReplyForm({{ $comment->id }})"
                    class="text-neutral-500 hover:text-primary-600 text-sm font-medium transition-colors duration-200">
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
                        class="text-neutral-500 hover:text-red-600 text-sm font-medium transition-colors duration-200">
                        削除
                    </button>
                </form>
                @endif
                @endauth
            </div>

            <!-- 返信フォーム -->
            @auth
            <div id="reply-form-{{ $comment->id }}"
                class="reply-form mt-4 hidden">
                <form action="{{ route('comments.store', $post->id) }}"
                    method="POST"
                    class="space-y-3">
                    @csrf
                    <input type="hidden" name="parent_id" value="{{ $comment->id }}">

                    <div class="flex space-x-3">
                        <x-atoms.avatar
                            :src="auth()->user()->avatar_url"
                            :alt="auth()->user()->name"
                            size="sm" />

                        <div class="flex-1">
                            <textarea name="body"
                                rows="2"
                                placeholder="{{ $comment->user->name }}さんに返信..."
                                class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 resize-none"
                                required></textarea>

                            <div class="flex items-center justify-end space-x-2 mt-2">
                                <button type="button"
                                    onclick="toggleReplyForm({{ $comment->id }})"
                                    class="px-3 py-1 text-sm text-neutral-600 hover:text-neutral-800 transition-colors duration-200">
                                    キャンセル
                                </button>
                                <button type="submit"
                                    class="px-4 py-1 bg-primary-500 text-white text-sm rounded-lg hover:bg-primary-600 transition-colors duration-200">
                                    返信
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            @endauth
        </div>
    </div>
</div>