// サジェストバー用Alpine.js関数（CSP対応）
export function searchBar() {
    return {
        query: "",
        showSuggestions: false,
        suggestions: [],
        isLoading: false,

        init() {
            // データ属性から初期値を取得
            const element = this.$el;
            this.query = element.dataset.initialValue || "";
            this.suggestions = JSON.parse(element.dataset.suggestions || "[]");
        },

        // CSP対応: 複雑な条件式をメソッドとして外部化
        shouldShowSuggestions() {
            return this.showSuggestions && (this.suggestions.length > 0 || this.isLoading);
        },

        // CSP対応: 結果なしメッセージの表示条件
        shouldShowNoResults() {
            return !this.isLoading && this.suggestions.length === 0 && this.query.length >= 2;
        },

        updateSuggestions() {
            // 入力値が2文字未満の場合は検索しない
            if (this.query.length < 2) {
                this.suggestions = [];
                this.showSuggestions = false;
                return;
            }

            this.isLoading = true;
            this.showSuggestions = true;

            // リアルタイム検索候補取得
            fetch(`/api/search/suggestions?q=${encodeURIComponent(this.query)}`)
                .then((response) => response.json())
                .then((data) => {
                    this.suggestions = data.suggestions || [];
                    this.isLoading = false;
                })
                .catch((error) => {
                    console.error("検索候補の取得に失敗しました:", error);
                    this.suggestions = [];
                    this.isLoading = false;
                });
        },

        selectSuggestion(suggestion) {
            // 店舗選択時の処理
            if (suggestion.category === "店舗") {
                // 検索ページに遷移
                window.location.href = suggestion.url;
            }
        },

        handleClickOutside(event) {
            if (!this.$el.contains(event.target)) {
                this.showSuggestions = false;
            }
        },
    };
}

// window登録（Alpine.jsでx-data="searchBar()"として使えるように）
if (typeof window !== "undefined") {
    window.searchBar = searchBar;
}
