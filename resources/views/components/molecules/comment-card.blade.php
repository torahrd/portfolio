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
    <div class="flex space-x-3 p-4">
        <!-- アバター（プロフィールリンク付き） -->
        <a href="{{ route('profile.show', $comment->user) }}" class="flex-shrink-0" aria-label="{{ $comment->user->name }}のプロフィールへ">
            <div class="w-10 h-10 bg-neutral-200 rounded-full overflow-hidden">
                @if($comment->user->avatar)
                <img src="{{ $comment->user->avatar_url }}" alt="{{ $comment->user->name }} のアバター" class="w-full h-full object-cover">
                @else
                <div class="w-full h-full flex items-center justify-center text-neutral-500 font-bold text-lg">
                    {{ substr($comment->user->name, 0, 1) }}
                </div>
                @endif
            </div>
        </a>

        <div class="flex-1 min-w-0">
            <!-- ヘッダー: ユーザー名と投稿時間 -->
            <div class="flex items-center space-x-2 mb-1">
                <a href="{{ route('profile.show', $comment->user) }}"
                    class="font-medium text-sm text-neutral-800 hover:underline">
                    {{ $comment->user->name }}
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
                <form action="{{ route('comments.store', ['post' => $post->id]) }}" method="POST" class="space-y-2">
                    @csrf
                    <input type="hidden" name="post_id" value="{{ $post->id }}">
                    <input type="hidden" name="parent_id" value="{{ $level === 0 ? $comment->id : $comment->parent_id }}">
                    <textarea name="body" rows="2" maxlength="200" required class="w-full border border-neutral-300 rounded-md p-2 text-sm focus:ring-primary-400 focus:border-primary-400" placeholder="返信を入力（200文字以内）"></textarea>
                    <div class="flex justify-end">
                        <button type="submit" class="bg-primary-500 text-white px-4 py-1 rounded hover:bg-primary-600 transition-colors text-sm font-semibold">返信する</button>
                    </div>
                </form>
            </div>
            @endauth

            <!-- 子コメントのインデント表示（2層まで） -->
            @foreach($comment->children as $childComment)
            <x-molecules.comment-card :comment="$childComment" :post="$post" :level="1" />
            @endforeach
        </div>
    </div>
</div>