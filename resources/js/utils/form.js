// フォーム処理ユーティリティ
class FormHandler {
    static async submit(form, options = {}) {
        const formData = new FormData(form);
        const url = form.action;
        const method = form.method || "POST";

        // デフォルトオプション
        const defaultOptions = {
            showLoading: true,
            showSuccess: true,
            showError: true,
            onSuccess: null,
            onError: null,
            onComplete: null,
        };

        const config = { ...defaultOptions, ...options };

        // ローディング表示
        if (config.showLoading) {
            const submitButton = form.querySelector('button[type="submit"]');
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.classList.add("loading");
            }
        }

        try {
            const response = await fetch(url, {
                method: method,
                body: formData,
                headers: {
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
            });

            if (response.ok) {
                const data = await response.json();

                if (config.showSuccess && data.message) {
                    MessageDisplay.success(data.message);
                }

                if (config.onSuccess) {
                    config.onSuccess(data);
                }

                return data;
            } else {
                const errorData = await response.json();
                throw new Error(errorData.message || "エラーが発生しました");
            }
        } catch (error) {
            if (config.showError) {
                MessageDisplay.error(error.message);
            }

            if (config.onError) {
                config.onError(error);
            }

            throw error;
        } finally {
            // ローディング解除
            if (config.showLoading) {
                const submitButton = form.querySelector(
                    'button[type="submit"]'
                );
                if (submitButton) {
                    submitButton.disabled = false;
                    submitButton.classList.remove("loading");
                }
            }

            if (config.onComplete) {
                config.onComplete();
            }
        }
    }

    // AJAX フォーム送信（ページリロードなし）
    static initAjaxForm(selector, options = {}) {
        document.querySelectorAll(selector).forEach((form) => {
            form.addEventListener("submit", async (e) => {
                e.preventDefault();

                try {
                    await this.submit(form, options);
                } catch (error) {
                    console.error("Form submission error:", error);
                }
            });
        });
    }
}

// グローバルに公開
window.FormHandler = FormHandler;
