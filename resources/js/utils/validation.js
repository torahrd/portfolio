// バリデーションシステム
class FormValidation {
    constructor(form, rules = {}) {
        this.form = form;
        this.rules = rules;
        this.errors = {};
        this.isValid = true;

        this.init();
    }

    init() {
        // リアルタイムバリデーション
        this.form.addEventListener("input", (e) => {
            if (e.target.matches("input, textarea, select")) {
                this.validateField(e.target);
            }
        });

        // フォーム送信時の検証
        this.form.addEventListener("submit", (e) => {
            if (!this.validateAll()) {
                e.preventDefault();
                this.showFirstError();
            }
        });
    }

    // 単一フィールドの検証
    validateField(field) {
        const fieldName = field.name;
        const value = field.value.trim();
        const fieldRules = this.rules[fieldName] || {};

        // エラーをクリア
        this.clearFieldError(field);

        // 必須チェック
        if (fieldRules.required && !value) {
            this.setFieldError(field, "この項目は必須です");
            return false;
        }

        // 値がある場合のみ以下の検証を実行
        if (value) {
            // 最小長チェック
            if (fieldRules.minLength && value.length < fieldRules.minLength) {
                this.setFieldError(
                    field,
                    `${fieldRules.minLength}文字以上で入力してください`
                );
                return false;
            }

            // 最大長チェック
            if (fieldRules.maxLength && value.length > fieldRules.maxLength) {
                this.setFieldError(
                    field,
                    `${fieldRules.maxLength}文字以下で入力してください`
                );
                return false;
            }

            // メールアドレスチェック
            if (fieldRules.email && !this.isValidEmail(value)) {
                this.setFieldError(
                    field,
                    "正しいメールアドレスを入力してください"
                );
                return false;
            }

            // URLチェック
            if (fieldRules.url && !this.isValidUrl(value)) {
                this.setFieldError(field, "正しいURLを入力してください");
                return false;
            }

            // 数値チェック
            if (fieldRules.numeric && !this.isNumeric(value)) {
                this.setFieldError(field, "数値を入力してください");
                return false;
            }

            // カスタムパターンチェック
            if (fieldRules.pattern && !fieldRules.pattern.test(value)) {
                this.setFieldError(
                    field,
                    fieldRules.patternMessage || "入力形式が正しくありません"
                );
                return false;
            }

            // カスタム検証関数
            if (fieldRules.custom && typeof fieldRules.custom === "function") {
                const customResult = fieldRules.custom(value, field);
                if (customResult !== true) {
                    this.setFieldError(field, customResult);
                    return false;
                }
            }
        }

        // パスワード確認チェック
        if (fieldRules.confirmPassword) {
            const passwordField = this.form.querySelector(
                `[name="${fieldRules.confirmPassword}"]`
            );
            if (passwordField && value !== passwordField.value) {
                this.setFieldError(field, "パスワードが一致しません");
                return false;
            }
        }

        // 成功状態を設定
        this.setFieldSuccess(field);
        return true;
    }

    // 全フィールドの検証
    validateAll() {
        this.isValid = true;
        this.errors = {};

        const fields = this.form.querySelectorAll("input, textarea, select");
        fields.forEach((field) => {
            if (!this.validateField(field)) {
                this.isValid = false;
            }
        });

        return this.isValid;
    }

    // フィールドエラーを設定
    setFieldError(field, message) {
        field.classList.add("error");
        field.classList.remove("success");

        // 既存のエラーメッセージを削除
        this.clearFieldError(field, false);

        // エラーメッセージを追加
        const errorDiv = document.createElement("div");
        errorDiv.className = "form-error validation-fade-in";
        errorDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i>${message}`;

        // フィールドの後に挿入
        field.parentNode.appendChild(errorDiv);

        // シェイクアニメーション
        field.classList.add("validation-shake");
        setTimeout(() => field.classList.remove("validation-shake"), 500);

        this.errors[field.name] = message;
    }

    // フィールド成功状態を設定
    setFieldSuccess(field) {
        field.classList.add("success");
        field.classList.remove("error");
        this.clearFieldError(field, false);

        // 成功メッセージを表示（オプション）
        if (this.rules[field.name]?.showSuccess) {
            const successDiv = document.createElement("div");
            successDiv.className = "form-success validation-fade-in";
            successDiv.innerHTML = `<i class="fas fa-check-circle"></i>入力内容に問題ありません`;
            field.parentNode.appendChild(successDiv);
        }

        delete this.errors[field.name];
    }

    // フィールドエラーをクリア
    clearFieldError(field, removeClasses = true) {
        if (removeClasses) {
            field.classList.remove("error", "success");
        }

        // エラーメッセージを削除
        const parent = field.parentNode;
        const existingError = parent.querySelector(".form-error");
        const existingSuccess = parent.querySelector(".form-success");

        if (existingError) existingError.remove();
        if (existingSuccess) existingSuccess.remove();
    }

    // 最初のエラーにスクロール
    showFirstError() {
        const firstErrorField = this.form.querySelector(".error");
        if (firstErrorField) {
            firstErrorField.scrollIntoView({
                behavior: "smooth",
                block: "center",
            });
            firstErrorField.focus();
        }
    }

    // メールアドレス検証
    isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    // URL検証
    isValidUrl(url) {
        try {
            new URL(url);
            return true;
        } catch {
            return false;
        }
    }

    // 数値検証
    isNumeric(value) {
        return !isNaN(value) && !isNaN(parseFloat(value));
    }

    // エラー取得
    getErrors() {
        return this.errors;
    }

    // 検証状態取得
    isFormValid() {
        return this.isValid;
    }
}

// グローバルに公開
window.FormValidation = FormValidation;

// 共通バリデーションルール
window.ValidationRules = {
    // ユーザー登録
    userRegistration: {
        name: {
            required: true,
            minLength: 2,
            maxLength: 50,
        },
        email: {
            required: true,
            email: true,
            maxLength: 255,
        },
        password: {
            required: true,
            minLength: 8,
            pattern: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d@$!%*?&]{8,}$/,
            patternMessage:
                "パスワードは8文字以上で、大文字・小文字・数字を含む必要があります",
        },
        password_confirmation: {
            required: true,
            confirmPassword: "password",
        },
    },

    // プロフィール編集
    profileEdit: {
        name: {
            required: true,
            minLength: 2,
            maxLength: 50,
        },
        email: {
            required: true,
            email: true,
            maxLength: 255,
        },
        bio: {
            maxLength: 500,
        },
        location: {
            maxLength: 100,
        },
        website: {
            url: true,
            maxLength: 255,
        },
    },

    // 投稿作成
    postCreation: {
        shop_name: {
            required: true,
            minLength: 1,
            maxLength: 100,
        },
        body: {
            maxLength: 1000,
        },
        repeat_menu: {
            maxLength: 200,
        },
        interest_menu: {
            maxLength: 200,
        },
        memo: {
            maxLength: 500,
        },
        reference_link: {
            url: true,
            maxLength: 255,
        },
    },

    // コメント投稿
    comment: {
        body: {
            required: true,
            minLength: 1,
            maxLength: 500,
        },
    },
};
