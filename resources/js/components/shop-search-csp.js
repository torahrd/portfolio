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
        
        // CSP対応: 直接プロパティとして定義
        shopName: '',
        shopAddress: '',
        phoneNumberText: '',
        shopId: '',
        googlePlaceId: '',
        shopNameForForm: '',
        shopAddressForForm: '',
        hasPhoneNumber: false,

        // 初期化
        init() {
            console.log('shopSearchCsp initialized');
            this.mode = this.$el.getAttribute('data-mode') || 'post';
            
            // 初期店舗データがある場合は設定
            const initialShopData = this.$el.getAttribute('data-initial-shop');
            if (initialShopData) {
                try {
                    this.selectedShop = JSON.parse(initialShopData);
                    this.searchQuery = this.selectedShop.name;
                    
                    // CSP対応: プロパティを更新
                    this.updateShopProperties();
                    this.validateSelection();
                    console.log('Initial shop loaded:', this.selectedShop);
                } catch (error) {
                    console.error('Error parsing initial shop data:', error);
                }
            }
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

        // 店舗選択（CSP対応: $eventから取得）
        selectShop() {
            // クリックされた要素からshopデータを取得（CSP対応）
            const shopIndex = this.$event.currentTarget.dataset.shopIndex;
            const shop = this.searchResults[shopIndex];
            
            if (!shop) return;
            
            this.selectedShop = shop;
            this.searchQuery = shop.name;
            this.showResults = false;
            
            // CSP対応: 各プロパティを直接更新
            this.updateShopProperties();
            this.validateSelection();
        },

        // 選択クリア
        clearSelection() {
            this.selectedShop = null;
            this.searchQuery = '';
            this.isSelectionValid = false;
            
            // CSP対応: プロパティをクリア
            this.clearShopProperties();
        },

        // 店舗プロパティ更新
        updateShopProperties() {
            if (this.selectedShop) {
                this.shopName = this.selectedShop.name || '';
                this.shopAddress = this.selectedShop.address || '';
                this.shopId = this.selectedShop.id || '';
                this.googlePlaceId = this.selectedShop.google_place_id || '';
                this.shopNameForForm = this.selectedShop.name || '';
                this.shopAddressForForm = this.selectedShop.address || '';
                this.hasPhoneNumber = !!(this.selectedShop.formatted_phone_number);
                this.phoneNumberText = this.selectedShop.formatted_phone_number 
                    ? `📞 ${this.selectedShop.formatted_phone_number}` 
                    : '';
            }
        },

        // 店舗プロパティクリア
        clearShopProperties() {
            this.shopName = '';
            this.shopAddress = '';
            this.shopId = '';
            this.googlePlaceId = '';
            this.shopNameForForm = '';
            this.shopAddressForForm = '';
            this.hasPhoneNumber = false;
            this.phoneNumberText = '';
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

    };
} 