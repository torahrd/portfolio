<!--
  CSP対応テスト用 返信コンポーネント
  @see /resources/js/components/comment-reply-test.js
-->
@props([
    'comment',          // コメントオブジェクト
    'post',             // 親投稿オブジェクト
    'replyPlaceholder'  // 返信のプレースホルダーテキスト
])

<div x-data="commentReplyTest"
     data-comments-store-url="{{ route('comments.store', ['post' => $post->id]) }}"
     data-comment-id="{{ $comment->id }}"
     data-parent-comment-user="{{ $comment->user->name }}"
     class="mt-4">

    <!-- Reply Button -->
    <button x-on:click="toggleReplyForm" class="text-sm text-gray-600 hover:text-gray-900 font-semibold">
        返信する
    </button>

    <!-- Reply Form (Shown on click) -->
    <template x-if="isReplying">
        <div class="mt-4">
            <form @submit.prevent="submitReply">
                <!-- Textarea for Reply -->
                <textarea x-ref="replyTextarea"
                          x-model="replyContent"
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                          rows="3"
                          placeholder="{{ $replyPlaceholder }}"></textarea>

                <!-- Validation Error Message -->
                <template x-if="errors.content">
                    <p x-text="errors.content[0]" class="text-sm text-red-600 mt-1"></p>
                </template>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end mt-2 space-x-2">
                    <button type="button"
                            x-on:click="cancelReply"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        キャンセル
                    </button>
                    <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        返信を送信
                    </button>
                </div>
            </form>
        </div>
    </template>
</div>