// 店舗検索用Alpine.js関数
export function shopSearch() {
    return {
        searchQuery: "",
        searchResults: [],
        selectedShop: null,
        showResults: false,
        isLoading: false,
        errorMessage: "",
        searchTimeout: null,

        init() {
            // 初期化処理
            this.searchQuery = "";
            this.searchResults = [];
            this.selectedShop = null;
            this.showResults = false;
            this.isLoading = false;
            this.errorMessage = "";
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

        // 実際の検索処理
        async performSearch() {
            this.isLoading = true;
            this.errorMessage = "";
            this.showResults = true;

            try {
                // --- 初心者向け解説 ---
                // Laravel SanctumのSPA認証では、APIリクエスト前に/sanctum/csrf-cookieを呼ぶ必要があります。
                // これによりセッションCookieが発行され、API認証が通るようになります。
                await fetch("/sanctum/csrf-cookie", { credentials: "include" });

                // APIリクエスト時もcredentials: 'include'を指定し、Cookieを送信します。
                const response = await fetch(
                    `/api/shops/search-places?query=${encodeURIComponent(
                        this.searchQuery
                    )}`,
                    {
                        method: "GET",
                        headers: {
                            "Content-Type": "application/json",
                            "X-Requested-With": "XMLHttpRequest",
                        },
                        credentials: "include", // これが重要
                    }
                );

                const data = await response.json();

                if (data.success) {
                    this.searchResults = data.data || [];

                    // フォールバックメッセージの表示
                    if (data.note) {
                        this.showFallbackMessage(data.note);
                    }
                } else {
                    this.errorMessage = data.message || "検索に失敗しました";
                    this.searchResults = [];
                }
            } catch (error) {
                console.error("店舗検索エラー:", error);
                this.errorMessage = "検索中にエラーが発生しました";
                this.searchResults = [];
            } finally {
                this.isLoading = false;
            }
        },

        // フォールバックメッセージの表示
        showFallbackMessage(message) {
            // 一時的なメッセージ表示（3秒後に自動消去）
            const fallbackDiv = document.createElement("div");
            fallbackDiv.className =
                "fixed top-4 right-4 bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded z-50";
            fallbackDiv.innerHTML = `
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-sm">${message}</span>
                </div>
            `;

            document.body.appendChild(fallbackDiv);

            // 3秒後に自動消去
            setTimeout(() => {
                if (fallbackDiv.parentNode) {
                    fallbackDiv.parentNode.removeChild(fallbackDiv);
                }
            }, 3000);
        },

        // 店舗選択
        selectShop(shop) {
            this.selectedShop = shop;
            this.searchQuery = shop.name;
            this.showResults = false;
            this.errorMessage = "";

            // 新規店舗の場合は、Google Places APIから詳細情報を取得
            if (!shop.is_existing && shop.google_place_id) {
                this.fetchShopDetails(shop.google_place_id);
            }
        },

        // 店舗詳細情報取得（新規店舗用）
        async fetchShopDetails(placeId) {
            try {
                // 詳細取得時もSanctum認証を維持
                await fetch("/sanctum/csrf-cookie", { credentials: "include" });
                const response = await fetch(
                    `/api/shops/place-details?place_id=${encodeURIComponent(
                        placeId
                    )}`,
                    {
                        method: "GET",
                        headers: {
                            "Content-Type": "application/json",
                            "X-Requested-With": "XMLHttpRequest",
                        },
                        credentials: "include",
                    }
                );

                const data = await response.json();

                if (data.success) {
                    // 詳細情報で選択された店舗を更新
                    this.selectedShop = {
                        ...this.selectedShop,
                        ...this.formatPlaceDetails(data.data),
                    };
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
            this.searchQuery = "";
            this.searchResults = [];
            this.showResults = false;
            this.errorMessage = "";
        },

        // 外部クリックで結果を非表示
        handleClickOutside(event) {
            if (!this.$el.contains(event.target)) {
                this.showResults = false;
            }
        },
    };
}

// window登録（Alpine.jsでx-data="shopSearch()"として使えるように）
if (typeof window !== "undefined") {
    window.shopSearch = shopSearch;
}
