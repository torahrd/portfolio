// メッセージ表示システム
class MessageDisplay {
    static show(message, type = "success", duration = 5000) {
        // 既存のメッセージを削除
        const existingMessages = document.querySelectorAll(".flash-message");
        existingMessages.forEach((msg) => msg.remove());

        // メッセージ要素を作成
        const messageDiv = document.createElement("div");
        messageDiv.className = `flash-message fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full`;

        // タイプに応じてスタイルを設定
        const styles = {
            success: "bg-success-500 text-white",
            error: "bg-danger-500 text-white",
            warning: "bg-warning-500 text-white",
            info: "bg-blue-500 text-white",
        };

        const icons = {
            success: "fas fa-check-circle",
            error: "fas fa-exclamation-circle",
            warning: "fas fa-exclamation-triangle",
            info: "fas fa-info-circle",
        };

        messageDiv.className += ` ${styles[type] || styles.info}`;

        messageDiv.innerHTML = `
          <div class="flex items-center space-x-3">
              <i class="${icons[type] || icons.info}"></i>
              <span>${message}</span>
              <button onclick="this.parentElement.parentElement.remove()" 
                      class="ml-4 text-white hover:text-gray-200 transition-colors duration-200">
                  <i class="fas fa-times"></i>
              </button>
          </div>
      `;

        // DOMに追加
        document.body.appendChild(messageDiv);

        // アニメーション: スライドイン
        setTimeout(() => {
            messageDiv.classList.remove("translate-x-full");
        }, 100);

        // 自動削除
        setTimeout(() => {
            messageDiv.classList.add("translate-x-full");
            setTimeout(() => {
                if (messageDiv.parentElement) {
                    messageDiv.remove();
                }
            }, 300);
        }, duration);
    }

    static success(message, duration = 5000) {
        this.show(message, "success", duration);
    }

    static error(message, duration = 5000) {
        this.show(message, "error", duration);
    }

    static warning(message, duration = 5000) {
        this.show(message, "warning", duration);
    }

    static info(message, duration = 5000) {
        this.show(message, "info", duration);
    }
}

// グローバルに公開
window.MessageDisplay = MessageDisplay;
