<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Post</title>
</head>

<body>
  <h1>title</h1>

  <form action="/post" method="post">
    <div>
      <label for="shopname"></label>
      <input type="text" name="name" id="shopname" placeholder="店舗名"><br>
    </div>
    <div>
      <label for="visit">訪問済</label>
      <input type="checkbox" name="visit_status" id="visit">
    </div>
    <select name="list" id="">
      <option value="" selected disabled>リストに追加</option>
      <option value="MyList">マイリスト</option>
      <option value="favorit">お気に入り</option>
      @foreach ($lists as $list)
      <option value="{{ $list->id }}">{{ $list->title }}</option>
      @endforeach
    </select>
    <div>
      <label for="private">非表示</label>
      <input type="checkbox" name="private_status" id="private">
    </div>
    <div>
      <label for="visit-time">訪問時間</label>
      <input type="time" name="visit-time" id="visit-time" step="900">
    </div>
    <div>
      <label for="budget">予算</label>
      <select name="budget" id="budget">
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
      <label for="repeatmenue">リピートメニュー</label>
      <textarea name="repeatmenue" id="repeatmenue"></textarea>
    </div>
    <div>
      <label for="intarestmenue">食べたいメニュー</label>
      <textarea name="intarestmenue" id="intarestmenue"></textarea>
    </div>
    <div>
      <label for="link">リンク</label>
      <input type="text" name="refarencelink" id="link">
    </div>
    <div><label for="">住所</label></div>
    <div><label for="">営業時間</label></div>
    <div>
      <textarea name="memo" id="memo" placeholder="メモ"></textarea>
    </div>

  </form>
  <button type="submit" value="POST"></button>
</body>

</html>