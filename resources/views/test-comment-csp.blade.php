@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-neutral-900 mb-8">コメント機能 CSP対応テスト</h1>
        
        <!-- テスト用投稿 -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-xl font-semibold mb-4">テスト投稿</h2>
            <p class="text-neutral-700">これはコメント機能のCSP対応をテストするための投稿です。</p>
        </div>

        <!-- コメントセクション -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold mb-4">コメント ({{ $comments->count() }}件)</h3>
            
            <!-- コメント投稿フォーム -->
            <div class="mb-6 p-4 bg-neutral-50 rounded-lg">
                <form id="comment-form" action="{{ route('comments.store', ['post' => $post->id]) }}" method="POST" class="space-y-3">
                    @csrf
                    <input type="hidden" name="post_id" value="{{ $post->id }}">
                    <div class="flex items-start space-x-3">
                        <div class="flex-1">
                            <x-atoms.input-text
                                name="body"
                                placeholder="コメントを入力してください..."
                                class="w-full"
                                x-model="commentContent" />
                        </div>
                        <x-atoms.button-primary
                            size="sm"
                            type="submit">
                            コメント投稿
                        </x-atoms.button-primary>
                    </div>
                </form>
            </div>

            <!-- コメント一覧 -->
            <div class="space-y-4">
                @foreach($comments as $comment)
                <div class="bg-white rounded-lg shadow-sm p-4">
                    <div class="flex items-start space-x-4">
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
                                <x-molecules.comment-reply-test :comment="$comment" :post="$post" reply-placeholder="返信コメントを入力..." />
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

                            <!-- 返信一覧 -->
                            <div class="mt-4 space-y-4 pl-4 border-l-2 border-neutral-100">
                                @foreach($comment->children->sortBy('created_at') as $child)
                                <div class="bg-neutral-50 rounded-lg p-3">
                                    <div class="flex items-start space-x-3">
                                        <x-atoms.avatar :user="$child->user" size="small" :showPrivateIcon="true" />
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-2 mb-1">
                                                <span class="font-medium text-sm text-neutral-800">{{ $child->user->name }}</span>
                                                <span class="text-neutral-500 text-xs">{{ $child->created_at->diffForHumans() }}</span>
                                            </div>
                                            <p class="text-sm text-neutral-900">{{ $child->body }}</p>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>


@endsection 