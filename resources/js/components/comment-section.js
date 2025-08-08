export function commentSection() {
    return {
        commentContent: "",
        replyContent: "",
        showReplyForm: null,
        postId: null,
        csrfToken: null,
        commentsStoreUrl: null,
        commentsDeleteBaseUrl: null,

        init() {
            // データ属性から設定値を取得
            this.postId = this.$el.dataset.postId;
            this.csrfToken = this.$el.dataset.csrfToken;
            this.commentsStoreUrl = this.$el.dataset.commentsStoreUrl;
            this.commentsDeleteBaseUrl = this.$el.dataset.commentsDeleteBaseUrl;
            
            // 返信フォームの初期表示制御
            this.updateReplyFormVisibility();
        },

        updateReplyContent(event) {
            this.replyContent = event.target.value;
        },

        updateCommentContent(event) {
            this.commentContent = event.target.value;
        },

        toggleReplyForm() {
            
            // CSP対応: data属性から値を取得
            if (!this.$event || !this.$event.currentTarget) {
                console.error('Event or currentTarget is undefined');
                return;
            }
            
            const commentId = parseInt(this.$event.currentTarget.dataset.commentId);
            
            this.showReplyForm = this.showReplyForm === commentId ? null : commentId;
            
            this.updateReplyFormVisibility();
        },

        closeReplyForm() {
            this.showReplyForm = null;
            this.replyContent = '';
            this.updateReplyFormVisibility();
        },

        // 返信フォームの表示状態を更新
        updateReplyFormVisibility() {
            
            // document全体から検索（一時的な対策）
            const replyForms = document.querySelectorAll('#comments .reply-form[data-comment-id]');
            
            replyForms.forEach(form => {
                const commentId = parseInt(form.dataset.commentId);
                    
                if (this.showReplyForm === commentId) {
                            form.style.display = 'block';
                } else {
                    form.style.display = 'none';
                }
            });
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
                        body: this.commentContent,
                    }),
                });
                if (response.ok) {
                    const data = await response.json();
                    // 成功時の処理
                    this.commentContent = '';
                    window.location.reload();
                }
            } catch (e) {
                console.error('コメント投稿エラー:', e);
            }
        },

        async submitReply() {
            // CSP対応: data属性から値を取得
            const commentId = parseInt(this.$event.currentTarget.dataset.commentId);
            
            // セキュリティ: 入力値の検証
            if (isNaN(commentId) || commentId <= 0) {
                console.error('無効なコメントIDです');
                return;
            }
            
            if (!this.replyContent.trim()) {
                alert('返信内容を入力してください');
                return;
            }
            
            // XSS対策: 文字数制限チェック（クライアント側でも確認）
            if (this.replyContent.length > 200) {
                alert('返信は200文字以内で入力してください');
                return;
            }
            
            try {
                const response = await fetch(this.commentsStoreUrl, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json", // JSON応答を要求
                        "X-CSRF-TOKEN": this.csrfToken,
                    },
                    body: JSON.stringify({
                        post_id: this.postId,
                        parent_id: commentId,
                        body: this.replyContent,
                    }),
                });
                
                if (response.ok) {
                    const data = await response.json();
                    // 成功時の処理
                    this.replyContent = '';
                    this.showReplyForm = null;
                    this.updateReplyFormVisibility();
                    
                    // Ajax処理: ページ全体をリロードせず、該当部分のみ更新
                    // 現状はリロードするが、将来的に部分更新に変更可能
                    window.location.reload();
                } else if (response.status === 422) {
                    // バリデーションエラー
                    const errors = await response.json();
                    const message = errors.errors?.body?.[0] || 'バリデーションエラーが発生しました';
                    alert(message);
                } else {
                    console.error('返信投稿エラー:', response.status);
                    alert('返信の投稿に失敗しました。もう一度お試しください。');
                }
            } catch (e) {
                console.error('返信投稿エラー:', e);
                alert('通信エラーが発生しました。もう一度お試しください。');
            }
        },

        async deleteComment() {
            // CSP対応: data属性から値を取得
            const commentId = parseInt(this.$event.currentTarget.dataset.commentId);
            
            // セキュリティ: 数値チェック
            if (isNaN(commentId) || commentId <= 0) {
                console.error('無効なコメントIDです');
                return;
            }
            
            if (!confirm('このコメントを削除しますか？')) return;
            
            try {
                const response = await fetch(`${this.commentsDeleteBaseUrl}${commentId}`, {
                    method: "DELETE",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json", // JSON応答を要求
                        "X-CSRF-TOKEN": this.csrfToken,
                    },
                });
                
                // ステータスコードに関わらずリダイレクト
                // 403（権限なし）や404（既に削除済み）でもリダイレクト
                if (response.ok || response.status === 403 || response.status === 404) {
                    // 明示的なリダイレクト（PRGパターン）
                    window.location.href = window.location.href;
                } else {
                    // その他のエラーの場合はログに記録
                    console.error('削除エラー:', response.status);
                    alert('削除に失敗しました。もう一度お試しください。');
                }
            } catch (e) {
                console.error('コメント削除エラー:', e);
                alert('通信エラーが発生しました。もう一度お試しください。');
            }
        },
    };
}
