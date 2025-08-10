@extends('layouts.app')

@section('content')
  <div class="min-h-screen bg-neutral-50">
    <!-- 戻るボタン -->
    <div class="bg-white border-b border-neutral-200">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <a href="{{ route('posts.index') }}"
          class="inline-flex items-center space-x-2 text-neutral-600 hover:text-neutral-900 transition-colors duration-200">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
          </svg>
          <span>投稿一覧に戻る</span>
        </a>
      </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- 投稿者情報ヘッダー -->
      <div class="bg-white rounded-xl shadow-card p-6 mb-6">
        <div class="flex items-center space-x-4">
          <x-atoms.avatar
            :user="$post->user"
            size="lg" />

          <div class="flex-1">
            <div class="flex items-center space-x-2">
              <a href="{{ route('profile.show', $post->user) }}"
                class="text-xl font-bold text-neutral-900 hover:text-primary-600 transition-colors duration-200">
                {{ $post->user->name }}
              </a>

              @if($post->user->is_private)
              <span class="text-neutral-400">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                </svg>
              </span>
              @endif

              <!-- いいねボタン -->
              <x-molecules.post-actions
                :post-id="$post->id"
                :likes-count="$post->favorite_users_count"
                :comments-count="$post->comments_count"
                :is-favorited="auth()->check() ? $post->isFavoritedBy(auth()->id()) : false"
                class="ml-4" />
            </div>

            <div class="flex items-center space-x-4 text-sm text-neutral-600 mt-1">
              <span>{{ $post->created_at->format('Y年m月d日 H:i') }}</span>

              @if($post->visit_time)
              <span>•</span>
              <span>{{ \Carbon\Carbon::parse($post->visit_time)->format('Y年m月d日') }}に訪問</span>
              @endif
            </div>
          </div>

          <!-- 投稿アクション -->
          @auth
          @if(auth()->id() === $post->user_id)
          <div x-data="dropdown" class="relative">
            <button @click="toggleDropdown"
              class="p-2 text-neutral-400 hover:text-neutral-600 rounded-full hover:bg-neutral-100 transition-colors duration-200">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
              </svg>
            </button>

            <div x-show="shouldShowDropdown"
              @click.outside="handleClickOutside"
              x-transition
              class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-neutral-200 py-1 z-40">
              <a href="{{ route('posts.edit', $post) }}"
                class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 transition-colors duration-200">
                編集
              </a>
              <form action="{{ route('posts.destroy', $post) }}"
                method="POST"
                onsubmit="return confirm('削除しますか？')">
                @csrf
                @method('DELETE')
                <button type="submit"
                  class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors duration-200">
                  削除
                </button>
              </form>
            </div>
          </div>
          @endif
          @endauth
        </div>
      </div>

      <!-- 店舗情報 -->
      <div class="bg-white rounded-xl shadow-card p-6 mb-6">
        <div class="flex items-center space-x-4">
          <div class="w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center">
            <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
          </div>

          <div class="flex-1">
            <h2 class="text-xl font-bold text-neutral-900">
              <a href="{{ route('shops.show', $post->shop) }}"
                class="hover:text-primary-600 transition-colors duration-200">
                {{ $post->shop->name }}
              </a>
            </h2>
            <p class="text-neutral-600 text-sm">{{ $post->shop->address }}</p>
          </div>

          <a href="{{ route('shops.show', $post->shop) }}"
            class="px-4 py-2 bg-primary-500 text-white rounded-lg hover:bg-primary-600 transition-colors duration-200 text-sm font-medium">
            店舗詳細
          </a>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- 左カラム: 写真ギャラリー -->
        <div class="lg:col-span-2">
          @if($post->image_url)
          <img src="{{ $post->image_url }}" alt="投稿画像" class="w-full rounded-lg mb-4">
          @endif
        </div>

        <!-- 右カラム: 投稿詳細 -->
        <div class="lg:col-span-1">
          <x-molecules.post-meta :post="$post" />
        </div>
      </div>

      <!-- コメントセクション -->
      <div id="comments" class="mt-8" 
           x-data="commentSection" 
           data-post-id="{{ $post->id }}"
           data-csrf-token="{{ csrf_token() }}"
           data-comments-store-url="{{ route('comments.store', $post) }}"
           data-comments-delete-base-url="/comments/">
        <div class="bg-white rounded-xl shadow-card p-6">
          <h3 class="text-lg font-bold text-neutral-900 mb-6">
            コメント (<span id="comment-count">{{ $post->comments->where('parent_id', null)->count() }}</span>件)
          </h3>

          <!-- コメント投稿フォーム -->
          @auth
          <div class="mb-6">
            <form id="comment-form" 
                  @submit.prevent="submitComment"
                  action="{{ route('comments.store', $post) }}" 
                  method="POST" 
                  class="space-y-4">
              @csrf
              <div class="flex space-x-3">
                <x-atoms.avatar
                  :user="auth()->user()"
                  size="sm" />

                <div class="flex-1">
                  <textarea name="body"
                    id="comment-body"
                    rows="3"
                    placeholder="コメントを入力してください..."
                    :value="commentContent"
                    @input="updateCommentContent"
                    class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 resize-none">{{ old('body') }}</textarea>

                  <div id="comment-error" class="text-red-600 text-sm mt-1 hidden"></div>

                  <div class="flex justify-end mt-3">
                    <button type="submit"
                      id="comment-submit"
                      class="px-4 py-2 bg-primary-500 text-white rounded-lg hover:bg-primary-600 transition-colors duration-200 font-medium">
                      コメント投稿
                    </button>
                  </div>
                </div>
              </div>
            </form>
          </div>
          @else
          <div class="mb-6 p-4 bg-neutral-50 rounded-lg text-center">
            <p class="text-neutral-600 mb-3">コメントを投稿するにはログインが必要です</p>
            <a href="{{ route('login') }}"
              class="inline-flex items-center px-4 py-2 bg-primary-500 text-white rounded-lg hover:bg-primary-600 transition-colors duration-200 font-medium">
              ログイン
            </a>
          </div>
          @endauth

          <!-- コメント一覧 -->
          <div id="comments-container" class="space-y-4">
            @forelse($post->comments()->parentComments()->with(['user', 'children.user'])->orderBy('created_at', 'desc')->get() as $comment)
            <x-molecules.comment-card :comment="$comment" :post="$post" :level="0" />
            @empty
            <div id="no-comments" class="text-center py-8 text-neutral-500">
              <svg class="w-16 h-16 mx-auto mb-4 text-neutral-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
              </svg>
              <p>まだコメントがありません</p>
              <p class="text-sm">最初のコメントを投稿してみませんか？</p>
            </div>
            @endforelse
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- モバイル用アクションバー -->
  <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-neutral-200 p-4 lg:hidden">
    <div class="flex items-center justify-between">
      <a href="{{ route('shops.show', $post->shop) }}"
        class="flex-1 bg-primary-500 text-white text-center py-3 rounded-lg font-medium hover:bg-primary-600 transition-colors duration-200">
        店舗詳細を見る
      </a>
    </div>
  </div>
@endsection