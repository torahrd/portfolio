<x-app-layout>
  <div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- モバイル用: プロフィールカード（上部） -->
      <div class="block lg:hidden mb-6">
        @include('profile.partials.profile-card', ['user' => $user, 'context' => 'mobile'])
      </div>
      <div class="flex flex-col lg:flex-row gap-8">
        <!-- PC用: 左カラム -->
        <aside class="lg:w-1/3 w-full hidden lg:block">
          <div class="sticky top-16 h-[calc(100vh-4rem)]">
            @include('profile.partials.profile-card', ['user' => $user, 'context' => 'desktop'])
          </div>
        </aside>
        <!-- 右カラム: 投稿一覧 -->
        <main class="lg:w-2/3 w-full">
          <div class="bg-white rounded-xl shadow p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-6">投稿 ({{ $user->posts_count }}件)</h2>
            @if($posts->count() > 0)
            <div class="grid gap-6 md:grid-cols-2">
              @foreach($posts as $post)
              <x-molecules.post-card :post="$post" />
              @endforeach
            </div>
            @if($posts->hasPages())
            <div class="mt-8">
              {{ $posts->links() }}
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
</x-app-layout>