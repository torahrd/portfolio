export function commentSection() {
    return {
        commentContent: "",
        replyContent: "",
        showReplyForm: null,

        init() {
            // データ属性から設定値を取得
            this.postId = this.$el.dataset.postId;
            this.csrfToken = this.$el.dataset.csrfToken;
            this.commentsStoreUrl = this.$el.dataset.commentsStoreUrl;
            this.commentsLikeBaseUrl = this.$el.dataset.commentsLikeBaseUrl;
            this.commentsDeleteBaseUrl = this.$el.dataset.commentsDeleteBaseUrl;
        },

        async submitComment() {
            if (!this.commentContent.trim()) return;
            try {
                const response = await fetch(this.commentsStoreUrl, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": this.csrfToken,
                    },
                    body: JSON.stringify({
                        post_id: this.postId,
                        content: this.commentContent,
                    }),
                });
                // ...（省略：現状のロジックをそのまま移植）
            } catch (e) {
                // ...
            }
        },
        // ...他の関数も同様に移植
    };
}
