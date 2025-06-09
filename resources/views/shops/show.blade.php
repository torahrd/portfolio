<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ $shop->name }} - 店舗詳細</title>
  <style>
    /* レスポンシブデザイン対応のCSS */
    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 20px;
    }

    .shop-header {
      display: flex;
      align-items: center;
      gap: 15px;
      margin-bottom: 30px;
      flex-wrap: wrap;
    }

    .shop-title {
      font-size: 2.5rem;
      font-weight: bold;
      color: #333;
      margin: 0;
    }

    .favorite-btn {
      background: none;
      border: 2px solid #ffc107;
      padding: 8px 15px;
      border-radius: 25px;
      cursor: pointer;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 16px;
      min-width: 120px;
      justify-content: center;
    }

    .favorite-btn:hover {
      transform: scale(1.05);
      box-shadow: 0 4px 8px rgba(255, 193, 7, 0.3);
    }

    .favorite-btn.favorited {
      background: #ffc107;
      color: #fff;
      border-color: #ffc107;
    }

    .favorite-btn.not-favorited {
      background: #fff;
      color: #ffc107;
      border-color: #ffc107;
    }

    .favorite-star {
      font-size: 20px;
      transition: transform 0.2s ease;
    }

    .favorite-btn:hover .favorite-star {
      transform: scale(1.2);
    }

    .status-indicators {
      display: flex;
      gap: 15px;
      margin-bottom: 20px;
      flex-wrap: wrap;
    }

    .status-badge {
      padding: 8px 16px;
      border-radius: 20px;
      font-weight: bold;
      font-size: 14px;
      display: flex;
      align-items: center;
      gap: 5px;
    }

    .status-open {
      background: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
    }

    .status-closed {
      background: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c6cb;
    }

    .status-unknown {
      background: #e2e3e5;
      color: #6c757d;
      border: 1px solid #d6d8db;
    }

    .info-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 30px;
      margin-bottom: 40px;
    }

    .info-card {
      background: #fff;
      border: 1px solid #dee2e6;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .info-card h3 {
      margin: 0 0 15px 0;
      color: #495057;
      font-size: 1.3rem;
      border-bottom: 2px solid #007bff;
      padding-bottom: 8px;
    }

    .business-hours-table {
      width: 100%;
      border-collapse: collapse;
    }

    .business-hours-table th,
    .business-hours-table td {
      padding: 8px 12px;
      text-align: left;
      border-bottom: 1px solid #dee2e6;
    }

    .business-hours-table th {
      background: #f8f9fa;
      font-weight: bold;
      width: 30%;
    }

    .today-highlight {
      background: #fff3cd !important;
      font-weight: bold;
      color: #856404;
    }

    .posts-section {
      margin-top: 40px;
    }

    .post-item {
      background: #f8f9fa;
      border: 1px solid #dee2e6;
      border-radius: 8px;
      padding: 15px;
      margin-bottom: 15px;
    }

    .post-meta {
      font-size: 0.9rem;
      color: #6c757d;
      margin-bottom: 8px;
    }

    .post-content {
      color: #495057;
    }

    .back-btn {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 10px 20px;
      background: #6c757d;
      color: #fff;
      text-decoration: none;
      border-radius: 5px;
      transition: background 0.3s ease;
    }

    .back-btn:hover {
      background: #5a6268;
    }

    .loading {
      opacity: 0.6;
      pointer-events: none;
    }

    .error-message {
      background: #f8d7da;
      color: #721c24;
      padding: 10px 15px;
      border-radius: 5px;
      margin: 10px 0;
      border: 1px solid #f5c6cb;
    }

    .success-message {
      background: #d4edda;
      color: #155724;
      padding: 10px 15px;
      border-radius: 5px;
      margin: 10px 0;
      border: 1px solid #c3e6cb;
    }

    /* レスポンシブ対応 */
    @media (max-width: 768px) {
      .container {
        padding: 10px;
      }

      .shop-header {
        flex-direction: column;
        align-items: flex-start;
      }

      .shop-title {
        font-size: 2rem;
      }

      .status-indicators {
        justify-content: center;
      }

      .info-grid {
        grid-template-columns: 1fr;
        gap: 20px;
      }
    }
  </style>
</head>

<body>
  <div class="container">
    <!-- メッセージ表示エリア -->
    <div id="message-area"></div>

    <!-- 店舗ヘッダー -->
    <div class="shop-header">
      <h1 class="shop-title">{{ $shop->name }}</h1>

      @auth
      <button id="favorite-btn" class="favorite-btn {{ $isFavorited ? 'favorited' : 'not-favorited' }}"
        data-shop-id="{{ $shop->id }}"
        data-favorited="{{ $isFavorited ? 'true' : 'false' }}">
        <span class="favorite-star">{{ $isFavorited ? '★' : '☆' }}</span>
        <span class="favorite-text">{{ $isFavorited ? 'お気に入り済み' : 'お気に入り' }}</span>
        <span class="favorite-count">({{ $shop->favorites_count }})</span>
      </button>
      @else
      <a href="{{ route('login') }}" class="favorite-btn not-favorited">
        <span class="favorite-star">☆</span>
        <span class="favorite-text">ログインしてお気に入り</span>
        <span class="favorite-count">({{ $shop->favorites_count }})</span>
      </a>
      @endauth
    </div>

    <!-- ステータス表示 -->
    <div class="status-indicators">
      @if($shop->today_business_hours)
      <div class="status-badge {{ $shop->is_open_now ? 'status-open' : 'status-closed' }}">
        <span>{{ $shop->is_open_now ? '🟢' : '🔴' }}</span>
        {{ $shop->open_status }}
      </div>
      @else
      <div class="status-badge status-unknown">
        <span>❓</span>
        営業時間不明
      </div>
      @endif
    </div>

    <!-- 店舗情報グリッド -->
    <div class="info-grid">
      <!-- 基本情報 -->
      <div class="info-card">
        <h3>📍 基本情報</h3>
        <p><strong>住所:</strong> {{ $shop->address }}</p>
        @if($shop->reservation_url)
        <p><strong>予約URL:</strong> <a href="{{ $shop->reservation_url }}" target="_blank">{{ $shop->reservation_url }}</a></p>
        @endif
        <p><strong>お気に入り数:</strong> <span id="favorites-count">{{ $shop->favorites_count }}</span>人</p>
        @if($shop->average_budget)
        <p><strong>平均予算:</strong> {{ $shop->formatted_average_budget }}</p>
        @else
        <p><strong>平均予算:</strong> 予算情報なし</p>
        @endif
      </div>

      <!-- 営業時間 -->
      <div class="info-card">
        <h3>🕒 営業時間</h3>
        @if($shop->business_hours->count() > 0)
        <table class="business-hours-table">
          <thead>
            <tr>
              <th>曜日</th>
              <th>営業時間</th>
            </tr>
          </thead>
          <tbody>
            @foreach($dayNames as $dayIndex => $dayName)
            @php
            $hours = $shop->business_hours->where('day', $dayIndex)->first();
            $isToday = now()->dayOfWeek === $dayIndex;
            @endphp
            <tr class="{{ $isToday ? 'today-highlight' : '' }}">
              <th>{{ $dayName }}曜日 {{ $isToday ? '(今日)' : '' }}</th>
              <td>
                @if($hours)
                {{ \Carbon\Carbon::parse($hours->open_time)->format('H:i') }} -
                {{ \Carbon\Carbon::parse($hours->close_time)->format('H:i') }}
                @else
                定休日
                @endif
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
        @else
        <p>営業時間情報がありません</p>
        @endif
      </div>
    </div>

    <!-- 最近の投稿 -->
    <div class="posts-section">
      <div class="info-card">
        <h3>📝 最近の投稿 ({{ $shop->recent_posts->count() }}件)</h3>
        @if($shop->recent_posts->count() > 0)
        @foreach($shop->recent_posts as $post)
        <div class="post-item">
          <div class="post-meta">
            <strong>{{ $post->user->name }}</strong>さん -
            {{ $post->created_at->format('Y年m月d日') }}
            @if($post->visit_time)
            (訪問: {{ \Carbon\Carbon::parse($post->visit_time)->format('Y年m月d日') }})
            @endif
          </div>
          <div class="post-content">
            @if($post->repeat_menu)
            <p><strong>リピートメニュー:</strong> {{ $post->repeat_menu }}</p>
            @endif
            @if($post->interest_menu)
            <p><strong>気になるメニュー:</strong> {{ $post->interest_menu }}</p>
            @endif
            @if($post->memo)
            <p><strong>メモ:</strong> {{ Str::limit($post->memo, 100) }}</p>
            @endif
            <p>
              <a href="{{ route('posts.show', $post->id) }}" class="post-link">
                詳細を見る →
              </a>
            </p>
          </div>
        </div>
        @endforeach
        @else
        <p>まだ投稿がありません。</p>
        @endif
      </div>
    </div>

    <!-- 戻るボタン -->
    <div style="margin-top: 30px;">
      <a href="javascript:history.back()" class="back-btn">
        ← 戻る
      </a>
    </div>
  </div>

  <!-- jQuery読み込み -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <script>
    $(document).ready(function() {
      // AJAX設定
      $.ajaxSetup({
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

      // お気に入りボタンのクリックイベント
      $('#favorite-btn').on('click', function() {
        const $btn = $(this);
        const shopId = $btn.data('shop-id');
        const isFavorited = $btn.data('favorited') === 'true';

        console.log('Favorite button clicked:', {
          shopId: shopId,
          isFavorited: isFavorited
        });

        // ボタンを無効化（連続クリック防止）
        $btn.addClass('loading').prop('disabled', true);

        // API呼び出し - ★修正: データとメソッドの指定を改善★
        const url = `/shops/${shopId}/favorite`;

        let requestData = {
          url: url,
          success: function(response) {
            console.log('Success response:', response);
            if (response.success) {
              // ボタンの状態を更新
              updateFavoriteButton($btn, response.is_favorited, response.favorites_count);

              // メッセージ表示
              showMessage(response.message, 'success');
            } else {
              showMessage(response.message, 'error');
            }
          },
          error: function(xhr) {
            console.error('Favorite toggle error:', xhr);
            console.error('Response text:', xhr.responseText);

            let errorMessage = 'エラーが発生しました';
            if (xhr.responseJSON && xhr.responseJSON.message) {
              errorMessage = xhr.responseJSON.message;
            }

            showMessage(errorMessage, 'error');
          },
          complete: function() {
            // ボタンを再有効化
            $btn.removeClass('loading').prop('disabled', false);
          }
        };

        if (isFavorited) {
          // お気に入り解除（DELETE）
          requestData.method = 'DELETE';
          requestData.data = {
            _method: 'DELETE', // Laravel用のメソッドスプーフィング
            _token: $('meta[name="csrf-token"]').attr('content')
          };
        } else {
          // お気に入り追加（POST）
          requestData.method = 'POST';
          requestData.data = {
            _token: $('meta[name="csrf-token"]').attr('content')
          };
        }

        $.ajax(requestData);
      });

      /**
       * お気に入りボタンの状態を更新
       */
      function updateFavoriteButton($btn, isFavorited, favoritesCount) {
        console.log('Updating button:', {
          isFavorited: isFavorited,
          favoritesCount: favoritesCount
        });

        // data属性を更新
        $btn.data('favorited', isFavorited);

        // クラスを更新
        if (isFavorited) {
          $btn.removeClass('not-favorited').addClass('favorited');
          $btn.find('.favorite-star').text('★');
          $btn.find('.favorite-text').text('お気に入り済み');
        } else {
          $btn.removeClass('favorited').addClass('not-favorited');
          $btn.find('.favorite-star').text('☆');
          $btn.find('.favorite-text').text('お気に入り');
        }

        // お気に入り数を更新
        $btn.find('.favorite-count').text(`(${favoritesCount})`);
        $('#favorites-count').text(favoritesCount);
      }

      /**
       * メッセージを表示
       */
      function showMessage(message, type) {
        const messageClass = type === 'success' ? 'success-message' : 'error-message';
        const $messageDiv = $(`<div class="${messageClass}">${message}</div>`);

        $('#message-area').empty().append($messageDiv);

        // 3秒後に自動で消去
        setTimeout(() => {
          $messageDiv.fadeOut(300, function() {
            $(this).remove();
          });
        }, 3000);
      }
    });
  </script>
</body>

</html>