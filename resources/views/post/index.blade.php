<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Post</title>
</head>

<body>
  <h1>hello world</h1>

  <form action="/post" method="post">
    <p>店舗名</p>
    <input type="text" name="name" id="" placeholder="店舗名"><br>
    <div>
      <label for="visit">訪問済</label>
      <input type="checkbox" name="visit_status" id="visit">
    </div>
    <select name="list" id="">
      <option value="none">リストに追加</option>
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
    <p>レビュー</p>
    <div id="rating">
      <span></span>
      <span></span>
      <span></span><span></span><span></span>
    </div>

  </form>
</body>

</html>