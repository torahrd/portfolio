// サジェストバー用Alpine.js関数
export function searchBar() {
    return {
        query: "",
        showSuggestions: false,
        suggestions: [],

        init() {
            // データ属性から初期値を取得
            const element = this.$el;
            this.query = element.dataset.initialValue || "";
            this.suggestions = JSON.parse(element.dataset.suggestions || "[]");
        },

        updateSuggestions() {
            // 実装例：リアルタイム検索候補取得
            if (this.query.length >= 2) {
                fetch(
                    `/api/search/suggestions?q=${encodeURIComponent(
                        this.query
                    )}`
                )
                    .then((response) => response.json())
                    .then((data) => {
                        this.suggestions = data.suggestions || [];
                    })
                    .catch((error) => {
                        console.error("検索候補の取得に失敗しました:", error);
                    });
            } else {
                this.suggestions = [];
            }
        },
    };
}

// window登録（Alpine.jsでx-data="searchBar()"として使えるように）
if (typeof window !== "undefined") {
    window.searchBar = searchBar;
}
