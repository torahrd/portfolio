// 店舗検索用Alpine.js関数
export function shopSearch({ initialShop = null, mode = "post" } = {}) {
    return {
        searchQuery: "",
        searchResults: [],
        selectedShop: null,
        showResults: false,
        isLoading: false,
        errorMessage: "",
        searchTimeout: null,
        mode: mode,
        isSelectionValid: false,

        init() {
            if (initialShop) {
                this.selectedShop = initialShop;
                this.searchQuery = initialShop.name;
                this.validateSelection();
            } else {
                this.selectedShop = null;
                this.searchQuery = "";
            }
            this.searchResults = [];
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

                    // フォールバックメッセージの表示（Google Places APIが失敗した場合）
                    if (data.note) {
                        this.showFallbackMessage(data.note);
                    }

                    // 検索結果が0件の場合のメッセージ
                    if (this.searchResults.length === 0) {
                        this.errorMessage =
                            "該当する店舗が見つかりませんでした。別のキーワードで検索してください。";
                    }
                } else {
                    this.errorMessage = data.message || "検索に失敗しました";
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

        // フォールバックメッセージの表示
        showFallbackMessage(message) {
            // 一時的なメッセージ表示（5秒後に自動消去）
            const fallbackDiv = document.createElement("div");
            fallbackDiv.className =
                "fixed top-4 right-4 bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded z-50 shadow-lg";
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

            // 5秒後に自動消去
            setTimeout(() => {
                if (fallbackDiv.parentNode) {
                    fallbackDiv.parentNode.removeChild(fallbackDiv);
                }
            }, 5000);
        },

        // 店舗選択
        selectShop(shop) {
            this.selectedShop = shop;
            this.searchQuery = shop.name;
            this.showResults = false;
            this.errorMessage = "";
            this.validateSelection();

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
                    this.validateSelection();
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
            this.isSelectionValid = false;
        },

        // 外部クリックで結果を非表示
        handleClickOutside(event) {
            if (!this.$el.contains(event.target)) {
                this.showResults = false;
            }
        },

        // 店舗選択の検証
        async validateSelection() {
            if (!this.selectedShop) {
                this.isSelectionValid = false;
                return;
            }

            try {
                await fetch("/sanctum/csrf-cookie", { credentials: "include" });
                const response = await fetch("/api/shops/validate-selection", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-Requested-With": "XMLHttpRequest",
                    },
                    credentials: "include",
                    body: JSON.stringify({
                        selected_shop: this.selectedShop,
                    }),
                });

                const data = await response.json();
                this.isSelectionValid = data.success;

                if (!data.success) {
                    this.errorMessage = data.message || "店舗選択が無効です";
                } else {
                    this.errorMessage = "";
                }
            } catch (error) {
                console.error("店舗選択検証エラー:", error);
                this.isSelectionValid = false;
                this.errorMessage = "店舗選択の検証に失敗しました";
            }
        },

        // フォーム送信前の検証
        validateBeforeSubmit() {
            if (!this.selectedShop) {
                this.errorMessage = "店舗を選択してください";
                return false;
            }

            if (!this.isSelectionValid) {
                this.errorMessage = "店舗を候補から選択してください";
                return false;
            }

            return true;
        },
    };
}

// window登録（Alpine.jsでx-data="shopSearch()"として使えるように）
if (typeof window !== "undefined") {
    window.shopSearch = shopSearch;
}
