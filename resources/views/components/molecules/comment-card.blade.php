{{--
  コメント表示用カードコンポーネント（CSP対応版）

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

<div id="comment-{{ $commentId }}" class="comment-card {{ $levelClass }}">
    <div class="flex items-start space-x-4 p-4">
        <!-- アバター -->
        <x-atoms.avatar :user="$comment->user" size="default" :showPrivateIcon="true" />

        <div class="flex-1 min-w-0">
            <!-- ユーザー名と投稿時間 -->
            <div class="flex items-center space-x-2 mb-1">
                <a href="{{ route('profile.show', $comment->user) }}" class="font-medium text-sm text-neutral-800 hover:underline">
                    {{ $comment->user->name }}
                </a>

                @if($comment->user->is_private)
                <span class="text-neutral-400">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                    </svg>
                </span>
                @endif

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
                <button class="text-neutral-600 hover:text-primary-600" 
                        @click="toggleReplyForm" 
                        data-comment-id="{{ $comment->id }}">
                    返信
                </button>
                @endauth

                @auth
                @if(auth()->id() === $comment->user_id || auth()->id() === $post->user_id)
                <button @click="deleteComment" 
                        data-comment-id="{{ $comment->id }}"
                        class="text-neutral-600 hover:text-red-600">
                    削除
                </button>
                @endif
                @endauth
            </div>

            <!-- 返信フォーム: 返信ボタンで表示/非表示 -->
            @auth
            <div data-comment-id="{{ $comment->id }}" class="mt-4 reply-form" style="display: none;">
                <form @submit.prevent="submitReply" data-comment-id="{{ $comment->id }}" class="space-y-3">
                    <textarea
                        @input="updateReplyContent"
                        placeholder="返信を入力..."
                        class="w-full p-3 border border-neutral-300 rounded-lg resize-none focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200"
                        rows="2"></textarea>
                    <div class="flex items-center justify-end space-x-2">
                        <x-atoms.button-secondary
                            size="sm"
                            type="button"
                            @click="closeReplyForm">
                            キャンセル
                        </x-atoms.button-secondary>
                        <x-atoms.button-primary
                            size="sm"
                            type="submit">
                            返信
                        </x-atoms.button-primary>
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