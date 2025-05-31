<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>show</title>
</head>

<body>
  <div class="post-detail">
    <h1>{{ $post->shop->name }}</h1>

    <div class="post-info">
      <h3>基本情報</h3>
      <p><strong>住所:</strong> {{ $post->shop->address }}</p>
      <p><strong>訪問日時:</strong> {{ $post->visit_time }}</p>
      <p><strong>訪問済:</strong> {{ $post->visit_status ? 'はい' : 'いいえ' }}</p>
      <p><strong>予算:</strong> {{ number_format($post->budget) }}円</p>
    </div>

    <div class="menus">
      <h3>メニュー情報</h3>
      <p><strong>リピートメニュー:</strong> {{ $post->repeat_menu }}</p>
      <p><strong>気になるメニュー:</strong> {{ $post->interest_menu }}</p>
    </div>

    <div class="memo">
      <h3>メモ</h3>
      <p><strong>メモ:</strong> {{ $post->memo }}</p>
    </div>

    @if($post->reference_link)
    <div class="reference">
      <h3>参考リンク</h3>
      <a href="{{ $post->reference_link }}" target="_blank">{{ $post->reference_link }}</a>
    </div>
    @endif

    <div class="folders">
      <h3>所属フォルダ</h3>
      @if(Auth::check() && $post->user_id === Auth::id())
      @if($post->folders->count() > 0)
      @foreach($post->folders as $folder)
      <span class="badge">{{ $folder->name }}</span>
      @endforeach
      @else
      <p>フォルダに登録されていません</p>
      @endif
      @else
      <p>フォルダ情報は投稿者のみ表示されます</p>
      @endif
    </div>

    <div class="actions">
      <a href="{{ route('posts.index') }}" class="btn btn-secondary">一覧に戻る</a>
      <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-primary">編集</a>
      <form action="{{ route('posts.destroy', $post->id) }}" method="POST" style="display: inline;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger" onclick="return confirm('削除しますか？')">削除</button>
      </form>
    </div>
  </div>
</body>

</html>