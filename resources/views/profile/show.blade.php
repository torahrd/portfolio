@extends('layouts.app')

@section('content')
  <div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- プロフィールカード（レスポンシブ対応・モーダル統合） -->
      <div class="block lg:hidden mb-6">
        @include('profile.partials.profile-card', ['user' => $user, 'context' => 'mobile'])
      </div>
      <div class="flex flex-col lg:flex-row gap-8">
        <!-- PC用: 左カラム -->
        <aside class="lg:w-1/3 w-full hidden lg:block">
          <div class="sticky top-16" style="min-height: calc(100vh - 6rem);">
            @include('profile.partials.profile-card', ['user' => $user, 'context' => 'desktop'])
          </div>
        </aside>
        <!-- 右カラム: 投稿一覧 -->
        <main class="lg:w-2/3 w-full">
          <div class="bg-white rounded-xl shadow p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-6">投稿 ({{ $user->posts_count }}件)</h2>
            @if($posts->count() > 0)
            <div id="posts-container" class="grid gap-6 md:grid-cols-2">
              @foreach($posts as $post)
              <x-molecules.post-card :post="$post" />
              @endforeach
            </div>
            @if($posts->hasPages())
            <div id="infinite-scroll-trigger" class="mt-8 text-center">
              <div id="loading-indicator" class="hidden">
                <div class="inline-flex items-center px-4 py-2 text-gray-500">
                  <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  読み込み中...
                </div>
              </div>
              <div id="no-more-posts" class="hidden text-gray-500 py-4">
                全ての投稿を表示しました
              </div>
            </div>
            @endif
            @else
            <div class="text-center py-12">
              <div class="text-gray-400 mb-4">
                <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
              </div>
              <p class="text-gray-500 mb-4">まだ投稿がありません</p>
              @if(auth()->check() && auth()->id() === $user->id)
              <a href="{{ route('posts.create') }}" class="btn btn-primary">最初の投稿を作成</a>
              @endif
            </div>
            @endif
          </div>
        </main>
      </div>
    </div>
  </div>
@endsection

@if($posts->hasPages())
@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const postsContainer = document.getElementById('posts-container');
    const infiniteScrollTrigger = document.getElementById('infinite-scroll-trigger');
    const loadingIndicator = document.getElementById('loading-indicator');
    const noMorePosts = document.getElementById('no-more-posts');

    if (!postsContainer || !infiniteScrollTrigger) {
      return;
    }

    let currentPage = 1;
    let isLoading = false;
    let hasMorePages = true;

    // Intersection Observer for infinite scroll
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting && !isLoading && hasMorePages) {
          loadMorePosts();
        }
      });
    }, {
      root: null,
      rootMargin: '100px',
      threshold: 0.1
    });

    // Start observing
    observer.observe(infiniteScrollTrigger);

    async function loadMorePosts() {
      if (isLoading || !hasMorePages) return;

      isLoading = true;
      loadingIndicator.classList.remove('hidden');

      try {
        const nextPage = currentPage + 1;
        const response = await fetch(`{{ route('profile.show', $user) }}?page=${nextPage}`, {
          method: 'GET',
          headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
          }
        });

        if (!response.ok) {
          throw new Error('Failed to load posts');
        }

        const data = await response.json();

        if (data.posts && data.posts.length > 0) {
          // Add new posts to the container
          data.posts.forEach(postHtml => {
            postsContainer.insertAdjacentHTML('beforeend', postHtml);
          });

          currentPage++;

          // Check if there are more pages
          if (!data.hasMorePages) {
            hasMorePages = false;
            observer.unobserve(infiniteScrollTrigger);
            noMorePosts.classList.remove('hidden');
          }
        } else {
          hasMorePages = false;
          observer.unobserve(infiniteScrollTrigger);
          noMorePosts.classList.remove('hidden');
        }

      } catch (error) {
        console.error('Error loading more posts:', error);
      } finally {
        isLoading = false;
        loadingIndicator.classList.add('hidden');
      }
    }
  });
</script>
@endpush
@endif