// shopSearchCspコンポーネント（CSPビルド対応版）
export function shopSearchCsp() {
    return {
        // 基本状態
        searchQuery: '',
        searchResults: [],
        selectedShop: null,
        isLoading: false,
        showResults: false,
        errorMessage: '',
        mode: 'post',
        isSelectionValid: false,

        // 初期化
        init() {
            console.log('shopSearchCsp initialized');
            this.mode = this.$el.getAttribute('data-mode') || 'post';
        },

        // 検索結果表示制御
        showSearchResults() {
            this.showResults = true;
        },

        shouldShowResults() {
            return this.showResults && this.searchResults.length > 0;
        },

        shouldShowSelectedShop() {
            return this.selectedShop && this.mode === 'post';
        },

        shouldShowValidSelection() {
            return this.selectedShop && this.isSelectionValid && this.mode === 'post';
        },

        shouldShowInvalidSelection() {
            return this.selectedShop && !this.isSelectionValid && this.mode === 'post';
        },

        // 検索クエリ更新
        updateSearchQuery(event) {
            this.searchQuery = event.target.value;
            if (this.searchQuery.length > 0) {
                this.searchShops();
            } else {
                this.searchResults = [];
                this.showResults = false;
            }
        },

        // CSRFトークンを取得（改善版）
        async getCsrfToken() {
            try {
                // まずLaravel SanctumのCSRFクッキーを取得
                await fetch('/sanctum/csrf-cookie', {
                    credentials: 'include'
                });

                // クッキーからXSRF-TOKENを取得
                const token = document.cookie
                    .split('; ')
                    .find(row => row.startsWith('XSRF-TOKEN='))
                    ?.split('=')[1];

                if (token) {
                    return decodeURIComponent(token);
                }

                // フォールバック: metaタグから取得
                const metaToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                if (metaToken) {
                    return metaToken;
                }

                console.error('CSRF token not found');
                return null;
            } catch (error) {
                console.error('Error getting CSRF token:', error);
                return null;
            }
        },

        // 店舗検索
        async searchShops() {
            if (this.searchQuery.length < 2) return;

            this.isLoading = true;
            this.errorMessage = '';

            try {
                // CSRFトークンを取得
                const csrfToken = await this.getCsrfToken();
                
                if (!csrfToken) {
                    throw new Error('CSRF token not available');
                }

                console.log('CSRF Token:', csrfToken); // デバッグ用

                // Google Places APIプロキシを使用
                const response = await fetch('/api/places/search-text', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-XSRF-TOKEN': csrfToken
                    },
                    credentials: 'include', // クッキーを含める
                    body: JSON.stringify({
                        query: this.searchQuery,
                        language: 'ja'
                    })
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                
                if (data.success !== false) {
                    this.searchResults = data.shops || data || [];
                    this.showResults = true;
                } else {
                    this.errorMessage = data.message || '検索中にエラーが発生しました。';
                    this.searchResults = [];
                }
            } catch (error) {
                console.error('Search error:', error);
                this.errorMessage = '検索中にエラーが発生しました。しばらく時間をおいてから再試行してください。';
                this.searchResults = [];
            } finally {
                this.isLoading = false;
            }
        },

        // 店舗選択
        selectShop(shop) {
            this.selectedShop = shop;
            this.searchQuery = shop.name;
            this.showResults = false;
            this.validateSelection();
        },

        // 選択クリア
        clearSelection() {
            this.selectedShop = null;
            this.searchQuery = '';
            this.isSelectionValid = false;
        },

        // 選択バリデーション
        validateSelection() {
            if (!this.selectedShop) {
                this.isSelectionValid = false;
                return;
            }

            // 基本的なバリデーション
            const hasName = this.selectedShop.name && this.selectedShop.name.trim() !== '';
            const hasAddress = this.selectedShop.address && this.selectedShop.address.trim() !== '';
            
            this.isSelectionValid = hasName && hasAddress;
        },

        // フォーム用データ取得メソッド
        getShopName() {
            return this.selectedShop ? this.selectedShop.name : '';
        },

        getShopAddress() {
            return this.selectedShop ? this.selectedShop.address : '';
        },

        getPhoneNumberText() {
            return this.selectedShop && this.selectedShop.formatted_phone_number 
                ? `📞 ${this.selectedShop.formatted_phone_number}` 
                : '';
        },

        getShopId() {
            return this.selectedShop ? this.selectedShop.id : '';
        },

        getGooglePlaceId() {
            return this.selectedShop ? this.selectedShop.google_place_id : '';
        },

        getShopNameForForm() {
            return this.selectedShop ? this.selectedShop.name : '';
        },

        getShopAddressForForm() {
            return this.selectedShop ? this.selectedShop.address : '';
        }
    };
} 