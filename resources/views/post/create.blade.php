<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Post</title>
</head>

<body>
  <h1>title</h1>

  <form action="/posts" method="post">
    @csrf
    <div>
      <select name="post[shop_id]" id="">
        <option value="" selected disabled>店舗を選択</option>
        @foreach ($shops as $shop)
        <option value="{{ $shop->id }}">{{ $shop->name }}</option>
        @endforeach
      </select>
    </div>

    <div>
      <label for="visit">訪問済</label>
      <input type="checkbox" name="post[visit_status]" id="visit" value=1>
      <input type="hidden" name="post[visit_status]" value=0>
    </div>
    <select name="post[folder]" id="">
      <option value="" selected disabled>リストに追加</option>
      @foreach ($folders as $folder)
      <option value="{{ $folder->id }}">{{ $folder->name }}</option>
      @endforeach
    </select>
    <div>
      <label for="private">非表示</label>
      <input type="checkbox" name="post[private_status]" id="visit" value=1>
      <input type="hidden" name="post[private_status]" value=0>
    </div>
    <div>
      <label for="visit_time">訪問時間</label>
      <input type="datetime-local" name="post[visit_time]" id="visit_time" step="900">
    </div>
    <div>
      <label for="budget">予算</label>
      <select name="post[budget]" id="budget">
        <option value="" selected disabled>予算</option>
        <option value="1000">〜¥1,000</option>
        <option value="2000">¥1,000〜¥2,000</option>
        <option value="3000">¥2,000〜¥3,000</option>
        <option value="5000">¥3,000〜¥5,000</option>
        <option value="10000">¥5,000〜¥10,000</option>
        <option value="30000">¥10,000〜¥30,000</option>
        <option value="">¥30,000〜¥50,000</option>
        <option value="">¥50,000〜</option>
      </select>
    </div>
    <div>
      <label for="repeat_menu">リピートメニュー</label>
      <textarea name="post[repeat_menu]" id="repeat_menu"></textarea>
    </div>
    <div>
      <label for="intarest_menu">食べたいメニュー</label>
      <textarea name="post[intarest_menu]" id="intarest_menu"></textarea>
    </div>
    <div>
      <label for="link">リンク</label>
      <input type="text" name="post[refarence_link]" id="refarence_link">
    </div>
    <div><label for="">住所</label></div>
    <div><label for="">営業時間</label></div>
    <div>
      <textarea name="post[memo]" id="memo" placeholder="メモ"></textarea>
    </div>
    <input type="submit" value="POST">

  </form>
</body>

</html>