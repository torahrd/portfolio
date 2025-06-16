// 店舗詳細画面の共通機能
export class ShopInteractions {
    constructor() {
        this.initFavoriteButton();
        this.initParallax();
        this.initTabSwitching();
    }

    initFavoriteButton() {
        const favoriteBtn = document.getElementById("favorite-btn");
        if (!favoriteBtn) return;

        favoriteBtn.addEventListener(
            "click",
            this.handleFavoriteClick.bind(this)
        );
    }

    async handleFavoriteClick(e) {
        e.preventDefault();

        const button = e.currentTarget;
        const shopId = button.dataset.shopId;
        const isFavorited = button.dataset.favorited === "true";

        try {
            const response = await fetch(`/shops/${shopId}/favorite`, {
                method: isFavorited ? "DELETE" : "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]'
                    ).content,
                    "Content-Type": "application/json",
                    Accept: "application/json",
                },
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            this.updateFavoriteButton(button, data);
        } catch (error) {
            console.error("Favorite error:", error);
            this.showErrorAnimation(button);
        }
    }

    updateFavoriteButton(button, data) {
        const heartIcon = button.querySelector("svg");
        const countElement = button.querySelector(".favorite-count");

        // データ属性更新
        button.dataset.favorited = data.is_favorited;

        // アイコンの状態更新
        if (data.is_favorited) {
            heartIcon.classList.add("text-red-500", "fill-current");
            heartIcon.classList.remove("text-neutral-400");
        } else {
            heartIcon.classList.remove("text-red-500", "fill-current");
            heartIcon.classList.add("text-neutral-400");
        }

        // カウント更新
        countElement.textContent = data.favorite_count;

        // 成功アニメーション
        heartIcon.classList.add("scale-125");
        setTimeout(() => heartIcon.classList.remove("scale-125"), 300);
    }

    showErrorAnimation(button) {
        button.classList.add("animate-pulse");
        setTimeout(() => button.classList.remove("animate-pulse"), 1000);
    }

    initParallax() {
        let ticking = false;

        const updateParallax = () => {
            const scrolled = window.pageYOffset;
            const parallaxElement = document.querySelector(
                '[x-data*="parallax"]'
            );

            if (parallaxElement) {
                const speed = scrolled * 0.5;
                parallaxElement.style.transform = `translateY(${speed}px)`;
            }

            ticking = false;
        };

        const requestParallaxUpdate = () => {
            if (!ticking) {
                requestAnimationFrame(updateParallax);
                ticking = true;
            }
        };

        window.addEventListener("scroll", requestParallaxUpdate);
    }

    initTabSwitching() {
        // AlpineJSと連携したタブ機能の補助
        const tabButtons = document.querySelectorAll(
            '[x-data] button[x-on\\:click*="activeTab"]'
        );

        tabButtons.forEach((button) => {
            button.addEventListener("click", () => {
                // カスタムイベントを発火（必要に応じて）
                window.dispatchEvent(
                    new CustomEvent("tab-switched", {
                        detail: { tab: button.textContent.trim() },
                    })
                );
            });
        });
    }
}

// ページ読み込み時に初期化
document.addEventListener("DOMContentLoaded", () => {
    new ShopInteractions();
});
