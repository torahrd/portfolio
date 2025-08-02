// サジェストバー用Alpine.js関数（CSP対応版）
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

        // CSP対応: 投稿数がある場合の表示条件
        hasPosts() {
            return this.suggestion && this.suggestion.post_count > 0;
        },

        // CSP対応: 投稿数がない場合の表示条件
        hasNoPosts() {
            return this.suggestion && this.suggestion.post_count === 0;
        },

        // CSP対応: 入力値更新メソッド
        updateQuery(event) {
            this.query = event.target.value;
            this.updateSuggestions();
        },

        // CSP対応: フォーカス時の処理
        showSuggestionsOnFocus() {
            this.showSuggestions = true;
        },

        // CSRFトークン取得
        async getCsrfToken() {
            try {
                await fetch('/sanctum/csrf-cookie', { credentials: 'include' });
                const token = document.cookie.split('; ').find(row => row.startsWith('XSRF-TOKEN='))?.split('=')[1];
                if (token) { return decodeURIComponent(token); }
                const metaToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                if (metaToken) { return metaToken; }
                console.error('CSRF token not found'); return null;
            } catch (error) { console.error('Error getting CSRF token:', error); return null; }
        },

        async updateSuggestions() {
            // 入力値が2文字未満の場合は検索しない
            if (this.query.length < 2) {
                this.suggestions = [];
                this.showSuggestions = false;
                return;
            }

            this.isLoading = true;
            this.showSuggestions = true;

            try {
                const csrfToken = await this.getCsrfToken();
                if (!csrfToken) { throw new Error('CSRF token not available'); }

                // shopSearchCspと同じAPIエンドポイントを使用
                const response = await fetch('/api/places/search-text', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-XSRF-TOKEN': csrfToken
                    },
                    credentials: 'include',
                    body: JSON.stringify({ query: this.query, language: 'ja' })
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                console.log('API response data for search suggestions:', data);
                
                // Google Places APIのレスポンス形式に合わせて変換
                if (data && Array.isArray(data)) {
                    this.suggestions = data.map(place => {
                        const existingShop = place.is_existing ? place.id : null;
                        const postCount = place.post_count || 0;
                        
                        return {
                            title: place.name || place.title || '',
                            subtitle: place.formatted_address || place.address || place.subtitle || '',
                            category: '店舗',
                            url: existingShop ? `/shops/${existingShop}` : `/search?q=${encodeURIComponent(place.name)}`,
                            post_count: postCount,
                            is_existing: place.is_existing || false
                        };
                    });
                } else {
                    console.error('Unexpected API response format:', data);
                    this.suggestions = [];
                }

                console.log('Processed suggestions:', this.suggestions);

            } catch (error) {
                console.error('Error fetching suggestions:', error);
                this.suggestions = [];
                this.showSuggestions = false;
            } finally {
                this.isLoading = false;
            }
        },

        selectSuggestion() {
            // CSP対応: data-suggestion-indexからsuggestionを取得
            const suggestionIndex = this.$event.target.closest('[data-suggestion-index]').dataset.suggestionIndex;
            const suggestion = this.suggestions[suggestionIndex];
            
            console.log('selectSuggestion called with:', suggestion);
            
            // 店舗選択時の処理
            if (suggestion && suggestion.category === "店舗") {
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
