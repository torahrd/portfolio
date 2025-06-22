{{--
  コメントアクションボタンコンポーネント

  props:
    - comment: コメントのオブジェクト
    - post: 親投稿のオブジェクト
    - showReply: 返信フォームの表示状態（x-dataで管理）
--}}
@props([
'comment',
'post',
'showReply' => false
])

<div class="flex items-center space-x-4 text-xs font-medium">
  @auth
  <button class="text-neutral-600 hover:text-primary-600" @click="{{ $showReply }} = !{{ $showReply }}" :aria-expanded="{{ $showReply }}.toString()">
    返信
  </button>
  @endauth

  @auth
  @if(auth()->id() === $comment->user_id || auth()->id() === $post->user_id)
  <form action="{{ route('comments.destroy', $comment->id) }}"
    method="POST"
    class="inline"
    onsubmit="return confirm('本当にこのコメントを削除しますか？')">
    @csrf
    @method('DELETE')
    <button type="submit"
      class="text-neutral-600 hover:text-red-600">
      削除
    </button>
  </form>
  @endif
  @endauth
</div>