export function commentSection() {
    return {
        commentContent: "",
        replyContent: "",
        showReplyForm: null,
        postId: null,
        csrfToken: null,
        commentsStoreUrl: null,
        commentsDeleteBaseUrl: null,
        isSubmitting: false,

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
            const commentId = this.getCommentIdFromEvent();
            if (!commentId) return;
            
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
            const replyForms = document.querySelectorAll('#comments .reply-form[data-comment-id]');
            
            replyForms.forEach(form => {
                const commentId = parseInt(form.dataset.commentId);
                form.style.display = this.showReplyForm === commentId ? 'block' : 'none';
            });
        },

        async submitComment() {
            if (!this.validateInput(this.commentContent)) return;
            if (this.isSubmitting) return;
            
            this.isSubmitting = true;
            
            try {
                await this.submitData({
                    post_id: this.postId,
                    body: this.commentContent,
                }, (responseData) => {
                    // コメントをDOMに追加
                    this.addCommentToDOM(responseData);
                    // フォームをクリア
                    this.commentContent = '';
                    // textareaも手動でクリア
                    const textarea = document.querySelector('#comment-body');
                    if (textarea) textarea.value = '';
                });
            } finally {
                this.isSubmitting = false;
            }
        },

        async submitReply() {
            const commentId = this.getCommentIdFromEvent();
            if (!commentId) return;
            
            if (!this.validateInput(this.replyContent, 200)) return;
            if (this.isSubmitting) return;
            
            this.isSubmitting = true;
            
            try {
                await this.submitData({
                    post_id: this.postId,
                    parent_id: commentId,
                    body: this.replyContent,
                }, (responseData) => {
                    // 返信をDOMに追加
                    this.addReplyToDOM(responseData, commentId);
                    // フォームをクリア
                    this.replyContent = '';
                    this.showReplyForm = null;
                    this.updateReplyFormVisibility();
                    // textareaも手動でクリア
                    const replyForm = document.querySelector(`.reply-form[data-comment-id="${commentId}"] textarea`);
                    if (replyForm) replyForm.value = '';
                });
            } finally {
                this.isSubmitting = false;
            }
        },

        async deleteComment() {
            const commentId = this.getCommentIdFromEvent();
            if (!commentId) return;
            
            if (!confirm('このコメントを削除しますか？')) return;
            
            try {
                const response = await fetch(`${this.commentsDeleteBaseUrl}${commentId}`, {
                    method: "DELETE",
                    headers: this.getRequestHeaders(),
                });
                
                // PRGパターンのためステータスコードに関わらずリダイレクト
                if (response.ok || response.status === 403 || response.status === 404) {
                    window.location.href = window.location.href;
                } else {
                    this.handleError('削除', response.status);
                }
            } catch (e) {
                console.error('コメント削除エラー:', e);
                alert('通信エラーが発生しました。もう一度お試しください。');
            }
        },

        // ヘルパーメソッド
        getCommentIdFromEvent() {
            if (!this.$event?.currentTarget) {
                console.error('イベントオブジェクトが不正です');
                return null;
            }
            
            const commentId = parseInt(this.$event.currentTarget.dataset.commentId);
            
            if (isNaN(commentId) || commentId <= 0) {
                console.error('無効なコメントIDです');
                return null;
            }
            
            return commentId;
        },

        validateInput(content, maxLength = 200) {
            if (!content.trim()) {
                alert('内容を入力してください');
                return false;
            }
            
            if (content.length > maxLength) {
                alert(`${maxLength}文字以内で入力してください`);
                return false;
            }
            
            return true;
        },

        getRequestHeaders() {
            return {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-CSRF-TOKEN": this.csrfToken,
            };
        },

        async submitData(data, successCallback) {
            try {
                const response = await fetch(this.commentsStoreUrl, {
                    method: "POST",
                    headers: this.getRequestHeaders(),
                    body: JSON.stringify(data),
                });
                
                if (response.ok) {
                    const responseData = await response.json();
                    successCallback(responseData);
                    // window.location.reload()を削除し、DOM更新のみ行う
                } else if (response.status === 422) {
                    const errors = await response.json();
                    const message = errors.errors?.body?.[0] || 'バリデーションエラーが発生しました';
                    alert(message);
                } else {
                    this.handleError('投稿', response.status);
                }
            } catch (e) {
                console.error('通信エラー:', e);
                alert('通信エラーが発生しました。もう一度お試しください。');
            }
        },

        handleError(action, status) {
            console.error(`${action}エラー:`, status);
            alert(`${action}に失敗しました。もう一度お試しください。`);
        },
        
        // DOM操作メソッド
        addCommentToDOM(responseData) {
            const container = document.getElementById('comments-container');
            const noComments = document.getElementById('no-comments');
            
            // 「コメントがありません」表示を削除
            if (noComments) {
                noComments.remove();
            }
            
            // 新しいコメントを先頭に追加
            if (container && responseData.html) {
                container.insertAdjacentHTML('afterbegin', responseData.html);
            }
            
            // コメント数を更新
            this.updateCommentCount(responseData.comment_count);
            
            // 追加したコメントにフェードインアニメーション
            const newComment = container.firstElementChild;
            if (newComment) {
                newComment.style.opacity = '0';
                newComment.style.transition = 'opacity 0.3s ease-in';
                setTimeout(() => {
                    newComment.style.opacity = '1';
                }, 10);
            }
        },
        
        addReplyToDOM(responseData, parentId) {
            const repliesContainer = document.getElementById(`comment-replies-${parentId}`);
            
            // 返信を追加
            if (repliesContainer && responseData.html) {
                repliesContainer.insertAdjacentHTML('beforeend', responseData.html);
                
                // 追加した返信にフェードインアニメーション
                const newReply = repliesContainer.lastElementChild;
                if (newReply) {
                    newReply.style.opacity = '0';
                    newReply.style.transition = 'opacity 0.3s ease-in';
                    setTimeout(() => {
                        newReply.style.opacity = '1';
                    }, 10);
                }
            }
        },
        
        updateCommentCount(count) {
            const countElement = document.getElementById('comment-count');
            if (countElement && count !== undefined) {
                countElement.textContent = count;
            }
        },
        
        removeCommentFromDOM(commentId) {
            // コメント要素を探す
            const commentElement = document.querySelector(`[data-comment-id="${commentId}"]`).closest('.bg-white.rounded-lg');
            
            if (commentElement) {
                // フェードアウトアニメーション
                commentElement.style.transition = 'opacity 0.3s ease-out';
                commentElement.style.opacity = '0';
                
                setTimeout(() => {
                    commentElement.remove();
                    
                    // コメントが0件になったら「コメントがありません」を表示
                    const container = document.getElementById('comments-container');
                    if (container && container.children.length === 0) {
                        container.innerHTML = `
                            <div id="no-comments" class="text-center py-8 text-neutral-500">
                                <svg class="w-16 h-16 mx-auto mb-4 text-neutral-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                                <p>まだコメントがありません</p>
                                <p class="text-sm">最初のコメントを投稿してみませんか？</p>
                            </div>
                        `;
                    }
                }, 300);
            }
        },
    };
}
