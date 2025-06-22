{{--
  コメント返信フォームコンポーネント

  props:
    - comment: コメントのオブジェクト
    - post: 親投稿のオブジェクト
    - level: 返信の階層レベル
    - showReply: 返信フォームの表示状態（x-dataで管理）
--}}
@props([
'comment',
'post',
'level' => 0,
'showReply' => false
])

<div x-show="{{ $showReply }}" class="mt-3" x-transition>
  <form id="reply-form-{{ $comment->id }}" action="{{ route('comments.store', ['post' => $post->id]) }}" method="POST" class="space-y-2 reply-form">
    @csrf
    <input type="hidden" name="post_id" value="{{ $post->id }}">
    <input type="hidden" name="parent_id" value="{{ $level === 0 ? $comment->id : $comment->parent_id }}">
    <textarea name="body" rows="2" maxlength="200" required class="w-full border border-neutral-300 rounded-md p-2 text-sm focus:ring-primary-400 focus:border-primary-400" placeholder="返信を入力（200文字以内）"></textarea>
    <div class="reply-error text-red-600 text-sm hidden"></div>
    <div class="flex justify-end">
      <button type="submit" class="bg-primary-500 text-white px-4 py-1 rounded hover:bg-primary-600 transition-colors text-sm font-semibold">返信する</button>
    </div>
  </form>
</div>