<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>user</title>
</head>

<body>
  <form action="{{ route('posts.create') }}" method="get">
    <button>投稿</button>
  </form>

  <div class="posts">
    @foreach($posts as $post)
    <div class="post">
      <h2>
        <a href="{{ route('posts.show', $post->id) }}">
          {{ $post->shop->name }}
        </a>
      </h2>
      <p>訪問日時: {{ $post->visit_time }}</p>
      <p>住所: {{ $post->shop->address }}</p>
      <p>予算: {{ $post->budget }}円</p>
      <p>リピートメニュー: {{ $post->repeat_menu }}</p>
      <p>気になるメニュー: {{ $post->interest_menu }}</p>
      <p>メモ: {{ $post->memo }}</p>

      <div class="actions">
        <a href="{{ route('posts.show', $post->id) }}" class="btn btn-primary">詳細を見る</a>
        <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-secondary">編集</a>
      </div>
    </div>
    @endforeach
  </div>
</body>

</html>