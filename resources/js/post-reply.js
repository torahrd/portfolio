document.addEventListener("DOMContentLoaded", function () {
    // CSRFトークンを取得
    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");

    // ヘッダーを共通化
    const headers = {
        "X-CSRF-TOKEN": csrfToken,
        "X-Requested-With": "XMLHttpRequest",
        Accept: "application/json",
    };

    // --- 親コメント投稿処理 ---
    const commentForm = document.getElementById("comment-form");
    if (commentForm) {
        commentForm.addEventListener("submit", function (e) {
            e.preventDefault();
            handleFormSubmit(commentForm);
        });
    }

    // --- 返信フォームの動的な処理 ---
    const commentsContainer = document.getElementById("comments-container");
    if (commentsContainer) {
        // イベント委譲：コンテナにイベントリスナーを設置
        commentsContainer.addEventListener("submit", function (e) {
            // reply-form クラスを持つフォームの送信イベントのみを捕捉
            if (e.target && e.target.classList.contains("reply-form")) {
                e.preventDefault();
                handleFormSubmit(e.target);
            }
        });
    }

    /**
     * コメント・返信フォームの送信を処理する共通関数
     * @param {HTMLFormElement} form - 送信するフォーム要素
     */
    async function handleFormSubmit(form) {
        const submitButton = form.querySelector('button[type="submit"]');
        const textarea = form.querySelector("textarea");
        const errorContainer = form.querySelector(".comment-error"); // エラー表示用の要素
        const originalButtonText = submitButton.textContent;

        // ボタンを無効化
        submitButton.disabled = true;
        submitButton.textContent = "投稿中...";

        // エラーメッセージをクリア
        if (errorContainer) {
            errorContainer.classList.add("hidden");
            errorContainer.textContent = "";
        }

        const formData = new FormData(form);

        try {
            const response = await fetch(form.action, {
                method: "POST",
                headers: headers, // 共通ヘッダーを使用
                body: formData,
            });

            const data = await response.json();

            if (!response.ok) {
                // バリデーションエラー等のハンドリング
                if (data.errors && data.errors.body) {
                    if (errorContainer) {
                        errorContainer.textContent = data.errors.body[0];
                        errorContainer.classList.remove("hidden");
                    }
                } else {
                    throw new Error(
                        data.message || "不明なエラーが発生しました。"
                    );
                }
                return; // エラーがあった場合はここで処理を終了
            }

            // 成功した場合のDOM操作
            if (data.html) {
                if (data.parent_id) {
                    // 返信の場合：親コメントの子要素コンテナに追加
                    const repliesContainer = document.getElementById(
                        `comment-replies-${data.parent_id}`
                    );
                    if (repliesContainer) {
                        repliesContainer.insertAdjacentHTML(
                            "beforeend",
                            data.html
                        );
                        // 返信フォームをリセット
                        textarea.value = "";
                        // 返信フォームを非表示にする (オプション)
                        form.classList.add("hidden");
                    }
                } else {
                    // 親コメントの場合：コメント一覧の先頭に追加
                    const commentsContainer =
                        document.getElementById("comments-container");
                    const noComments = document.getElementById("no-comments");
                    if (commentsContainer) {
                        if (noComments) {
                            noComments.remove(); // 「コメントがありません」を削除
                        }
                        commentsContainer.insertAdjacentHTML(
                            "afterbegin",
                            data.html
                        );
                        textarea.value = ""; // テキストエリアをクリア
                        // コメント数を更新
                        const commentCount =
                            document.getElementById("comment-count");
                        commentCount.textContent =
                            parseInt(commentCount.textContent) + 1;
                    }
                }
            }
        } catch (error) {
            console.error("フォームの送信に失敗しました:", error);
            if (errorContainer) {
                errorContainer.textContent = "コメントの投稿に失敗しました。";
                errorContainer.classList.remove("hidden");
            }
        } finally {
            // ボタンの状態を元に戻す
            submitButton.disabled = false;
            submitButton.textContent = originalButtonText;
        }
    }
});

// 返信フォームの表示切り替え（グローバルスコープに残す）
function toggleReplyForm(commentId) {
    const replyForm = document.getElementById(`reply-form-${commentId}`);
    if (replyForm) {
        replyForm.classList.toggle("hidden");
        if (!replyForm.classList.contains("hidden")) {
            const textarea = replyForm.querySelector("textarea");
            if (textarea) {
                textarea.focus();
            }
        }
    }
}
