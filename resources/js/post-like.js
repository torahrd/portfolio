/**
 * 投稿のいいね機能
 */
document.addEventListener("DOMContentLoaded", function () {
    // いいねボタンのクリックイベントを設定
    document.addEventListener("click", function (e) {
        if (e.target.closest(".like-button")) {
            e.preventDefault();
            handleLikeClick(e.target.closest(".like-button"));
        }
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

    // ローディング状態の表示
    const originalContent = button.innerHTML;
    button.innerHTML = `
        <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
        </svg>
        <span class="text-sm">${
            button.querySelector(".like-count").textContent
        }</span>
    `;

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
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                // UI更新
                updateLikeUI(button, data.is_favorited, data.favorites_count);
            } else {
                // エラー時の処理
                showError(
                    data.message ||
                        "いいねの処理に失敗しました。しばらく時間をおいてから再度お試しください。"
                );
            }
        })
        .catch((error) => {
            // ネットワークエラー時のみ
            showError(
                "ネットワークエラーが発生しました。しばらく時間をおいてから再度お試しください。"
            );
        })
        .finally(() => {
            // ボタンを再度有効化
            button.disabled = false;
            button.innerHTML = originalContent;
        });
}

/**
 * いいねUIの更新
 */
function updateLikeUI(button, isFavorited, count) {
    const icon = button.querySelector(".like-icon");
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
    countElement.textContent = count;

    // アニメーション効果
    button.classList.add("scale-110");
    setTimeout(() => {
        button.classList.remove("scale-110");
    }, 150);
}

/**
 * エラーメッセージの表示
 */
function showError(message) {
    // 簡易的なエラー表示（必要に応じてモーダルやトーストに変更可能）
    alert(message);
}
