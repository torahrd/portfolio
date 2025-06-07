<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>投稿一覧</title>
  <style>
    .post-item {
      border: 1px solid #ddd;
      margin-bottom: 20px;
      padding: 15px;
      border-radius: 5px;
      background-color: #fff;
    }

    .post-header {
      border-bottom: 1px solid #eee;
      margin-bottom: 10px;
      padding-bottom: 10px;
    }

    .budget {
      color: #e67e22;
      font-weight: bold;
      font-size: 1.1em;
    }

    .comments-section {
      margin-top: 15px;
      padding-top: 15px;
      border-top: 1px solid #eee;
      background-color: #f8f9fa;
      padding: 15px;
      border-radius: 5px;
    }

    .comment-item {
      background-color: #fff;
      padding: 8px 12px;
      margin-bottom: 8px;
      border-radius: 3px;
      font-size: 0.9em;
      border-left: 3px solid #007bff;
    }

    .comment-author {
      font-weight: bold;
      color: #495057;
    }

    .comment-date {
      color: #6c757d;
      font-size: 0.8em;
      margin-left: 10px;
    }

    .comment-body {
      margin-top: 5px;
      line-height: 1.4;
    }

    .view-all-comments {
      text-align: center;
      margin-top: 10px;
    }

    .view-all-comments a {
      color: #007bff;
      text-decoration: none;
      font-size: 0.9em;
    }

    .view-all-comments a:hover {
      text-decoration: underline;
    }
  </style>
</head>

<body>
  <h1>投稿一覧</h1>

  <form action="{{ route('posts.create') }}" method="get">
    <button>投稿</button>
  </form>

  <div class="posts">
    @foreach($posts as $post)
    <div class="post-item">
      <div class="post-header">
        <h2>
          <a href="{{ route('posts.show', $post->id) }}">
            {{ $post->shop->name }}
          </a>
        </h2>
        <p><strong>訪問日時:</strong> {{ $post->visit_time ?? '未設定' }}</p>
        <p><strong>住所:</strong> {{ $post->shop->address }}</p>

        <!-- 改善された予算表示 -->
        <p><strong>予算:</strong>
          <span class="budget">
            {{ App\Helpers\BudgetHelper::formatBudget($post->budget) }}
          </span>
        </p>
      </div>

      <div class="post-content">
        @if($post->repeat_menu)
        <p><strong>リピートメニュー:</strong> {{ $post->repeat_menu }}</p>
        @endif

        @if($post->interest_menu)
        <p><strong>気になるメニュー:</strong> {{ $post->interest_menu }}</p>
        @endif

        @if($post->memo)
        <p><strong>メモ:</strong> {{ $post->memo }}</p>
        @endif
      </div>

      <!-- 最新コメント5件表示（新機能） -->
      @if($post->comments->count() > 0)
      <div class="comments-section">
        <h4>💬 最新のコメント ({{ min($post->comments->count(), 5) }}件表示)</h4>
        @foreach($post->comments->take(5) as $comment)
        <div class="comment-item">
          <div>
            <span class="comment-author">{{ $comment->user->name }}</span>
            <span class="comment-date">{{ $comment->created_at->diffForHumans() }}</span>
          </div>
          <div class="comment-body">{{ Str::limit($comment->body, 100) }}</div>
        </div>
        @endforeach

        @if($post->comments()->count() > 5)
        <div class="view-all-comments">
          <a href="{{ route('posts.show', $post->id) }}#comments">
            すべてのコメントを見る (全{{ $post->comments()->count() }}件)
          </a>
        </div>
        @endif
      </div>
      @else
      <div class="comments-section">
        <p style="color: #6c757d; font-style: italic;">まだコメントがありません</p>
      </div>
      @endif

      <div class="actions" style="margin-top: 15px;">
        <a href="{{ route('posts.show', $post->id) }}" class="btn btn-primary">詳細を見る</a>
        <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-secondary">編集</a>
      </div>
    </div>
    @endforeach
  </div>
</body>

</html>