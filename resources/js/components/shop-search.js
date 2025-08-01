// 店舗検索用Alpine.js関数（CSP対応）
// CSPビルドでは、すべてのメソッドを明示的に定義する必要があります
export function shopSearch() {
    return {
        // データプロパティ
        searchQuery: "",
        searchResults: [],
        selectedShop: null,
        showResults: false,
        isLoading: false,
        errorMessage: "",
        searchTimeout: null,
        mode: "post",
        isSelectionValid: false,

        // 初期化メソッド
        init() {
            console.log('shopSearch component initialized');
            this.selectedShop = null;
            this.searchQuery = "";
            this.searchResults = [];
            this.showResults = false;
            this.isLoading = false;
            this.errorMessage = "";
        },

        // 店舗検索実行
        searchShops() {
            console.log('searchShops called with query:', this.searchQuery);
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

        // 実際の検索処理
        async performSearch() {
            console.log('performSearch called');
            this.isLoading = true;
            this.errorMessage = "";
            this.showResults = true;

            try {
                // Laravel SanctumのSPA認証
                await fetch("/sanctum/csrf-cookie", { credentials: "include" });

                // XSRF-TOKENを取得してデコード
                const token = this.getCookie('XSRF-TOKEN');
                const decodedToken = decodeURIComponent(token);

                // Google Places API プロキシを使用
                const response = await fetch("/api/places/search-text", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-Requested-With": "XMLHttpRequest",
                        "X-XSRF-TOKEN": decodedToken
                    },
                    credentials: "include",
                    body: JSON.stringify({
                        query: this.searchQuery,
                        language: "ja"
                    })
                });

                const data = await response.json();

                if (response.ok) {
                    this.searchResults = data || [];
                    console.log('Search results:', this.searchResults);

                    // 検索結果が0件の場合のメッセージ
                    if (this.searchResults.length === 0) {
                        this.errorMessage = "該当する店舗が見つかりませんでした。別のキーワードで検索してください。";
                    }
                } else {
                    this.errorMessage = data.error || "検索に失敗しました";
                    this.searchResults = [];
                }
            } catch (error) {
                console.error("店舗検索エラー:", error);
                this.errorMessage = "検索中にエラーが発生しました。しばらく時間をおいてから再試行してください。";
                this.searchResults = [];
            } finally {
                this.isLoading = false;
            }
        },

        // Cookie取得ヘルパー関数
        getCookie(name) {
            const value = `; ${document.cookie}`;
            const parts = value.split(`; ${name}=`);
            if (parts.length === 2) return parts.pop().split(';').shift();
            return '';
        },

        // フォールバックメッセージの表示
        showFallbackMessage(message) {
            const fallbackDiv = document.createElement("div");
            fallbackDiv.className = "fixed top-4 right-4 bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded z-40 shadow-lg";
            fallbackDiv.innerHTML = `
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <div class="font-medium text-sm">検索情報</div>
                        <div class="text-xs mt-1">${message}</div>
                    </div>
                </div>
            `;

            document.body.appendChild(fallbackDiv);

            setTimeout(() => {
                if (fallbackDiv.parentNode) {
                    fallbackDiv.parentNode.removeChild(fallbackDiv);
                }
            }, 5000);
        },

        // 店舗選択
        selectShop(shop) {
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
                await fetch("/sanctum/csrf-cookie", { credentials: "include" });
                
                const token = this.getCookie('XSRF-TOKEN');
                const decodedToken = decodeURIComponent(token);
                
                const response = await fetch("/api/places/details", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-Requested-With": "XMLHttpRequest",
                        "X-XSRF-TOKEN": decodedToken
                    },
                    credentials: "include",
                    body: JSON.stringify({
                        place_id: placeId,
                        language: "ja"
                    })
                });

                const data = await response.json();

                if (response.ok && data) {
                    this.selectedShop = {
                        ...this.selectedShop,
                        ...this.formatPlaceDetails(data)
                    };
                    this.$dispatch("update-selected-shop", { shop: this.selectedShop });
                }
            } catch (error) {
                console.error("店舗詳細取得エラー:", error);
            }
        },

        // Google Places APIの詳細情報をフォーマット
        formatPlaceDetails(placeData) {
            return {
                name: placeData.displayName?.text || this.selectedShop.name,
                address: placeData.shortFormattedAddress || this.selectedShop.address,
                phone: placeData.nationalPhoneNumber || this.selectedShop.phone,
                website: placeData.websiteUri || this.selectedShop.website,
                latitude: placeData.location?.latitude || this.selectedShop.latitude,
                longitude: placeData.location?.longitude || this.selectedShop.longitude,
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
        handleClickOutside(event) {
            if (!this.$el.contains(event.target)) {
                this.showResults = false;
            }
        },

        // 店舗選択の検証
        async validateSelection() {
            console.log("validateSelection called, selectedShop:", this.selectedShop);

            if (!this.selectedShop) {
                console.log("No selectedShop, setting isSelectionValid to false");
                this.isSelectionValid = false;
                this.$dispatch("update-selection-valid", { valid: false });
                return;
            }

            try {
                await fetch("/sanctum/csrf-cookie", { credentials: "include" });
                
                const token = this.getCookie('XSRF-TOKEN');
                const decodedToken = decodeURIComponent(token);
                
                const response = await fetch("/api/shops/validate-selection", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-Requested-With": "XMLHttpRequest",
                        "X-XSRF-TOKEN": decodedToken
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
    };
}
