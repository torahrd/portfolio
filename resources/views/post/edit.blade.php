<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Post</title>
</head>

<body>
  <div class="container">
    <h1>投稿を編集</h1>

    <form action="{{ route('posts.update', $post->id) }}" method="POST">
      @csrf
      @method('PUT')

      <div class="form-group">
        <label for="shop_id">店舗</label>
        <select name="post[shop_id]" id="shop_id" class="form-control" required>
          <option value="">店舗を選択してください</option>
          @foreach($shops as $shop)
          <option value="{{ $shop->id }}" {{ $post->shop_id == $shop->id ? 'selected' : '' }}>
            {{ $shop->name }}
          </option>
          @endforeach
        </select>
      </div>

      <div class="form-group">
        <label for="visit_time">訪問日時</label>
        <input
          type="datetime-local"
          name="post[visit_time]"
          id="visit_time"
          class="form-control"
          value="{{ $post->visit_time ? date('Y-m-d\TH:i', strtotime($post->visit_time)) : '' }}"
          step="300">
      </div>

      <div class="form-group">
        <label for="visit_status">訪問済</label>
        <input type="checkbox" name="post[visit_status]" id="visit_status" value="1" {{ $post->visit_status ? 'checked' : '' }}>
        <input type="hidden" name="post[visit_status]" value="0">
      </div>

      <div class="form-group">
        <label for="budget">予算</label>
        <input
          type="number"
          name="post[budget]"
          id="budget"
          class="form-control"
          value="{{ $post->budget }}"
          min="0"
          step="100">
      </div>

      <div class="form-group">
        <label for="repeat_menu">リピートメニュー</label>
        <textarea
          name="post[repeat_menu]"
          id="repeat_menu"
          class="form-control"
          rows="3">{{ $post->repeat_menu }}</textarea>
      </div>

      <div class="form-group">
        <label for="interest_menu">気になるメニュー</label>
        <textarea
          name="post[interest_menu]"
          id="interest_menu"
          class="form-control"
          rows="3">{{ $post->interest_menu }}</textarea>
      </div>

      <div class="form-group">
        <label for="memo">メモ</label>
        <textarea
          name="post[memo]"
          id="memo"
          class="form-control"
          rows="3">{{ $post->memo }}</textarea>
      </div>

      <div class="form-group">
        <label for="reference_link">参考リンク</label>
        <input
          type="url"
          name="post[reference_link]"
          id="reference_link"
          class="form-control"
          value="{{ $post->reference_link }}">
      </div>

      <div class="form-group">
        <label for="private_status">非公開</label>
        <input type="checkbox" name="post[private_status]" id="private_status" value="1" {{ $post->private_status ? 'checked' : '' }}>
        <input type="hidden" name="post[private_status]" value="0">
      </div>

      <div class="form-group">
        <label>フォルダ</label>
        @auth
        @foreach($folders as $folder)
        <div class="form-check">
          <input
            type="checkbox"
            name="folders[]"
            value="{{ $folder->id }}"
            id="folder_{{ $folder->id }}"
            class="form-check-input"
            {{ $post->folders->contains($folder->id) ? 'checked' : '' }}>
          <label for="folder_{{ $folder->id }}" class="form-check-label">
            {{ $folder->name }}
          </label>
        </div>
        @endforeach
        @endauth
      </div>

      <div class="form-actions">
        <button type="submit" class="btn btn-primary">更新</button>
        <a href="{{ route('posts.show', $post->id) }}" class="btn btn-secondary">キャンセル</a>
      </div>
    </form>
  </div>
</body>

</html>