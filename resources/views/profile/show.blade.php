{{-- resources/views/profile/show.blade.php --}}
@extends('layouts.app')

@section('title', $user->name . 'のプロフィール')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-4 mb-4">
      {{-- プロフィール情報セクション --}}
      <div class="profile-card">
        <div class="profile-header">
          <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
            class="profile-avatar">
          <h1 class="profile-name">{{ $user->name }}</h1>

          @if($user->bio)
          <p class="profile-bio">{{ $user->bio }}</p>
          @endif

          {{-- プロフィール統計 --}}
          <div class="profile-stats">
            <div class="stat-item">
              <span class="stat-number">{{ $user->posts_count }}</span>
              <span class="stat-label">投稿</span>
            </div>
            <div class="stat-item">
              <a href="{{ route('profile.followers', $user) }}" class="stat-link">
                <span class="stat-number">{{ $user->followers_count }}</span>
                <span class="stat-label">フォロワー</span>
              </a>
            </div>
            <div class="stat-item">
              <a href="{{ route('profile.following', $user) }}" class="stat-link">
                <span class="stat-number">{{ $user->following_count }}</span>
                <span class="stat-label">フォロー中</span>
              </a>
            </div>
          </div>

          {{-- フォローボタン --}}
          @auth
          @if(auth()->id() !== $user->id)
          <div class="profile-actions">
            <button class="btn follow-btn {{ $isFollowing ? 'btn-primary following' : 'btn-outline-primary' }}"
              data-user-id="{{ $user->id }}"
              id="followButton{{ $user->id }}">
              @if($isFollowing)
              <span class="btn-text">フォロー中</span>
              @elseif($hasPendingRequest)
              <span class="btn-text">申請中</span>
              @else
              <span class="btn-text">フォロー</span>
              @endif
            </button>
          </div>
          @else
          <div class="profile-actions">
            <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary">
              プロフィールを編集
            </a>
            @if($user->is_private)
            <button class="btn btn-outline-info" id="generateProfileLink">
              プロフィールリンクを生成
            </button>
            @endif
          </div>
          @endif
          @endauth

          {{-- 追加情報 --}}
          <div class="profile-details">
            @if($user->location)
            <div class="detail-item">
              <i class="fas fa-map-marker-alt"></i>
              <span>{{ $user->location }}</span>
            </div>
            @endif

            @if($user->website)
            <div class="detail-item">
              <i class="fas fa-link"></i>
              <a href="{{ $user->website }}" target="_blank" rel="noopener">
                {{ $user->website }}
              </a>
            </div>
            @endif

            <div class="detail-item">
              <i class="fas fa-calendar-alt"></i>
              <span>{{ $user->created_at->format('Y年n月') }}に参加</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-8">
      {{-- 投稿一覧 --}}
      <div class="posts-section">
        <h2 class="section-title">投稿</h2>

        @if($posts->count() > 0)
        <div class="posts-grid">
          @foreach($posts as $post)
          <div class="post-item">
            <div class="post-header">
              <img src="{{ $post->user->avatar_url }}" alt="{{ $post->user->name }}"
                class="post-avatar">
              <div class="post-info">
                <a href="{{ route('profile.show', $post->user) }}" class="post-author">
                  {{ $post->user->name }}
                </a>
                <span class="post-date">{{ $post->created_at->diffForHumans() }}</span>
              </div>
            </div>
            <div class="post-content">
              <h3 class="post-title">{{ $post->title }}</h3>
              <p class="post-excerpt">{{ Str::limit($post->content, 100) }}</p>
            </div>
          </div>
          @endforeach
        </div>

        {{ $posts->links() }}
        @else
        <div class="no-posts">
          <p>まだ投稿がありません</p>
        </div>
        @endif
      </div>
    </div>
  </div>
</div>

{{-- プロフィールリンク生成モーダル --}}
<div class="modal fade" id="profileLinkModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">プロフィールリンクを生成</h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>24時間有効なプロフィールリンクを生成します。このリンクを使用すると、フォローしていないユーザーでもあなたのプライベートプロフィールを閲覧できます。</p>
        <div id="profileLinkResult" style="display: none;">
          <div class="form-group">
            <label>生成されたリンク:</label>
            <div class="input-group">
              <input type="text" class="form-control" id="profileLinkUrl" readonly>
              <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="button" id="copyLinkBtn">
                  コピー
                </button>
              </div>
            </div>
          </div>
          <small class="text-muted">
            有効期限: <span id="profileLinkExpiry"></span>
          </small>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">閉じる</button>
        <button type="button" class="btn btn-primary" id="generateLinkBtn">リンクを生成</button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/profile.js') }}"></script>
@endpush