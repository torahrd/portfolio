export function commentSection() {
    return {
        commentContent: "",
        replyContent: "",
        showReplyForm: null,
        postId: null,
        csrfToken: null,
        commentsStoreUrl: null,
        commentsLikeBaseUrl: null,
        commentsDeleteBaseUrl: null,

        init() {
            // データ属性から設定値を取得
            this.postId = this.$el.dataset.postId;
            this.csrfToken = this.$el.dataset.csrfToken;
            this.commentsStoreUrl = this.$el.dataset.commentsStoreUrl;
            this.commentsLikeBaseUrl = this.$el.dataset.commentsLikeBaseUrl;
            this.commentsDeleteBaseUrl = this.$el.dataset.commentsDeleteBaseUrl;
        },

        updateReplyContent(event) {
            this.replyContent = event.target.value;
        },

        updateCommentContent(event) {
            this.commentContent = event.target.value;
        },

        toggleReplyForm(commentId) {
            this.showReplyForm = this.showReplyForm === commentId ? null : commentId;
        },

        closeReplyForm() {
            this.showReplyForm = null;
            this.replyContent = '';
        },

        shouldShowReplyForm(commentId) {
            return this.showReplyForm === commentId;
        },

        shouldShowReplyButton(commentId) {
            return this.showReplyForm !== commentId;
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

        async submitReply(commentId) {
            if (!this.replyContent.trim()) return;
            try {
                const response = await fetch(this.commentsStoreUrl, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": this.csrfToken,
                    },
                    body: JSON.stringify({
                        post_id: this.postId,
                        parent_id: commentId,
                        content: this.replyContent,
                    }),
                });
                // ...（省略：現状のロジックをそのまま移植）
            } catch (e) {
                // ...
            }
        },

        async toggleLike(commentId) {
            try {
                const response = await fetch(`${this.commentsLikeBaseUrl}${commentId}/like`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": this.csrfToken,
                    },
                });
                // ...（省略：現状のロジックをそのまま移植）
            } catch (e) {
                // ...
            }
        },

        async deleteComment(commentId) {
            if (!confirm('このコメントを削除しますか？')) return;
            try {
                const response = await fetch(`${this.commentsDeleteBaseUrl}${commentId}`, {
                    method: "DELETE",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": this.csrfToken,
                    },
                });
                // ...（省略：現状のロジックをそのまま移植）
            } catch (e) {
                // ...
            }
        },
    };
}
