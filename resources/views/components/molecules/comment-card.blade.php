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
            <x-molecules.comment-actions :comment="$comment" :post="$post" showReply="showReply" />

            <!-- 返信フォーム: 返信ボタンで表示/非表示 -->
            @auth
            <x-molecules.comment-reply-form :comment="$comment" :post="$post" :level="$level" showReply="showReply" />
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