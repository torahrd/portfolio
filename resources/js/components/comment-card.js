// CSP対応版コメントカードコンポーネント
export const commentCard = () => ({
    showReplyForm: null,
    replyContent: '',
    
    init() {
        this.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        this.commentsStoreUrl = this.getCommentsStoreUrl();
        this.commentsDeleteBaseUrl = '/comments/';
    },
    
    getCommentsStoreUrl() {
        // URLからpost_idを取得するか、デフォルト値を使用
        const urlParts = window.location.pathname.split('/');
        const postIdIndex = urlParts.indexOf('posts') + 1;
        const postId = urlParts[postIdIndex] || 1;
        return `/posts/${postId}/comments`;
    },
    
    toggleReplyForm(commentId) {
        this.showReplyForm = this.showReplyForm === commentId ? null : commentId;
    },
    
    shouldShowReplyForm(commentId) {
        return this.showReplyForm === commentId;
    },
    
    closeReplyForm() {
        this.showReplyForm = null;
        this.replyContent = '';
    },
    
    async submitReply(commentId) {
        try {
            const response = await fetch(this.commentsStoreUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                },
                body: JSON.stringify({
                    post_id: this.getPostId(),
                    parent_id: commentId,
                    body: this.replyContent
                }),
            });
            
            if (response.ok) {
                window.location.reload();
            }
        } catch (error) {
            console.error('返信投稿エラー:', error);
        }
    },
    
    getPostId() {
        // URLからpost_idを取得するか、デフォルト値を使用
        const urlParts = window.location.pathname.split('/');
        const postIdIndex = urlParts.indexOf('posts') + 1;
        return urlParts[postIdIndex] || 1;
    },
    
    async deleteComment(commentId) {
        if (!confirm('本当にこのコメントを削除しますか？')) return;
        
        try {
            const response = await fetch(`${this.commentsDeleteBaseUrl}${commentId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': this.csrfToken,
                },
            });
            
            if (response.ok) {
                window.location.reload();
            }
        } catch (error) {
            console.error('コメント削除エラー:', error);
        }
    }
}); 