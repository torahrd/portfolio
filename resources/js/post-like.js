/**
 * 投稿のいいね機能
 */
document.addEventListener("DOMContentLoaded", function () {
    // いいねボタンのクリックイベントを設定
    document.addEventListener("click", function (event) {
        const button = event.target.closest(".like-button");
        if (!button) return;

        // SVG取得の堅牢化: クリックされた要素がlike-iconならそれを使う
        let icon = null;
        if (
            event.target.classList &&
            event.target.classList.contains("like-icon")
        ) {
            icon = event.target;
        } else {
            icon = button.querySelector(".like-icon");
        }
        if (!icon) {
            showError("「いいね」ボタンの表示に問題が発生しました。");
            console.error("like-iconが見つかりません", button, event.target);
            return;
        }

        event.preventDefault();
        handleLikeClick(button);
    });
});

/**
 * いいねボタンのクリック処理
 */
function handleLikeClick(button) {
    // 未ログインの場合は処理しない
    if (button.hasAttribute("disabled")) {
        return;
    }

    const postId = button.dataset.postId;
    const isFavorited = button.dataset.isFavorited === "true";

    // ボタンを一時的に無効化（二重クリック防止）
    button.disabled = true;

    // ローディング状態の表示（構造を維持しつつアニメーションのみ付与）
    const icon = button.querySelector(".like-icon");
    const countElement = button.querySelector(".like-count");
    if (icon) icon.classList.add("animate-spin");

    // APIリクエストの設定
    const url = `/posts/${postId}/favorite`;
    const method = isFavorited ? "DELETE" : "POST";

    // CSRFトークンの取得
    const token = document
        .querySelector('meta[name="csrf-token"]')
        ?.getAttribute("content");

    fetch(url, {
        method: method,
        headers: {
            "Content-Type": "application/json",
            Accept: "application/json",
            "X-CSRF-TOKEN": token,
        },
    })
        // サーバーからのレスポンスが必ずJSONとは限らない場合（例: セッション切れでHTMLが返る等）
        .then(async (response) => {
            const contentType = response.headers.get("content-type");
            if (contentType && contentType.includes("application/json")) {
                return response.json();
            } else {
                // 予期しないHTMLや空レスポンスの場合はcatchに送る
                throw new Error("サーバーから不正なレスポンスが返されました");
            }
        })
        .then((data) => {
            if (data.success) {
                updateLikeUI(button, data.is_favorited, data.favorites_count);
            } else {
                showError(
                    data.message ||
                        "いいねの処理に失敗しました。しばらく時間をおいてから再度お試しください。"
                );
            }
        })
        .catch((error) => {
            // エラー内容を詳細に出力し、セッション切れ等も考慮
            console.error("いいねAPIエラー:", error);
            showError(
                error.message.includes("CSRF") ||
                    error.message.includes("token")
                    ? "セッションが切れています。再度ログインしてください。"
                    : error.message.includes("不正なレスポンス")
                    ? "サーバーから不正なレスポンスが返されました。再度ログインするか、時間をおいてお試しください。"
                    : "ネットワークエラーが発生しました。しばらく時間をおいてから再度お試しください。"
            );
        })
        .finally(() => {
            // ボタンを再度有効化
            button.disabled = false;
            if (icon) icon.classList.remove("animate-spin");
        });
}

/**
 * いいねUIの更新
 */
function updateLikeUI(button, isFavorited, favoritesCount) {
    try {
        // SVGアイコンが見つからない場合はUI更新を中断し、エラー表示
        const icon = button.querySelector(".like-icon");
        if (!icon) {
            // 一般ユーザー向けの分かりやすい文言でエラー表示
            showError(
                "「いいね」ボタンの表示に問題が発生しました。ページを再読み込みしてください。"
            );
            console.error("like-iconが見つかりません", button);
            return;
        }
        const countElement = button.querySelector(".like-count");

        // いいね状態の更新
        button.dataset.isFavorited = isFavorited.toString();

        // アイコンの更新
        if (isFavorited) {
            icon.setAttribute("fill", "currentColor");
            button.classList.remove("text-neutral-500", "hover:text-red-500");
            button.classList.add("text-red-500");
        } else {
            icon.setAttribute("fill", "none");
            button.classList.remove("text-red-500");
            button.classList.add("text-neutral-500", "hover:text-red-500");
        }

        // カウントの更新
        countElement.textContent = favoritesCount;

        // アニメーション効果
        button.classList.add("scale-110");
        setTimeout(() => {
            button.classList.remove("scale-110");
        }, 150);
    } catch (e) {
        // 予期しない例外もユーザーに分かりやすく通知
        showError("「いいね」ボタンの更新中にエラーが発生しました。");
        console.error("updateLikeUI例外:", e);
    }
}

/**
 * エラーメッセージの表示
 */
function showError(message) {
    // 簡易的なエラー表示（必要に応じてモーダルやトーストに変更可能）
    alert(message);
}
