// スクロールアニメーション
class ScrollAnimations {
    static init() {
        const observerOptions = {
            threshold: 0.1,
            rootMargin: "0px 0px -50px 0px",
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add("visible");
                    // 一度表示されたら監視を停止（パフォーマンス向上）
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        // .animate-on-scroll クラスを持つ要素を監視
        document.querySelectorAll(".animate-on-scroll").forEach((el) => {
            observer.observe(el);
        });
    }

    // ページトップへスムーズスクロール
    static scrollToTop(duration = 500) {
        const start = window.pageYOffset;
        const startTime =
            "now" in window.performance
                ? performance.now()
                : new Date().getTime();

        function scroll() {
            const now =
                "now" in window.performance
                    ? performance.now()
                    : new Date().getTime();
            const time = Math.min(1, (now - startTime) / duration);
            const timeFunction =
                time < 0.5
                    ? 4 * time * time * time
                    : (time - 1) * (2 * time - 2) * (2 * time - 2) + 1;

            window.scroll(0, Math.ceil(timeFunction * (0 - start) + start));

            if (window.pageYOffset !== 0) {
                requestAnimationFrame(scroll);
            }
        }

        scroll();
    }

    // 要素へのスムーズスクロール
    static scrollToElement(element, offset = 0) {
        const elementPosition = element.offsetTop - offset;
        window.scrollTo({
            top: elementPosition,
            behavior: "smooth",
        });
    }
}

// DOMContentLoadedでアニメーション初期化
document.addEventListener("DOMContentLoaded", () => {
    ScrollAnimations.init();
});

// グローバルに公開
window.ScrollAnimations = ScrollAnimations;
