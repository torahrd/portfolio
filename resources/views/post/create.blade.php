<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>投稿作成</title>
  <style>
    /* 店舗検索のスタイル */
    .shop-search-container {
      position: relative;
      margin-bottom: 20px;
      width: 250px;
    }

    .search-results {
      position: absolute;
      top: 100%;
      left: 0;
      right: 0;
      background: white;
      border: 1px solid #ddd;
      border-top: none;
      max-height: 200px;
      overflow-y: auto;
      z-index: 1000;
      display: none;
    }

    .search-result-item {
      padding: 10px;
      cursor: pointer;
      border-bottom: 1px solid #eee;
    }

    .search-result-item:hover {
      background-color: #f5f5f5;
    }

    .create-new-shop {
      padding: 10px;
      background-color: #e7f3ff;
      color: #0066cc;
      cursor: pointer;
      text-align: center;
      font-weight: bold;
    }

    .create-new-shop:hover {
      background-color: #cce7ff;
    }

    .loading {
      padding: 10px;
      text-align: center;
      color: #666;
    }
  </style>
</head>

<body>
  <h1>投稿作成</h1>

  <form action="/posts" method="post">
    @csrf

    <!-- 店舗検索セクション（新機能） -->
    <div class="shop-search-container">
      <label for="shop-search">店舗を選択:</label>
      <input type="text"
        id="shop-search"
        placeholder="店舗名を入力してください（2文字以上）"
        autocomplete="off"
        style="width: 100%; padding: 8px; margin-top: 5px;">

      <!-- 選択された店舗のIDを保存する隠しフィールド -->
      <input type="hidden" name="post[shop_id]" id="selected-shop-id" required>

      <!-- 検索結果を表示するエリア -->
      <div id="search-results" class="search-results"></div>
    </div>

    <!-- 訪問ステータス -->
    <div>
      <label for="visit">訪問済</label>
      <input type="checkbox" name="post[visit_status]" id="visit" value="1">
      <input type="hidden" name="post[visit_status]" value="0">
    </div>

    <!-- フォルダ選択（既存機能） -->
    <select name="post[folder_id]" id="">
      <option value="" selected disabled>リストに追加</option>
      @foreach ($folders as $folder)
      <option value="{{ $folder->id }}">{{ $folder->name }}</option>
      @endforeach
    </select>

    <!-- 非公開設定 -->
    <div>
      <label for="private">非表示</label>
      <input type="checkbox" name="post[private_status]" id="private" value="1">
      <input type="hidden" name="post[private_status]" value="0">
    </div>

    <!-- 訪問時間 -->
    <div>
      <label for="visit_time">訪問時間</label>
      <input type="datetime-local" name="post[visit_time]" id="visit_time" step="900">
    </div>

    <!-- 予算 -->
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
        <option value="50000">¥30,000〜¥50,000</option>
        <option value="50001">¥50,000〜</option>
      </select>
    </div>

    <!-- リピートメニュー -->
    <div>
      <label for="repeat_menu">リピートメニュー</label>
      <textarea name="post[repeat_menu]" id="repeat_menu"></textarea>
    </div>

    <!-- 気になるメニュー -->
    <div>
      <label for="interest_menu">食べたいメニュー</label>
      <textarea name="post[interest_menu]" id="interest_menu"></textarea>
    </div>

    <!-- 参考リンク -->
    <div>
      <label for="reference_link">参考リンク</label>
      <input type="text" name="post[reference_link]" id="reference_link">
    </div>

    <!-- メモ -->
    <div>
      <textarea name="post[memo]" id="memo" placeholder="メモ"></textarea>
    </div>

    <input type="submit" value="POST">
    <a href="{{ route('posts.index') }}" class="btn btn-secondary">キャンセル</a>
  </form>

  <!-- jQuery読み込み -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <script>
    $(document).ready(function() {
      // AJAX設定（全リクエストに適用）
      $.ajaxSetup({
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

      let searchTimeout;

      // 検索ボックスに入力があったときの処理
      $('#shop-search').on('input', function() {
        const query = $(this).val().trim();

        // 既存のタイムアウトをクリア（連続入力対応）
        clearTimeout(searchTimeout);

        // 2文字未満の場合は検索結果を非表示
        if (query.length < 2) {
          hideResults();
          return;
        }

        // 300ms後に検索実行（入力完了を待つ）
        searchTimeout = setTimeout(function() {
          searchShops(query);
        }, 300);
      });

      // 検索結果エリア以外をクリックしたら結果を非表示
      $(document).on('click', function(e) {
        if (!$(e.target).closest('.shop-search-container').length) {
          hideResults();
        }
      });

      /**
       * 店舗検索を実行
       */
      function searchShops(query) {
        showLoading();

        $.ajax({
          url: '{{ route("shops.search") }}',
          method: 'GET',
          data: {
            query: query
          },
          success: function(response) {
            displayResults(response, query);
          },
          error: function(xhr) {
            if (xhr.status === 422) {
              // バリデーションエラー
              const errors = xhr.responseJSON.errors;
              showError(Object.values(errors).flat().join(' '));
            } else {
              showError('検索に失敗しました');
            }
          }
        });
      }

      /**
       * 検索結果を表示
       */
      function displayResults(response, query) {
        const $results = $('#search-results');
        $results.empty();

        if (response.has_results) {
          // 検索結果がある場合
          response.shops.forEach(function(shop) {
            const $item = $('<div class="search-result-item"></div>')
              .html('<strong>' + shop.name + '</strong><br><small>' + shop.address + '</small>')
              .on('click', function() {
                selectShop(shop);
              });
            $results.append($item);
          });
        } else {
          // 検索結果がない場合：新規作成オプションを表示
          const $createNew = $('<div class="create-new-shop"></div>')
            .text('「' + query + '」という店舗を新規作成')
            .on('click', function() {
              createNewShop(query);
            });
          $results.append($createNew);
        }

        $results.show();
      }

      /**
       * 店舗を選択
       */
      function selectShop(shop) {
        $('#shop-search').val(shop.name + ' - ' + shop.address);
        $('#selected-shop-id').val(shop.id);
        hideResults();
      }

      /**
       * 新しい店舗を作成
       */
      function createNewShop(name) {
        const address = prompt('店舗の住所を入力してください:');
        if (!address) return;

        $.ajax({
          url: '{{ route("shops.store") }}',
          method: 'POST',
          data: {
            name: name,
            address: address
          },
          success: function(response) {
            if (response.success) {
              selectShop(response.shop);
              alert('新しい店舗を作成しました！');
            }
          },
          error: function(xhr) {
            if (xhr.status === 422) {
              const errors = xhr.responseJSON.errors;
              alert('エラー: ' + Object.values(errors).flat().join(' '));
            } else {
              alert('店舗の作成に失敗しました');
            }
          }
        });
      }

      /**
       * ローディング表示
       */
      function showLoading() {
        $('#search-results')
          .html('<div class="loading">検索中...</div>')
          .show();
      }

      /**
       * エラー表示
       */
      function showError(message) {
        $('#search-results')
          .html('<div class="loading" style="color: red;">' + message + '</div>')
          .show();
      }

      /**
       * 検索結果を非表示
       */
      function hideResults() {
        $('#search-results').hide();
      }
    });
  </script>
</body>

</html>