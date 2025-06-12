import "./bootstrap";
import Alpine from "alpinejs";

// ユーティリティとコンポーネントをインポート
import "./utils/message.js";
import "./utils/form.js";
import "./utils/animations.js";
import "./utils/validation.js";
import "./components/follow.js";
import "./components/modal.js";

// Alpine.jsを開始
window.Alpine = Alpine;
Alpine.start();

// DOMContentLoadedイベントで初期化
document.addEventListener("DOMContentLoaded", function () {
    // CSRF トークンの設定
    const token = document.querySelector('meta[name="csrf-token"]');
    if (token) {
        window.csrfToken = token.getAttribute("content");
    }

    // グローバルなユーザー情報の設定
    const bodyElement = document.body;
    window.isAuthenticated = JSON.parse(
        bodyElement.dataset.authenticated || "false"
    );
    window.currentUserId = bodyElement.dataset.userId
        ? parseInt(bodyElement.dataset.userId)
        : null;

    // フォーム処理の初期化
    FormHandler.initAjaxForm(".ajax-form");

    // コメントフォームの処理
    initCommentForms();

    // 画像プレビュー機能の初期化
    initImagePreviews();

    // ツールチップの初期化
    initTooltips();

    // フォームバリデーションの初期化
    initFormValidations();

    console.log("App initialized successfully");
});

// コメントフォームの初期化
function initCommentForms() {
    document.querySelectorAll(".comment-form form").forEach((form) => {
        form.addEventListener("submit", async (e) => {
            e.preventDefault();

            try {
                const data = await FormHandler.submit(form, {
                    onSuccess: (data) => {
                        // フォームをリセット
                        form.reset();
                        // 必要に応じてページをリロード（コメント一覧の更新）
                        if (data.reload) {
                            window.location.reload();
                        }
                    },
                });
            } catch (error) {
                console.error("Comment submission error:", error);
            }
        });
    });
}

// 画像プレビュー機能
function initImagePreviews() {
    document
        .querySelectorAll('input[type="file"][accept*="image"]')
        .forEach((input) => {
            input.addEventListener("change", (e) => {
                const file = e.target.files[0];
                if (!file) return;

                const reader = new FileReader();
                reader.onload = (e) => {
                    // Alpine.jsのデータを更新
                    const alpineData = Alpine.$data(input.closest("[x-data]"));
                    if (alpineData && "avatarPreview" in alpineData) {
                        alpineData.avatarPreview = e.target.result;
                    }

                    // または直接プレビュー要素を更新
                    const previewElement =
                        document.querySelector("#profilePreview");
                    if (previewElement) {
                        previewElement.src = e.target.result;
                    }
                };
                reader.readAsDataURL(file);
            });
        });
}

// ツールチップの初期化
function initTooltips() {
    document.querySelectorAll("[title]").forEach((element) => {
        // 基本的なツールチップ機能
        element.addEventListener("mouseenter", (e) => {
            const tooltip = document.createElement("div");
            tooltip.className =
                "tooltip absolute z-50 px-2 py-1 text-sm text-white bg-gray-800 rounded shadow-lg";
            tooltip.textContent = e.target.getAttribute("title");

            // title属性を一時的に削除（デフォルトツールチップを防ぐ）
            e.target.removeAttribute("title");
            e.target.dataset.originalTitle = tooltip.textContent;

            document.body.appendChild(tooltip);

            // 位置を計算
            const rect = e.target.getBoundingClientRect();
            tooltip.style.left =
                rect.left + rect.width / 2 - tooltip.offsetWidth / 2 + "px";
            tooltip.style.top = rect.top - tooltip.offsetHeight - 5 + "px";
        });

        element.addEventListener("mouseleave", (e) => {
            // ツールチップを削除
            document
                .querySelectorAll(".tooltip")
                .forEach((tooltip) => tooltip.remove());

            // title属性を復元
            if (e.target.dataset.originalTitle) {
                e.target.setAttribute("title", e.target.dataset.originalTitle);
                delete e.target.dataset.originalTitle;
            }
        });
    });
}

// フォームバリデーション初期化
function initFormValidations() {
    // プロフィール編集フォーム
    const profileForm = document.getElementById("profileForm");
    if (profileForm) {
        new FormValidation(profileForm, ValidationRules.profileEdit);
    }

    // ユーザー登録フォーム
    const registerForm = document.querySelector('form[action*="register"]');
    if (registerForm) {
        new FormValidation(registerForm, ValidationRules.userRegistration);
    }

    // 投稿作成フォーム
    const postForm = document.querySelector('form[action*="posts"]');
    if (postForm) {
        new FormValidation(postForm, ValidationRules.postCreation);
    }

    // コメントフォーム
    document.querySelectorAll(".comment-form form").forEach((form) => {
        new FormValidation(form, ValidationRules.comment);
    });
}

// グローバル関数のエクスポート
window.AppFunctions = {
    scrollToTop: ScrollAnimations.scrollToTop,
    scrollToElement: ScrollAnimations.scrollToElement,
    showMessage: MessageDisplay.show,
    submitForm: FormHandler.submit,
};
