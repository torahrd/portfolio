<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>show</title>
  <style>
    /* 既存のスタイルに追加 */
    .comment-section {
      margin-top: 30px;
      padding: 20px;
      border-top: 2px solid #eee;
    }

    .comment {
      background: #f9f9f9;
      padding: 15px;
      margin-bottom: 10px;
      border-radius: 5px;
      border-left: 3px solid #007bff;
    }

    .comment.reply {
      margin-left: 30px;
      border-left-color: #28a745;
      background: #f0f8f0;
    }

    .comment-form {
      background: #f8f9fa;
      padding: 15px;
      border-radius: 5px;
      margin-bottom: 20px;
    }

    .reply-form {
      margin-top: 10px;
      display: none;
      background: #fff;
      padding: 10px;
      border-radius: 3px;
    }

    .btn {
      padding: 8px 12px;
      margin: 2px;
      text-decoration: none;
      border-radius: 4px;
      border: none;
      cursor: pointer;
      font-size: 14px;
    }

    .btn-primary {
      background: #007bff;
      color: white;
    }

    .btn-secondary {
      background: #6c757d;
      color: white;
    }

    .btn-danger {
      background: #dc3545;
      color: white;
    }

    .btn-small {
      padding: 4px 8px;
      font-size: 12px;
    }

    .comment-meta {
      font-size: 12px;
      color: #666;
      margin-bottom: 5px;
    }

    .comment-body {
      margin-bottom: 10px;
      line-height: 1.4;
    }

    .badge {
      background: #007bff;
      color: white;
      padding: 2px 6px;
      border-radius: 3px;
      font-size: 12px;
      margin-right: 5px;
    }

    textarea {
      width: 100%;
      padding: 8px;
      border: 1px solid #ddd;
      border-radius: 4px;
      resize: vertical;
    }

    /* メンション検索ドロップダウン */
    .mention-dropdown {
      position: absolute;
      background: white;
      border: 1px solid #ccc;
      border-radius: 4px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      max-height: 200px;
      overflow-y: auto;
      z-index: 1000;
      display: none;
    }

    .mention-item {
      padding: 8px 12px;
      cursor: pointer;
      font-size: 14px;
      border-bottom: 1px solid #f0f0f0;
    }

    .mention-item:hover,
    .mention-item.selected {
      background-color: #f0f0f0;
    }

    .mention-item:last-child {
      border-bottom: none;
    }

    .mention-loading {
      padding: 8px 12px;
      text-align: center;
      color: #666;
      font-size: 12px;
    }
  </style>
</head>

<body>
  <!-- セッションメッセージの表示 -->
  @if (session('success'))
  <div style="background: #d4edda; color: #155724; padding: 10px; margin: 10px; border-radius: 4px;">
    {{ session('success') }}
  </div>
  @endif

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

  <!-- コメントセクション（YouTube風） -->
  <div class="comment-section">
    <h3>コメント ({{ $post->comments->where('parent_id', null)->count() }})</h3>

    <!-- コメント投稿フォーム -->
    @auth
    <div class="comment-form">
      <h4>コメントを投稿</h4>
      <form action="{{ route('comments.store', $post->id) }}" method="POST">
        @csrf
        <div style="margin-bottom: 10px;">
          <textarea name="body" rows="3" placeholder="コメントを入力してください..." required>{{ old('body') }}</textarea>
          @error('body')
          <div style="color: red; font-size: 12px;">{{ $message }}</div>
          @enderror
        </div>
        <button type="submit" class="btn btn-primary">コメント投稿</button>
      </form>
    </div>
    @else
    <p><a href="{{ route('login') }}">ログイン</a>してコメントを投稿できます。</p>
    @endauth

    <!-- コメント一覧 -->
    <div class="comments">
      @forelse($post->comments()->parentComments()->with(['user'])->orderBy('created_at', 'desc')->get() as $comment)
      <!-- メインコメント -->
      <div class="comment" data-comment-id="{{ $comment->id }}">
        <div class="comment-meta">
          <strong>{{ $comment->user->name }}</strong>
          <span>{{ $comment->created_at->format('Y/m/d H:i') }}</span>
          @if(Auth::check() && (Auth::id() === $comment->user_id || Auth::id() === $post->user_id))
          <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" style="display: inline; float: right;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-small" onclick="return confirm('削除しますか？')">削除</button>
          </form>
          @endif
        </div>
        <div class="comment-body">{!! $comment->body_with_mentions !!}</div>
        @auth
        <button class="reply-toggle-btn btn btn-secondary btn-small" data-comment-id="{{ $comment->id }}">返信</button>
        @endauth

        <!-- 返信フォーム -->
        @auth
        <div id="reply-form-{{ $comment->id }}" class="reply-form">
          <form action="{{ route('comments.store', $post->id) }}" method="POST" style="margin-top: 10px;">
            @csrf
            <input type="hidden" name="parent_id" value="{{ $comment->id }}">
            <textarea name="body" rows="2" placeholder="{{ $comment->user->name }}さんに返信...&#10;💡 @でスレッド参加者を検索" required></textarea>
            <div style="margin-top: 5px;">
              <button type="submit" class="btn btn-primary btn-small">返信投稿</button>
              <button type="button" class="reply-cancel-btn btn btn-secondary btn-small" data-comment-id="{{ $comment->id }}">キャンセル</button>
            </div>
          </form>
        </div>
        @endauth
      </div>

      <!-- このコメント配下の全ての返信（YouTube風に同階層で表示） -->
      @foreach($comment->getAllRepliesFlat() as $reply)
      <div class="comment reply" style="margin-left: 30px; border-left: 2px solid #eee; padding-left: 15px;" data-comment-id="{{ $reply->id }}">
        <div class="comment-meta">
          <strong>{{ $reply->user->name }}</strong>
          <span>{{ $reply->created_at->format('Y/m/d H:i') }}</span>
          @if($reply->parent && $reply->parent->user_id !== $comment->user_id)
          <span style="color: #666;">→ {{ $reply->parent->user->name }}さんへの返信</span>
          @endif
          @if(Auth::check() && (Auth::id() === $reply->user_id || Auth::id() === $post->user_id))
          <form action="{{ route('comments.destroy', $reply->id) }}" method="POST" style="display: inline; float: right;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-small" onclick="return confirm('削除しますか？')">削除</button>
          </form>
          @endif
        </div>
        <div class="comment-body">{!! $reply->body_with_mentions !!}</div>
        @auth
        <button class="reply-toggle-btn btn btn-secondary btn-small" data-comment-id="{{ $reply->id }}">返信</button>
        @endauth

        <!-- 返信に対する返信フォーム -->
        @auth
        <div id="reply-form-{{ $reply->id }}" class="reply-form">
          <form action="{{ route('comments.store', $post->id) }}" method="POST" style="margin-top: 10px;">
            @csrf
            <input type="hidden" name="parent_id" value="{{ $reply->id }}">
            <textarea name="body" rows="2" placeholder="@{{ $reply->user->name }} さんに返信...&#10;💡 @でスレッド参加者を検索" required></textarea>
            <div style="margin-top: 5px;">
              <button type="submit" class="btn btn-primary btn-small">返信投稿</button>
              <button type="button" class="reply-cancel-btn btn btn-secondary btn-small" data-comment-id="{{ $reply->id }}">キャンセル</button>
            </div>
          </form>
        </div>
        @endauth
      </div>
      @endforeach

      @empty
      <p>まだコメントがありません。</p>
      @endforelse
    </div>
  </div>

  <!-- jQuery読み込み -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <script>
    $(document).ready(function() {
      console.log('jQuery loaded successfully');

      // CSRFトークンの確認
      const csrfToken = $('meta[name="csrf-token"]').attr('content');
      console.log('CSRF Token:', csrfToken ? 'Found' : 'Missing');

      // AJAX設定（CSRFトークン付き）
      $.ajaxSetup({
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': csrfToken
        }
      });

      // 返信ボタンのイベントリスナー（data属性を使用）
      $(document).on('click', '.reply-toggle-btn', function() {
        const commentId = $(this).data('comment-id');
        console.log('Reply button clicked for comment:', commentId);
        toggleReplyForm(commentId);
      });

      // キャンセルボタンのイベントリスナー
      $(document).on('click', '.reply-cancel-btn', function() {
        const commentId = $(this).data('comment-id');
        console.log('Cancel button clicked for comment:', commentId);
        toggleReplyForm(commentId);
      });

      // 返信フォームの表示/非表示を切り替える関数
      function toggleReplyForm(commentId) {
        const form = document.getElementById('reply-form-' + commentId);
        if (form) {
          if (form.style.display === 'none' || form.style.display === '') {
            form.style.display = 'block';
            const textarea = form.querySelector('textarea');
            if (textarea) {
              textarea.focus();
            }
          } else {
            form.style.display = 'none';
          }
        } else {
          console.error('Reply form not found for comment:', commentId);
        }
      }

      // メンション検索機能
      const textareas = document.querySelectorAll('textarea[name="body"]');
      let mentionDropdown = null;
      let currentTextarea = null;
      let currentMentionStart = -1;

      console.log('Found textareas:', textareas.length);

      textareas.forEach((textarea, index) => {
        console.log('Setting up textarea', index);

        // コンテナを相対位置に設定
        if (!textarea.closest('.comment-form') && !textarea.closest('.reply-form')) {
          textarea.parentElement.style.position = 'relative';
        }

        textarea.addEventListener('input', function(e) {
          handleMentionInput(this);
        });

        textarea.addEventListener('keydown', function(e) {
          handleMentionKeydown(e, this);
        });

        // フォーカス時の処理
        textarea.addEventListener('focus', function() {
          currentTextarea = this;
          console.log('Textarea focused');
        });
      });

      function handleMentionInput(textarea) {
        // 新規コメントフォームではメンション機能を無効
        if (textarea.closest('.comment-form') !== null) {
          console.log('Mention disabled for main comment form');
          return;
        }

        const value = textarea.value;
        const cursorPos = textarea.selectionStart;

        // @マークを検出
        const beforeCursor = value.substring(0, cursorPos);
        const atIndex = beforeCursor.lastIndexOf('@');

        console.log('Input detected:', {
          value,
          cursorPos,
          atIndex
        });

        if (atIndex !== -1) {
          const afterAt = beforeCursor.substring(atIndex + 1);

          // @の後に空白がない場合のみメンション検索
          if (afterAt.indexOf(' ') === -1 && afterAt.length >= 1) {
            currentMentionStart = atIndex;
            currentTextarea = textarea;
            console.log('Starting mention search for:', afterAt);
            searchUsers(afterAt, textarea);
          } else {
            hideMentionDropdown();
          }
        } else {
          hideMentionDropdown();
        }
      }

      function handleMentionKeydown(e, textarea) {
        // 新規コメントフォームではメンション機能を無効
        if (textarea.closest('.comment-form') !== null) {
          return;
        }

        if (mentionDropdown && mentionDropdown.style.display !== 'none') {
          const items = mentionDropdown.querySelectorAll('.mention-item');
          const selectedItem = mentionDropdown.querySelector('.mention-item.selected');

          if (e.key === 'ArrowDown') {
            e.preventDefault();
            let nextItem = selectedItem ? selectedItem.nextElementSibling : items[0];
            if (!nextItem || nextItem.classList.contains('mention-loading')) {
              nextItem = items[0];
            }
            selectMentionItem(nextItem);
          } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            let prevItem = selectedItem ? selectedItem.previousElementSibling : items[items.length - 1];
            if (!prevItem || prevItem.classList.contains('mention-loading')) {
              prevItem = items[items.length - 1];
            }
            selectMentionItem(prevItem);
          } else if (e.key === 'Enter' && selectedItem && !selectedItem.classList.contains('mention-loading')) {
            e.preventDefault();
            const username = selectedItem.textContent.trim();
            insertMention(username, textarea);
          } else if (e.key === 'Escape') {
            hideMentionDropdown();
          }
        }
      }

      function searchUsers(query, textarea) {
        // 新規コメントフォームではメンション機能を無効（二重チェック）
        if (textarea.closest('.comment-form') !== null) {
          return;
        }

        if (query.length < 1) {
          hideMentionDropdown();
          return;
        }

        console.log('Searching users for query:', query);
        showMentionLoading(textarea);

        // 返信フォームのコンテキスト情報を取得
        const params = getSearchParams(textarea);
        console.log('Search params:', params);

        if (!params.comment_id) {
          console.log('No comment_id found, hiding dropdown');
          hideMentionDropdown();
          return;
        }

        const queryString = new URLSearchParams({
          q: query,
          ...params
        }).toString();

        // 修正: WebルートのURLに変更
        const url = `/users/search?${queryString}`;
        console.log('Fetching from URL:', url);

        // fetch APIの使用
        fetch(url, {
            method: 'GET',
            headers: {
              'X-Requested-With': 'XMLHttpRequest',
              'X-CSRF-TOKEN': csrfToken,
              'Accept': 'application/json',
              'Content-Type': 'application/json'
            }
          })
          .then(response => {
            console.log('Response status:', response.status);

            if (!response.ok) {
              throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
          })
          .then(users => {
            console.log('検索結果受信:', users);
            showMentionDropdown(users, textarea);
          })
          .catch(error => {
            console.error('ユーザー検索エラー:', error);
            showErrorDropdown('検索エラーが発生しました', textarea);
          });
      }

      function getSearchParams(textarea) {
        // 返信フォームのみ対応：parent_comment_idを送信
        const form = textarea.closest('form');
        const parentIdInput = form ? form.querySelector('input[name="parent_id"]') : null;

        if (parentIdInput) {
          console.log('Found parent_id:', parentIdInput.value);
          return {
            comment_id: parentIdInput.value
          };
        }

        console.log('No parent_id found');
        return {};
      }

      function showMentionLoading(textarea) {
        hideMentionDropdown();

        mentionDropdown = document.createElement('div');
        mentionDropdown.className = 'mention-dropdown';
        mentionDropdown.style.display = 'block';

        const loadingItem = document.createElement('div');
        loadingItem.className = 'mention-loading';
        loadingItem.textContent = '検索中...';
        mentionDropdown.appendChild(loadingItem);

        positionDropdown(textarea);
        document.body.appendChild(mentionDropdown);

        console.log('Loading dropdown shown');
      }

      function showErrorDropdown(message, textarea) {
        hideMentionDropdown();

        mentionDropdown = document.createElement('div');
        mentionDropdown.className = 'mention-dropdown';
        mentionDropdown.style.display = 'block';

        const errorItem = document.createElement('div');
        errorItem.className = 'mention-loading';
        errorItem.style.color = 'red';
        errorItem.textContent = message;
        mentionDropdown.appendChild(errorItem);

        positionDropdown(textarea);
        document.body.appendChild(mentionDropdown);

        // 3秒後に非表示
        setTimeout(() => {
          hideMentionDropdown();
        }, 3000);
      }

      function showMentionDropdown(users, textarea) {
        hideMentionDropdown();

        if (!Array.isArray(users) || users.length === 0) {
          console.log('No users found or invalid response');
          showErrorDropdown('ユーザーが見つかりません', textarea);
          return;
        }

        console.log('Showing dropdown for users:', users);

        mentionDropdown = document.createElement('div');
        mentionDropdown.className = 'mention-dropdown';
        mentionDropdown.style.display = 'block';

        users.forEach((user, index) => {
          const item = document.createElement('div');
          item.className = 'mention-item' + (index === 0 ? ' selected' : '');
          item.textContent = user.name;

          item.addEventListener('click', function() {
            insertMention(user.name, textarea);
          });

          item.addEventListener('mouseenter', function() {
            selectMentionItem(this);
          });

          mentionDropdown.appendChild(item);
        });

        positionDropdown(textarea);
        document.body.appendChild(mentionDropdown);
      }

      function positionDropdown(textarea) {
        const rect = textarea.getBoundingClientRect();
        mentionDropdown.style.left = rect.left + 'px';
        mentionDropdown.style.top = (rect.bottom + window.scrollY + 2) + 'px';
        mentionDropdown.style.minWidth = Math.min(rect.width, 200) + 'px';
      }

      function selectMentionItem(item) {
        if (!item || item.classList.contains('mention-loading')) return;

        const selected = mentionDropdown.querySelector('.mention-item.selected');
        if (selected) {
          selected.classList.remove('selected');
        }

        item.classList.add('selected');
      }

      function insertMention(username, textarea) {
        const value = textarea.value;
        const cursorPos = textarea.selectionStart;

        // @マークから現在位置までを置換
        const beforeMention = value.substring(0, currentMentionStart);
        const afterCursor = value.substring(cursorPos);

        const newValue = beforeMention + '@' + username + ' ' + afterCursor;
        textarea.value = newValue;

        // カーソル位置を調整
        const newCursorPos = currentMentionStart + username.length + 2;
        textarea.setSelectionRange(newCursorPos, newCursorPos);

        hideMentionDropdown();
        textarea.focus();

        console.log('Mention inserted:', username);
      }

      function hideMentionDropdown() {
        if (mentionDropdown) {
          mentionDropdown.remove();
          mentionDropdown = null;
        }
        currentMentionStart = -1;
      }

      // ドキュメント外クリックでドロップダウンを閉じる
      document.addEventListener('click', function(e) {
        if (mentionDropdown && !mentionDropdown.contains(e.target) && !e.target.matches('textarea[name="body"]')) {
          hideMentionDropdown();
        }
      });

      // ウィンドウリサイズ時にドロップダウンを閉じる
      window.addEventListener('resize', function() {
        hideMentionDropdown();
      });
    });
  </script>
</body>

</html>