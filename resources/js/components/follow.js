// フォロー機能のAlpine.jsコンポーネント
document.addEventListener("alpine:init", () => {
    Alpine.data("followComponent", () => ({
        isFollowing: false,
        isPending: false,
        isLoading: false,
        userId: null,

        init() {
            // 初期化
            this.userId = this.$el.dataset.userId;
            this.isFollowing = this.$el.classList.contains("following");
            this.isPending = this.$el.classList.contains("pending");
        },

        async toggleFollow() {
            if (this.isLoading) return;

            this.isLoading = true;

            try {
                const response = await fetch(`/users/${this.userId}/follow`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute("content"),
                    },
                });

                if (response.ok) {
                    const data = await response.json();
                    this.isFollowing = data.isFollowing;
                    this.isPending = data.isPending;

                    // メッセージ表示
                    if (data.message) {
                        window.MessageDisplay.show(data.message, "success");
                    }

                    // 統計の更新
                    this.updateFollowStats(data);
                } else {
                    throw new Error("フォローに失敗しました");
                }
            } catch (error) {
                console.error("Follow error:", error);
                window.MessageDisplay.show("エラーが発生しました", "error");
            } finally {
                this.isLoading = false;
            }
        },

        updateFollowStats(data) {
            // フォロワー数の更新
            const followerCountElements =
                document.querySelectorAll(".followers-count");
            followerCountElements.forEach((el) => {
                if (data.followerCount !== undefined) {
                    el.textContent = data.followerCount;
                }
            });
        },

        get buttonText() {
            if (this.isLoading) return "処理中...";
            if (this.isFollowing) return "フォロー中";
            if (this.isPending) return "申請中";
            return "フォロー";
        },

        get buttonClass() {
            if (this.isFollowing) return "following";
            if (this.isPending) return "pending";
            return "";
        },
    }));
});
