// モーダル機能のAlpine.jsコンポーネント
document.addEventListener("alpine:init", () => {
    Alpine.data("modalComponent", (initialShow = false) => ({
        show: initialShow,

        open() {
            this.show = true;
            document.body.style.overflow = "hidden";
        },

        close() {
            this.show = false;
            document.body.style.overflow = "auto";
        },

        closeOnEscape(event) {
            if (event.key === "Escape") {
                this.close();
            }
        },

        init() {
            // ESCキーでモーダルを閉じる
            document.addEventListener("keydown", (e) => this.closeOnEscape(e));
        },
    }));

    // プロフィールリンクモーダル専用
    Alpine.data("profileLinkModal", () => ({
        show: false,
        generating: false,
        linkUrl: "",
        expiresAt: "",
        copied: false,

        async generateLink() {
            if (this.generating) return;

            this.generating = true;

            try {
                const response = await fetch("/profile/generate-link", {
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
                    this.linkUrl = data.url;
                    this.expiresAt = new Date(data.expires_at).toLocaleString(
                        "ja-JP"
                    );
                    window.MessageDisplay.show(
                        "プロフィールリンクを生成しました",
                        "success"
                    );
                } else {
                    throw new Error("リンク生成に失敗しました");
                }
            } catch (error) {
                console.error("Link generation error:", error);
                window.MessageDisplay.show("リンク生成に失敗しました", "error");
            } finally {
                this.generating = false;
            }
        },

        async copyLink() {
            try {
                await navigator.clipboard.writeText(this.linkUrl);
                this.copied = true;
                window.MessageDisplay.show("リンクをコピーしました", "success");

                // 3秒後にコピー状態をリセット
                setTimeout(() => {
                    this.copied = false;
                }, 3000);
            } catch (error) {
                // フォールバック: 従来の方法
                const textArea = document.createElement("textarea");
                textArea.value = this.linkUrl;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand("copy");
                document.body.removeChild(textArea);

                this.copied = true;
                window.MessageDisplay.show("リンクをコピーしました", "success");

                setTimeout(() => {
                    this.copied = false;
                }, 3000);
            }
        },

        open() {
            this.show = true;
            document.body.style.overflow = "hidden";
        },

        close() {
            this.show = false;
            document.body.style.overflow = "auto";
            // モーダルを閉じる時にリセット
            this.linkUrl = "";
            this.expiresAt = "";
            this.copied = false;
        },
    }));
});
