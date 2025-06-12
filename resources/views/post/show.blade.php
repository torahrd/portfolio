<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ $post->shop->name }} - 投稿詳細
    </h2>
  </x-slot>

  <div class="container-responsive py-6">
    <!-- 戻るボタン -->
    <div class="mb-6">
      <a href="{{ route('posts.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left mr-2"></i> 投稿一覧に戻る
      </a>
    </div>

    <div class="post-detail">
      <!-- 投稿者情報ヘッダー -->
      <div class="post-detail-header">
        <img src="{{ $post->user->avatar_url }}" alt="{{ $post->user->name }}" class="post-detail-avatar">

        <div class="post-detail-author-info">
          <h2>
            <a href="{{ route('profile.show', $post->user) }}">
              {{ $post->user->name }}
              @if($post->user->is_private)
              <i class="fas fa-lock text-gray-400 ml-1" title="プライベートアカウント"></i>
              @endif
            </a>
          </h2>
          <div class="post-detail-meta">
            <span class="flex items-center space-x-1">
              <i class="fas fa-clock"></i>
              <span>{{ $post->created_at->format('Y年n月j日 H:i') }}</span>
            </span>
            <span class="flex items-center space-x-1">
              <i class="fas fa-eye"></i>
              <span>投稿詳細</span>
            </span>
            @if($post->visit_time)
            <span class="flex items-center space-x-1">
              <i class="fas fa-calendar-alt"></i>
              <span>訪問: {{ \Carbon\Carbon::parse($post->visit_time)->format('Y年n月j日') }}</span>
            </span>
            @endif
            <span class="visit-status-badge {{ $post->visit_status ? 'visit-status-visited' : 'visit-status-planned' }}">
              {{ $post->visit_status ? '訪問済み' : '行きたい' }}
            </span>
          </div>
        </div>
      </div>

      <!-- 店舗情報 -->
      <div class="post-info">
        <h3><i class="fas fa-store"></i> 店舗情報</h3>
        <div class="space-y-2">
          <p class="text-lg">
            <a href="{{ route('shops.show', $post->shop->id) }}" class="shop-link">
              <i class="fas fa-external-link-alt shop-icon"></i>
              {{ $post->shop->name }}
            </a>
          </p>
          @if($post->shop->address)
          <p><strong>住所:</strong> {{ $post->shop->address }}</p>
          @endif
          @if($post->shop->phone)
          <p><strong>電話:</strong> {{ $post->shop->phone }}</p>
          @endif
        </div>
      </div>

      <!-- 投稿内容 -->
      @if($post->body)
      <div class="post-info">
        <h3><i class="fas fa-comment"></i> 投稿内容</h3>
        <div class="prose max-w-none">
          <p class="leading-relaxed">{{ $post->body }}</p>
        </div>
      </div>
      @endif

      <!-- 訪問詳細情報 -->
      <div class="post-info">
        <h3><i class="fas fa-info-circle"></i> 訪問情報</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <p><strong>訪問予定時間:</strong> {{ $post->visit_time ? \Carbon\Carbon::parse($post->visit_time)->format('Y年n月j日 H:i') : '未設定' }}</p>
          <p><strong>訪問済:</strong> {{ $post->visit_status ? 'はい' : 'いいえ' }}</p>
          @if($post->budget)
          <p><strong>予算:</strong> <span class="budget">{{ App\Helpers\BudgetHelper::formatBudget($post->budget) }}</span></p>
          @endif
        </div>
      </div>

      @if($post->repeat_menu || $post->interest_menu)
      <div class="menus">
        <h3><i class="fas fa-utensils"></i> メニュー情報</h3>
        <div class="space-y-3">
          @if($post->repeat_menu)
          <div>
            <strong>リピートメニュー:</strong>
            <p class="mt-1 text-gray-700">{{ $post->repeat_menu }}</p>
          </div>
          @endif
          @if($post->interest_menu)
          <div>
            <strong>気になるメニュー:</strong>
            <p class="mt-1 text-gray-700">{{ $post->interest_menu }}</p>
          </div>
          @endif
        </div>
      </div>
      @endif

      @if($post->memo)
      <div class="memo">
        <h3><i class="fas fa-sticky-note"></i> メモ</h3>
        <div class="prose max-w-none">
          <p class="leading-relaxed">{{ $post->memo }}</p>
        </div>
      </div>
      @endif

      @if($post->reference_link)
      <div class="reference">
        <h3><i class="fas fa-link"></i> 参考リンク</h3>
        <a href="{{ $post->reference_link }}" target="_blank" rel="noopener" class="text-primary-500 hover:text-primary-600 break-all">
          {{ $post->reference_link }}
        </a>
      </div>
      @endif

      <div class="folders">
        <h3><i class="fas fa-folder"></i> 所属フォルダ</h3>
        @if(Auth::check() && $post->user_id === Auth::id())
        @if($post->folders->count() > 0)
        <div class="flex flex-wrap gap-2">
          @foreach($post->folders as $folder)
          <span class="badge badge-primary">{{ $folder->name }}</span>
          @endforeach
        </div>
        @else
        <p class="text-gray-500">フォルダに登録されていません</p>
        @endif
        @else
        <p class="text-gray-500">フォルダ情報は投稿者のみ表示されます</p>
        @endif
      </div>

      <div class="actions">
        <a href="{{ route('posts.index') }}" class="btn btn-secondary">一覧に戻る</a>
        @auth
        @if(auth()->id() === $post->user_id)
        <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-primary">
          <i class="fas fa-edit mr-1"></i>編集
        </a>
        @endif
        @endauth
        <a href="{{ route('shops.show', $post->shop->id) }}" class="btn btn-outline-primary">
          <i class="fas fa-store mr-1"></i>店舗詳細を見る
        </a>
        <a href="{{ route('profile.show', $post->user) }}" class="btn btn-outline-secondary">
          <i class="fas fa-user mr-1"></i> {{ $post->user->name }}のプロフィール
        </a>
      </div>
    </div>

    <!-- コメントセクション -->
    <div class="comment-section" id="comments">
      <h3 class="section-title">
        <i class="fas fa-comments mr-2"></i>
        コメント ({{ $post->comments->where('parent_id', null)->count() }})
      </h3>

      <!-- コメント投稿フォーム -->
      @auth
      <div class="comment-form">
        <h4 class="text-lg font-semibold mb-3">コメントを投稿</h4>
        <form action="{{ route('comments.store', $post->id) }}" method="POST"
          x-data="{ submitting: false }"
          @submit.prevent="submitting = true; $el.submit()">
          @csrf
          <div class="form-group">
            <textarea
              name="body"
              rows="3"
              class="form-input"
              placeholder="コメントを入力してください..."
              required>{{ old('body') }}</textarea>
            @error('body')
            <div class="form-error">{{ $message }}</div>
            @enderror
          </div>
          <button type="submit" class="btn btn-primary" :disabled="submitting">
            <span x-show="!submitting">コメントを投稿</span>
            <span x-show="submitting" class="flex items-center">
              <i class="fas fa-spinner fa-spin mr-2"></i>投稿中...
            </span>
          </button>
        </form>
      </div>
      @else
      <div class="bg-gray-50 p-4 rounded-lg text-center">
        <p class="text-gray-600 mb-3">コメントを投稿するにはログインが必要です</p>
        <div class="space-x-2">
          <a href="{{ route('login') }}" class="btn btn-primary">ログイン</a>
          <a href="{{ route('register') }}" class="btn btn-outline-primary">新規登録</a>
        </div>
      </div>
      @endauth

      <!-- コメント一覧 -->
      <div class="space-y-4">
        @forelse($post->comments()->whereNull('parent_id')->with(['user', 'replies.user'])->latest()->get() as $comment)
        <div class="comment" x-data="{ showReplyForm: false }">
          <div class="flex items-start space-x-3">
            <img src="{{ $comment->user->avatar_url }}" alt="{{ $comment->user->name }}" class="avatar avatar-sm">
            <div class="flex-1">
              <div class="comment-meta">
                <a href="{{ route('profile.show', $comment->user) }}" class="font-semibold text-gray-700 hover:text-primary-500">
                  {{ $comment->user->name }}
                </a>
                <span class="text-gray-500 text-sm">{{ $comment->created_at->diffForHumans() }}</span>
                @auth
                @if(auth()->id() === $comment->user_id || auth()->id() === $post->user_id)
                <form action="{{ route('comments.destroy', $comment) }}" method="POST" class="inline ml-2"
                  onsubmit="return confirm('このコメントを削除しますか？')">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="text-red-500 hover:text-red-700 text-sm">削除</button>
                </form>
                @endif
                @endauth
              </div>
              <div class="comment-body mt-1">{{ $comment->body }}</div>

              @auth
              <button @click="showReplyForm = !showReplyForm"
                class="text-sm text-primary-500 hover:text-primary-600 mt-2">
                返信
              </button>
              @endauth

              <!-- 返信フォーム -->
              @auth
              <div x-show="showReplyForm" x-transition class="reply-form mt-3">
                <form action="{{ route('comments.store', $post->id) }}" method="POST">
                  @csrf
                  <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                  <div class="form-group">
                    <textarea name="body" rows="2" class="form-input" placeholder="返信を入力してください..." required></textarea>
                  </div>
                  <div class="flex space-x-2">
                    <button type="submit" class="btn btn-primary btn-sm">返信する</button>
                    <button type="button" @click="showReplyForm = false" class="btn btn-secondary btn-sm">キャンセル</button>
                  </div>
                </form>
              </div>
              @endauth

              <!-- 返信コメント -->
              @if($comment->replies->count() > 0)
              <div class="mt-3 space-y-3">
                @foreach($comment->replies as $reply)
                <div class="comment reply">
                  <div class="flex items-start space-x-3">
                    <img src="{{ $reply->user->avatar_url }}" alt="{{ $reply->user->name }}" class="avatar avatar-sm">
                    <div class="flex-1">
                      <div class="comment-meta">
                        <a href="{{ route('profile.show', $reply->user) }}" class="font-semibold text-gray-700 hover:text-primary-500">
                          {{ $reply->user->name }}
                        </a>
                        <span class="text-gray-500 text-sm">{{ $reply->created_at->diffForHumans() }}</span>
                        @auth
                        @if(auth()->id() === $reply->user_id || auth()->id() === $post->user_id)
                        <form action="{{ route('comments.destroy', $reply) }}" method="POST" class="inline ml-2"
                          onsubmit="return confirm('このコメントを削除しますか？')">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="text-red-500 hover:text-red-700 text-sm">削除</button>
                        </form>
                        @endif
                        @endauth
                      </div>
                      <div class="comment-body mt-1">{{ $reply->body }}</div>
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
          <i class="fas fa-comments text-4xl text-gray-300 mb-3"></i>
          <p class="text-gray-500">まだコメントがありません</p>
          @auth
          <p class="text-sm text-gray-400 mt-1">最初のコメントを投稿してみませんか？</p>
          @endauth
        </div>
        @endforelse
      </div>
    </div>
  </div>
</x-app-layout>