
export default () => ({
    // --- Component State ---
    isReplying: false,
    replyContent: '',
    errors: {},

    // --- Data from DOM ---
    commentsStoreUrl: '',
    commentId: null,
    parentCommentUser: '',

    // --- Initialization ---
    init() {
        // Load data from data-* attributes
        this.commentsStoreUrl = this.$el.dataset.commentsStoreUrl;
        this.commentId = this.$el.dataset.commentId;
        this.parentCommentUser = this.$el.dataset.parentCommentUser;

        // Set initial reply content with @mention
        this.replyContent = `@${this.parentCommentUser} `;
    },

    // --- Actions ---
    toggleReplyForm() {
        this.isReplying = !this.isReplying;
        if (this.isReplying) {
            // Focus the textarea when the form appears
            this.$nextTick(() => {
                this.$refs.replyTextarea.focus();
            });
        }
    },

    async submitReply() {
        try {
            const response = await fetch(this.commentsStoreUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    content: this.replyContent,
                    parent_id: this.commentId,
                }),
            });

            if (!response.ok) {
                if (response.status === 422) {
                    const data = await response.json();
                    this.errors = data.errors;
                } else {
                    throw new Error('A network error occurred.');
                }
                return;
            }

            // Reset form and state on success
            this.replyContent = `@${this.parentCommentUser} `;
            this.isReplying = false;
            this.errors = {};

            // For now, reload the page to show the new comment.
            // A more advanced implementation might dynamically insert the new comment.
            window.location.reload();

        } catch (error) {
            console.error('There was an error submitting the reply:', error);
        }
    },

    // --- UI Helpers ---
    cancelReply() {
        this.isReplying = false;
        this.replyContent = `@${this.parentCommentUser} `;
        this.errors = {};
    }
});
