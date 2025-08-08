// 店舗検索用Alpine.js関数（CSP対応版）
export function shopSearchCsp() {
    return {
        searchQuery: "",
        searchResults: [],
        selectedShop: null,
        showResults: false,
        isLoading: false,
        errorMessage: "",
        searchTimeout: null,
        mode: 'post',
        isSelectionValid: false,

        init() {
            console.log('shopSearchCsp initialized');
            this.mode = this.$el.getAttribute('data-mode') || 'post';
            
            // 初期店舗が設定されている場合
            const initialShop = this.$el.getAttribute('data-initial-shop');
            if (initialShop) {
                try {
                    this.selectedShop = JSON.parse(initialShop);
                    this.searchQuery = this.selectedShop.name;
                    this.validateSelection();
                } catch (error) {
                    console.error('Initial shop parsing error:', error);
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

        // 投稿ボタンのクラスを返す（CSP対応）
        getSubmitButtonClass() {
            if (this.isSelectionValid) {
                return 'bg-red-500 hover:bg-red-600';
            }
            return 'bg-gray-400 cursor-not-allowed';
        },

        // シンプルな投稿ボタンクラス（バリデーション不要版）
        getSimpleSubmitButtonClass() {
            if (this.selectedShop) {
                return 'bg-red-500 hover:bg-red-600';
            }
            return 'bg-gray-400 cursor-not-allowed';
        },

        // 選択済み店舗の表示制御（CSP対応版）
        hasSelectedShop() {
            return this.selectedShop !== null;
        },

        isPostMode() {
            return this.mode === 'post';
        },

        isSelectionValid() {
            return this.isSelectionValid;
        },

        isSelectionInvalid() {
            return !this.isSelectionValid;
        },

        // 店舗検索実行
        searchShops() {
            // 入力値が2文字未満の場合は検索しない
            if (this.searchQuery.length < 2) {
                this.searchResults = [];
                this.showResults = false;
                return;
            }

            // デバウンス処理（500ms待機）
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(() => {
                this.performSearch();
            }, 500);
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

        // 実際の検索処理
        async performSearch() {
            this.isLoading = true;
            this.errorMessage = "";
            this.showResults = true;

            try {
                // CSRFトークンを取得
                const csrfToken = await this.getCsrfToken();
                
                if (!csrfToken) {
                    throw new Error('CSRF token not available');
                }

                console.log('CSRF Token:', csrfToken); // デバッグ用

                // 新しいGoogle Places API プロキシを使用
                const response = await fetch("/api/places/search-text", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-Requested-With": "XMLHttpRequest",
                        "X-XSRF-TOKEN": csrfToken
                    },
                    credentials: "include", // これが重要
                    body: JSON.stringify({
                        query: this.searchQuery,
                        language: "ja"
                    })
                });

                const data = await response.json();

                if (response.ok) {
                    // プロキシAPIからの直接レスポンス
                    this.searchResults = data || [];

                    // 検索結果が0件の場合のメッセージ
                    if (this.searchResults.length === 0) {
                        this.errorMessage =
                            "該当する店舗が見つかりませんでした。別のキーワードで検索してください。";
                    }
                } else {
                    this.errorMessage = data.error || "検索に失敗しました";
                    this.searchResults = [];
                }
            } catch (error) {
                console.error("店舗検索エラー:", error);
                this.errorMessage =
                    "検索中にエラーが発生しました。しばらく時間をおいてから再試行してください。";
                this.searchResults = [];
            } finally {
                this.isLoading = false;
            }
        },

        // 店舗選択（CSP対応版 - 引数なし）
        selectShop() {
            // クリックされた要素からshopデータを取得
            const shopIndex = this.$event.target.closest('[data-shop-index]').dataset.shopIndex;
            const shop = this.searchResults[shopIndex];
            
            console.log("selectShop called with:", shop);
            this.selectedShop = shop;
            this.$dispatch("update-selected-shop", { shop: this.selectedShop });
            this.searchQuery = shop.name;
            this.showResults = false;
            this.errorMessage = "";
            console.log("Calling validateSelection from selectShop");
            this.validateSelection();

            // 新規店舗の場合は、Google Places APIから詳細情報を取得
            if (!shop.is_existing && shop.google_place_id) {
                this.fetchShopDetails(shop.google_place_id);
            }
        },

        // 店舗詳細情報取得（新規店舗用）
        async fetchShopDetails(placeId) {
            try {
                // CSRFトークンを取得
                const csrfToken = await this.getCsrfToken();
                
                if (!csrfToken) {
                    throw new Error('CSRF token not available');
                }
                
                const response = await fetch("/api/places/details", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-Requested-With": "XMLHttpRequest",
                        "X-XSRF-TOKEN": csrfToken
                    },
                    credentials: "include",
                    body: JSON.stringify({
                        place_id: placeId,
                        language: "ja"
                    })
                });

                const data = await response.json();

                if (response.ok && data) {
                    // 詳細情報で店舗データを更新
                    this.selectedShop = {
                        ...this.selectedShop,
                        ...this.formatPlaceDetails(data)
                    };
                    this.$dispatch("update-selected-shop", { shop: this.selectedShop });
                }
            } catch (error) {
                console.error("店舗詳細取得エラー:", error);
                // エラーが発生しても選択は維持
            }
        },

        // Google Places APIの詳細情報をフォーマット
        formatPlaceDetails(placeData) {
            return {
                name: placeData.displayName?.text || this.selectedShop.name,
                address:
                    placeData.shortFormattedAddress ||
                    this.selectedShop.address,
                phone: placeData.nationalPhoneNumber || this.selectedShop.phone,
                website: placeData.websiteUri || this.selectedShop.website,
                latitude:
                    placeData.location?.latitude || this.selectedShop.latitude,
                longitude:
                    placeData.location?.longitude ||
                    this.selectedShop.longitude,
                opening_hours: placeData.regularOpeningHours || null,
            };
        },

        // 選択クリア
        clearSelection() {
            this.selectedShop = null;
            this.$dispatch("update-selected-shop", { shop: null });
            this.searchQuery = "";
            this.searchResults = [];
            this.showResults = false;
            this.errorMessage = "";
            this.isSelectionValid = false;
            this.$dispatch("update-selection-valid", { valid: false });
        },

        // 外部クリックで結果を非表示
        handleClickOutside() {
            // CSPビルドでは$eventが使えないため、別の方法で実装
            setTimeout(() => {
                if (!this.$el.contains(document.activeElement)) {
                    this.showResults = false;
                }
            }, 100);
        },

        // 店舗選択の検証
        async validateSelection() {
            console.log(
                "validateSelection called, selectedShop:",
                this.selectedShop
            );

            if (!this.selectedShop) {
                console.log(
                    "No selectedShop, setting isSelectionValid to false"
                );
                this.isSelectionValid = false;
                this.$dispatch("update-selection-valid", { valid: false });
                return;
            }

            try {
                // CSRFトークンを取得
                const csrfToken = await this.getCsrfToken();
                
                if (!csrfToken) {
                    throw new Error('CSRF token not available');
                }
                
                const response = await fetch("/api/shops/validate-selection", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-Requested-With": "XMLHttpRequest",
                        "X-XSRF-TOKEN": csrfToken
                    },
                    credentials: "include",
                    body: JSON.stringify({
                        selected_shop: this.selectedShop,
                    }),
                });

                const data = await response.json();
                console.log("validateSelection response:", data);

                this.isSelectionValid = data.success;
                this.$dispatch("update-selection-valid", {
                    valid: this.isSelectionValid,
                });
                console.log("isSelectionValid set to:", this.isSelectionValid);

                if (!data.success) {
                    this.errorMessage = data.message || "店舗選択が無効です";
                } else {
                    this.errorMessage = "";
                }
            } catch (error) {
                console.error("店舗選択検証エラー:", error);
                this.isSelectionValid = false;
                this.$dispatch("update-selection-valid", { valid: false });
                this.errorMessage = "店舗選択の検証に失敗しました";
            }
        },

        // フォーム送信前の検証
        validateBeforeSubmit() {
            console.log("validateBeforeSubmit called");
            console.log("selectedShop:", this.selectedShop);
            console.log("isSelectionValid:", this.isSelectionValid);

            if (!this.selectedShop) {
                console.log("No selectedShop, showing error");
                this.errorMessage = "店舗を選択してください";
                return false;
            }

            if (!this.isSelectionValid) {
                console.log("isSelectionValid is false, showing error");
                this.errorMessage = "店舗を候補から選択してください";
                return false;
            }

            console.log("Validation passed, returning true");
            return true;
        },

        // 店舗情報取得メソッド（CSP対応版）
        getShopName() {
            return this.selectedShop ? this.selectedShop.name : '';
        },

        getShopAddress() {
            console.log('getShopAddress called, selectedShop:', this.selectedShop);
            if (this.selectedShop) {
                console.log('selectedShop properties:', Object.keys(this.selectedShop));
                console.log('formatted_address:', this.selectedShop.formatted_address);
                console.log('address:', this.selectedShop.address);
            }
            // addressプロパティを優先的に使用
            return this.selectedShop ? (this.selectedShop.address || this.selectedShop.formatted_address || '') : '';
        },

        getPhoneNumberText() {
            if (!this.selectedShop) return '';
            // 安全なプロパティアクセス
            const phoneNumber = this.selectedShop.formatted_phone_number || this.selectedShop.phone_number || '';
            return phoneNumber ? `📞 ${phoneNumber}` : '';
        },

        getShopId() {
            // 既存店舗の場合はid、新規店舗の場合は空
            return this.selectedShop && this.selectedShop.is_existing ? this.selectedShop.id : '';
        },

        getGooglePlaceId() {
            // 新規店舗の場合はgoogle_place_id、既存店舗の場合は空
            return this.selectedShop && !this.selectedShop.is_existing ? this.selectedShop.google_place_id : '';
        },

        getShopNameForForm() {
            return this.selectedShop ? this.selectedShop.name : '';
        },

        getShopAddressForForm() {
            return this.selectedShop ? (this.selectedShop.address || this.selectedShop.formatted_address || '') : '';
        }
    };
}
