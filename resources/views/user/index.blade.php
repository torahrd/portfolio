<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('ユーザー一覧') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
          <!-- セッションメッセージの表示 -->
          @if (session('success'))
          <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('success') }}
          </div>
          @endif

          <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach($posts as $post)
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow">
              <div class="p-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">
                  {{ $post->shop->name }}
                </h3>
                <div class="space-y-2 text-sm text-gray-600">
                  <p><span class="font-medium">訪問日時:</span> {{ $post->visit_time }}</p>
                  <p><span class="font-medium">住所:</span> {{ $post->shop->address }}</p>
                  <p><span class="font-medium">予算:</span> {{ $post->budget }}</p>
                  @if($post->repeat_menue)
                  <p><span class="font-medium">リピートメニュー:</span> {{ $post->repeat_menue }}</p>
                  @endif
                  @if($post->interest_menue)
                  <p><span class="font-medium">興味のあるメニュー:</span> {{ $post->interest_menue }}</p>
                  @endif
                  @if($post->memo)
                  <p><span class="font-medium">メモ:</span> {{ $post->memo }}</p>
                  @endif
                </div>
              </div>
            </div>
            @endforeach
          </div>

          @if($posts->isEmpty())
          <div class="text-center py-8">
            <p class="text-gray-500">投稿がありません。</p>
          </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</x-app-layout>