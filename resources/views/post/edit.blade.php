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
        <select name="post[budget]" id="budget">
          <!-- 現在の値を表示（ヘルパーで統一） -->
          @if($post->budget)
          <option value="{{ $post->budget }}" selected>
            {{ App\Helpers\BudgetHelper::formatBudget($post->budget) }} (現在の設定)
          </option>
          @else
          <option value="" selected disabled>予算を選択してください</option>
          @endif

          <!-- 選択可能なオプション -->
          <option value="">未設定</option>
          <option value="1000" {{ $post->budget == 1000 ? 'selected' : '' }}>〜¥1,000</option>
          <option value="2000" {{ $post->budget == 2000 ? 'selected' : '' }}>¥1,000〜¥2,000</option>
          <option value="3000" {{ $post->budget == 3000 ? 'selected' : '' }}>¥2,000〜¥3,000</option>
          <option value="5000" {{ $post->budget == 5000 ? 'selected' : '' }}>¥3,000〜¥5,000</option>
          <option value="10000" {{ $post->budget == 10000 ? 'selected' : '' }}>¥5,000〜¥10,000</option>
          <option value="30000" {{ $post->budget == 30000 ? 'selected' : '' }}>¥10,000〜¥30,000</option>
          <option value="50000" {{ $post->budget == 50000 ? 'selected' : '' }}>¥30,000〜¥50,000</option>
          <option value="50001" {{ $post->budget == 50001 ? 'selected' : '' }}>¥50,000〜</option>
        </select>
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
        <a href="{{ route('posts.show', $post->id) }}" class="btn btn-secondary" autofocus>キャンセル</a>
      </div>
    </form>
  </div>
</body>

</html>