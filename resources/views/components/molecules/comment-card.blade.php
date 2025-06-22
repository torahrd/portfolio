{{--
  コメント表示用カードコンポーネント

  props:
    - comment: コメントのオブジェクト
    - post: 親投稿のオブジェクト
    - level: 返信の階層レベル（インデントに使用）
--}}
@props([
'comment',
'post',
'level' => 0
])

@php
$levelClass = $level > 0 ? 'ml-8' : ''; // インデントを少し深く
$commentId = $comment->id;
@endphp

<div id="comment-{{ $commentId }}" class="comment-card {{ $levelClass }}" x-data="{ showReply: false }">
    <div class="flex items-start space-x-4 p-4">
        <!-- アバター -->
        <x-atoms.avatar :user="$comment->user" size="default" />

        <div class="flex-1 min-w-0">
            <!-- ユーザー名と投稿時間 -->
            <div class="flex items-center space-x-2 mb-1">
                <a href="{{ route('profile.show', $comment->user) }}" class="font-medium text-sm text-neutral-800 hover:underline">
                    {{ $comment->user->name }}
                    @if($comment->user->is_private)
                    <svg class="inline w-3 h-3 ml-1 text-neutral-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                    </svg>
                    @endif
                </a>
                <span class="text-neutral-500 text-xs">
                    {{ $comment->created_at->diffForHumans() }}
                </span>
            </div>

            <!-- コメント本文 -->
            <div class="text-base text-neutral-900 mb-2">
                <p class="whitespace-pre-wrap">{{ $comment->body }}</p>
            </div>

            <!-- アクション: 返信と削除 -->
            <div class="flex items-center space-x-4 text-xs font-medium">
                @auth
                <button class="text-neutral-600 hover:text-primary-600" @click="showReply = !showReply" :aria-expanded="showReply.toString()">
                    返信
                </button>
                @endauth

                @auth
                @if(auth()->id() === $comment->user_id || auth()->id() === $post->user_id)
                <form action="{{ route('comments.destroy', $comment->id) }}"
                    method="POST"
                    class="inline"
                    onsubmit="return confirm('本当にこのコメントを削除しますか？')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="text-neutral-600 hover:text-red-600">
                        削除
                    </button>
                </form>
                @endif
                @endauth
            </div>

            <!-- 返信フォーム: 返信ボタンで表示/非表示 -->
            @auth
            <div x-show="showReply" class="mt-3" x-transition>
                <form id="reply-form-{{ $comment->id }}" action="{{ route('comments.store', ['post' => $post->id]) }}" method="POST" class="space-y-2 reply-form">
                    @csrf
                    <input type="hidden" name="post_id" value="{{ $post->id }}">
                    <input type="hidden" name="parent_id" value="{{ $level === 0 ? $comment->id : $comment->parent_id }}">
                    <textarea name="body" rows="2" maxlength="200" required class="w-full border border-neutral-300 rounded-md p-2 text-sm focus:ring-primary-400 focus:border-primary-400" placeholder="返信を入力（200文字以内）"></textarea>
                    <div class="reply-error text-red-600 text-sm hidden"></div>
                    <div class="flex justify-end">
                        <button type="submit" class="bg-primary-500 text-white px-4 py-1 rounded hover:bg-primary-600 transition-colors text-sm font-semibold">返信する</button>
                    </div>
                </form>
            </div>
            @endauth

            <!-- 返信一覧 -->
            <div class="mt-4 space-y-4 pl-4 border-l-2 border-neutral-100" id="comment-replies-{{ $comment->id }}">
                @foreach($comment->children->sortBy('created_at') as $child)
                <x-molecules.comment-card :comment="$child" :post="$post" :level="$level + 1" />
                @endforeach
            </div>
        </div>
    </div>
</div>