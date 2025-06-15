@props([
'post',
'comments' => [],
'showForm' => true
])

<div x-data="commentSection()"
  data-post-id="{{ $post->id }}"
  data-csrf-token="{{ csrf_token() }}"
  data-comments-store-url="{{ route('comments.store') }}"
  data-comments-like-base-url="/comments/"
  data-comments-delete-base-url="/comments/"
  class="space-y-6">
  <!-- コメント一覧 -->
  <div class="space-y-4">
    @forelse($comments as $comment)
    <div class="bg-white rounded-xl p-4 shadow-sm border border-neutral-200">
      <div class="flex items-start space-x-3">
        <x-atoms.avatar
          :src="$comment->user->avatar_url"
          :alt="$comment->user->name"
          size="sm" />

        <div class="flex-1 min-w-0">
          <div class="flex items-center space-x-2 mb-2">
            <h4 class="text-sm font-semibold text-neutral-900">
              <a href="{{ route('profile.show', $comment->user) }}" class="hover:text-primary-500 transition-colors duration-200">
                {{ $comment->user->name }}
              </a>
            </h4>
            <span class="text-xs text-neutral-500">
              {{ $comment->created_at->diffForHumans() }}
            </span>
          </div>

          <div class="text-sm text-neutral-900 mb-3">
            {{ $comment->content }}
          </div>

          <!-- コメントアクション -->
          <div class="flex items-center space-x-4 text-xs">
            <button
              x-on:click="toggleLike({{ $comment->id }})"
              class="flex items-center space-x-1 text-neutral-500 hover:text-primary-500 transition-colors duration-200">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
              </svg>
              <span>{{ $comment->likes_count ?? 0 }}</span>
            </button>

            <button
              x-on:click="showReplyForm = showReplyForm === {{ $comment->id }} ? null : {{ $comment->id }}"
              class="text-neutral-500 hover:text-primary-500 transition-colors duration-200">
              返信
            </button>

            @if(Auth::id() === $comment->user_id)
            <button
              x-on:click="deleteComment({{ $comment->id }})"
              class="text-neutral-500 hover:text-error-500 transition-colors duration-200">
              削除
            </button>
            @endif
          </div>

          <!-- 返信フォーム -->
          <div x-show="showReplyForm === {{ $comment->id }}" class="mt-4">
            <form x-on:submit.prevent="submitReply({{ $comment->id }})" class="space-y-3">
              <textarea
                x-model="replyContent"
                placeholder="返信を入力..."
                class="w-full p-3 border border-neutral-300 rounded-lg resize-none focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200"
                rows="2"></textarea>
              <div class="flex items-center justify-end space-x-2">
                <x-atoms.button
                  variant="ghost"
                  size="sm"
                  type="button"
                  x-on:click="showReplyForm = null; replyContent = ''">
                  キャンセル
                </x-atoms.button>
                <x-atoms.button
                  variant="primary"
                  size="sm"
                  type="submit">
                  返信
                </x-atoms.button>
              </div>
            </form>
          </div>

          <!-- 返信一覧 -->
          @if($comment->replies && $comment->replies->count() > 0)
          <div class="mt-4 space-y-3 pl-4 border-l-2 border-neutral-200">
            @foreach($comment->replies as $reply)
            <div class="bg-neutral-50 rounded-lg p-3">
              <div class="flex items-start space-x-2">
                <x-atoms.avatar
                  :src="$reply->user->avatar_url"
                  :alt="$reply->user->name"
                  size="xs" />
                <div class="flex-1 min-w-0">
                  <div class="flex items-center space-x-2 mb-1">
                    <h5 class="text-xs font-semibold text-neutral-900">
                      {{ $reply->user->name }}
                    </h5>
                    <span class="text-xs text-neutral-400">
                      {{ $reply->created_at->diffForHumans() }}
                    </span>
                  </div>
                  <p class="text-xs text-neutral-900">
                    {{ $reply->content }}
                  </p>
                </div>
              </div>
            </div>
            @endforeach
          </div>
          @endif
        </div>
      </div>
    </div>
    @empty
    <div class="text-center py-8">
      <svg class="mx-auto h-12 w-12 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 48 48">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 16c0 3.5-1.3 6.4-3.5 8.5-1.4 1.3-3.2 2.3-5.5 2.3s-4.1-1-5.5-2.3C4.3 22.4 3 19.5 3 16c0-3.5 1.3-6.4 3.5-8.5C8 6.2 9.8 5.2 12.1 5.2c2.3 0 4.1 1 5.5 2.3 2.2 2.1 3.5 5 3.5 8.5z"></path>
      </svg>
      <h3 class="mt-2 text-sm font-medium text-neutral-900">コメントがありません</h3>
      <p class="mt-1 text-sm text-neutral-500">最初のコメントを投稿してみましょう！</p>
    </div>
    @endforelse
  </div>

  <!-- コメント投稿フォーム -->
  @if($showForm && Auth::check())
  <div class="bg-white rounded-xl p-6 shadow-sm border border-neutral-200">
    <form x-on:submit.prevent="submitComment()" class="space-y-4">
      <div class="flex items-start space-x-3">
        <x-atoms.avatar
          :src="auth()->user()->avatar_url"
          :alt="auth()->user()->name"
          size="sm" />

        <div class="flex-1">
          <textarea
            x-model="commentContent"
            placeholder="コメントを入力..."
            class="w-full p-3 border border-neutral-300 rounded-lg resize-none focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200"
            rows="3"></textarea>
        </div>
      </div>

      <div class="flex items-center justify-end">
        <x-atoms.button
          variant="primary"
          type="submit">
          コメント投稿
        </x-atoms.button>
      </div>
    </form>
  </div>
  @elseif($showForm && !Auth::check())
  <div class="bg-neutral-50 rounded-xl p-6 text-center">
    <p class="text-neutral-600 mb-4">コメントを投稿するにはログインが必要です</p>
    <div class="flex items-center justify-center space-x-3">
      <x-atoms.button variant="primary" href="{{ route('login') }}" size="sm">
        ログイン
      </x-atoms.button>
      <x-atoms.button variant="secondary" href="{{ route('register') }}" size="sm">
        新規登録
      </x-atoms.button>
    </div>
  </div>
  @endif
</div>

@verbatim
<script>
  function commentSection() {
    return {
      commentContent: '',
      replyContent: '',
      showReplyForm: null,

      init() {
        // データ属性から設定値を取得
        this.postId = this.$el.dataset.postId;
        this.csrfToken = this.$el.dataset.csrfToken;
        this.commentsStoreUrl = this.$el.dataset.commentsStoreUrl;
        this.commentsLikeBaseUrl = this.$el.dataset.commentsLikeBaseUrl;
        this.commentsDeleteBaseUrl = this.$el.dataset.commentsDeleteBaseUrl;
      },

      async submitComment() {
        if (!this.commentContent.trim()) return;

        try {
          const response = await fetch(this.commentsStoreUrl, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': this.csrfToken
            },
            body: JSON.stringify({
              post_id: this.postId,
              content: this.commentContent
            })
          });

          if (response.ok) {
            this.commentContent = '';
            // ページをリロードまたは動的にコメントを追加
            window.location.reload();
          } else {
            console.error('コメントの投稿に失敗しました');
          }
        } catch (error) {
          console.error('コメントの投稿に失敗しました:', error);
        }
      },

      async submitReply(commentId) {
        if (!this.replyContent.trim()) return;

        try {
          const response = await fetch(this.commentsStoreUrl, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': this.csrfToken
            },
            body: JSON.stringify({
              post_id: this.postId,
              parent_id: commentId,
              content: this.replyContent
            })
          });

          if (response.ok) {
            this.replyContent = '';
            this.showReplyForm = null;
            window.location.reload();
          } else {
            console.error('返信の投稿に失敗しました');
          }
        } catch (error) {
          console.error('返信の投稿に失敗しました:', error);
        }
      },

      async toggleLike(commentId) {
        try {
          const response = await fetch(`${this.commentsLikeBaseUrl}${commentId}/like`, {
            method: 'POST',
            headers: {
              'X-CSRF-TOKEN': this.csrfToken
            }
          });

          if (response.ok) {
            // いいね数の更新など
            window.location.reload();
          } else {
            console.error('いいねの更新に失敗しました');
          }
        } catch (error) {
          console.error('いいねの更新に失敗しました:', error);
        }
      },

      async deleteComment(commentId) {
        if (!confirm('コメントを削除しますか？')) return;

        try {
          const response = await fetch(`${this.commentsDeleteBaseUrl}${commentId}`, {
            method: 'DELETE',
            headers: {
              'X-CSRF-TOKEN': this.csrfToken
            }
          });

          if (response.ok) {
            window.location.reload();
          } else {
            console.error('コメントの削除に失敗しました');
          }
        } catch (error) {
          console.error('コメントの削除に失敗しました:', error);
        }
      }
    }
  }
</script>
@endverbatim