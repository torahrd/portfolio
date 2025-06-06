<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>user</title>
</head>

<body>
  <!-- {{-- セッションメッセージの表示 --}} -->
  @if (session('success'))
  <div class="alert alert-success">
    {{ session('success') }}
  </div>
  @endif

  <div class="posts">
    @foreach($posts as $post)
    <div class="post">
      <h2>{{ $post->shop->name }}</h2>
      <p>{{ $post->visit_time }}</p>
      <p>{{ $post->shop->address }}</p>
      <p>{{ $post->budget }}</p>
      <p>{{ $post->repeat_menue }}</p>
      <p>{{ $post->interest_menue }}</p>
      <p>{{ $post->memo }}</p>
    </div>
    @endforeach
  </div>
</body>

</html>