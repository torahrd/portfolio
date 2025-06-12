<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="auth-check" content="{{ auth()->check() ? 'true' : 'false' }}">
    @auth
    <meta name="user-id" content="{{ auth()->id() }}">
    @endauth

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Font Awesome (アイコン用) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Scripts (Tailwind + Alpine.js) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js (軽量なJavaScriptフレームワーク - jQueryの代替) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="font-sans antialiased dark-mode-bg"
    data-authenticated="{{ auth()->check() ? 'true' : 'false' }}"
    @auth data-user-id="{{ auth()->id() }}" @endauth
    x-data="appData()">

    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @if (isset($header))
        <header class="bg-white dark:bg-gray-800 shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <div class="dark-mode-text">
                    {{ $header }}
                </div>
            </div>
        </header>
        @endif

        <!-- Flash Messages -->
        @if (session('success'))
        <div class="fixed top-4 right-4 z-50 animate-slide-up">
            <div class="alert alert-success flex items-center p-4 rounded-lg shadow-lg"
                x-data="{ show: true }"
                x-show="show"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-90"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-90"
                x-init="setTimeout(() => show = false, 5000)">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
                <button @click="show = false" class="ml-4 text-success-600 hover:text-success-800">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        @endif

        @if (session('error'))
        <div class="fixed top-4 right-4 z-50 animate-slide-up">
            <div class="alert alert-danger flex items-center p-4 rounded-lg shadow-lg"
                x-data="{ show: true }"
                x-show="show"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-90"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-90"
                x-init="setTimeout(() => show = false, 5000)">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ session('error') }}
                <button @click="show = false" class="ml-4 text-danger-600 hover:text-danger-800">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        @endif

        @if (session('warning'))
        <div class="fixed top-4 right-4 z-50 animate-slide-up">
            <div class="alert alert-warning flex items-center p-4 rounded-lg shadow-lg"
                x-data="{ show: true }"
                x-show="show"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-90"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-90"
                x-init="setTimeout(() => show = false, 5000)">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                {{ session('warning') }}
                <button @click="show = false" class="ml-4 text-warning-600 hover:text-warning-800">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        @endif

        @if (session('info'))
        <div class="fixed top-4 right-4 z-50 animate-slide-up">
            <div class="alert alert-info flex items-center p-4 rounded-lg shadow-lg"
                x-data="{ show: true }"
                x-show="show"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-90"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-90"
                x-init="setTimeout(() => show = false, 5000)">
                <i class="fas fa-info-circle mr-2"></i>
                {{ session('info') }}
                <button @click="show = false" class="ml-4 text-blue-600 hover:text-blue-800">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        @endif

        <!-- Page Content -->
        <main class="dark-mode-text">
            {{ $slot }}
        </main>
    </div>

    <!-- フォロー申請モーダル (Alpine.js版) -->
    @auth
    <div x-show="showFollowRequestsModal"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 overflow-y-auto"
        x-cloak>
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75"
                @click="showFollowRequestsModal = false"></div>

            <!-- Modal panel -->
            <div class="inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-gray-800 shadow-xl rounded-lg"
                @click.stop>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                        <i class="fas fa-user-plus mr-2"></i>
                        フォロー申請
                    </h3>
                    <button @click="showFollowRequestsModal = false"
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="space-y-4">
                    <!-- フォロー申請のリストをここに表示 -->
                    <div id="follow-requests-list">
                        <p class="text-gray-600 dark:text-gray-400 text-center py-4">
                            <i class="fas fa-inbox text-3xl text-gray-300 block mb-2"></i>
                            現在フォロー申請はありません
                        </p>
                    </div>
                </div>

                <div class="flex justify-end mt-6 space-x-3">
                    <button @click="showFollowRequestsModal = false"
                        class="btn btn-secondary">
                        閉じる
                    </button>
                    <button @click="refreshFollowRequests()" class="btn btn-outline-primary">
                        <i class="fas fa-sync-alt mr-1"></i>
                        更新
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endauth

    <!-- JavaScriptの初期化 -->
    @verbatim
    <script>
        // アプリケーションデータの初期化
        function appData() {
            // メタタグから認証情報を取得
            const authCheck = document.querySelector('meta[name="auth-check"]');
            const userId = document.querySelector('meta[name="user-id"]');

            const isAuthenticated = authCheck ? authCheck.content === 'true' : false;
            const currentUserId = userId ? parseInt(userId.content) : null;

            // グローバル変数も設定（既存コードとの互換性）
            window.isAuthenticated = isAuthenticated;
            window.currentUserId = currentUserId;

            return {
                isAuthenticated: isAuthenticated,
                currentUserId: currentUserId,
                showFollowRequestsModal: false,

                // フォロー申請モーダルを開く
                openFollowRequestsModal() {
                    this.showFollowRequestsModal = true;
                    this.loadFollowRequests();
                },

                // フォロー申請を読み込む
                async loadFollowRequests() {
                    if (!this.isAuthenticated) return;

                    try {
                        const response = await fetch('/api/follow-requests', {
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        });

                        if (response.ok) {
                            const data = await response.json();
                            this.renderFollowRequests(data.requests);
                        }
                    } catch (error) {
                        console.error('フォロー申請の取得に失敗しました:', error);
                    }
                },

                // フォロー申請を表示
                renderFollowRequests(requests) {
                    const container = document.getElementById('follow-requests-list');
                    if (!container) return;

                    if (requests.length === 0) {
                        container.innerHTML = `
                            <p class="text-gray-600 dark:text-gray-400 text-center py-4">
                                <i class="fas fa-inbox text-3xl text-gray-300 block mb-2"></i>
                                現在フォロー申請はありません
                            </p>
                        `;
                        return;
                    }

                    const requestsHtml = requests.map(request => `
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <img src="${request.user.avatar_url}" alt="${request.user.name}" class="w-10 h-10 rounded-full">
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">${request.user.name}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">${request.created_at}</p>
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                <button onclick="handleFollowRequest(${request.user.id}, 'accept')" 
                                        class="btn btn-success btn-sm">
                                    承認
                                </button>
                                <button onclick="handleFollowRequest(${request.user.id}, 'reject')" 
                                        class="btn btn-danger btn-sm">
                                    拒否
                                </button>
                            </div>
                        </div>
                    `).join('');

                    container.innerHTML = requestsHtml;
                }
            };
        }

        // Alpine.jsのデータストア
        document.addEventListener('alpine:init', () => {
            const authMeta = document.querySelector('meta[name="auth-check"]');
            const userMeta = document.querySelector('meta[name="user-id"]');

            Alpine.store('user', {
                isAuthenticated: authMeta ? authMeta.content === 'true' : false,
                currentUserId: userMeta ? parseInt(userMeta.content) : null,

                // フォロー機能
                async followUser(userId) {
                    try {
                        const response = await fetch(`/users/${userId}/follow`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        });

                        if (response.ok) {
                            const data = await response.json();
                            return data;
                        }
                        throw new Error('フォローに失敗しました');
                    } catch (error) {
                        console.error('Follow error:', error);
                        throw error;
                    }
                },

                // フォロー申請の処理
                async handleFollowRequest(userId, action) {
                    try {
                        const response = await fetch(`/users/${userId}/${action}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        });

                        if (response.ok) {
                            const data = await response.json();
                            return data;
                        }
                        throw new Error(`フォロー申請の${action === 'accept' ? '承認' : '拒否'}に失敗しました`);
                    } catch (error) {
                        console.error('Follow request error:', error);
                        throw error;
                    }
                }
            });
        });

        // メッセージ表示システム
        const MessageDisplay = {
            show: (message, type = 'success', duration = 5000) => {
                // 既存のメッセージを削除
                const existingMessages = document.querySelectorAll('.dynamic-message');
                existingMessages.forEach(msg => msg.remove());

                // メッセージ要素を作成
                const messageDiv = document.createElement('div');
                messageDiv.className = `dynamic-message fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full`;

                // タイプに応じてスタイルを設定
                const styles = {
                    success: 'bg-success-500 text-white',
                    error: 'bg-danger-500 text-white',
                    warning: 'bg-warning-500 text-white',
                    info: 'bg-blue-500 text-white'
                };

                const icons = {
                    success: 'fas fa-check-circle',
                    error: 'fas fa-exclamation-circle',
                    warning: 'fas fa-exclamation-triangle',
                    info: 'fas fa-info-circle'
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
                    messageDiv.classList.remove('translate-x-full');
                }, 100);

                // 自動削除
                setTimeout(() => {
                    messageDiv.classList.add('translate-x-full');
                    setTimeout(() => {
                        if (messageDiv.parentElement) {
                            messageDiv.remove();
                        }
                    }, 300);
                }, duration);
            },

            success: (message, duration = 5000) => MessageDisplay.show(message, 'success', duration),
            error: (message, duration = 5000) => MessageDisplay.show(message, 'error', duration),
            warning: (message, duration = 5000) => MessageDisplay.show(message, 'warning', duration),
            info: (message, duration = 5000) => MessageDisplay.show(message, 'info', duration)
        };

        // フォーム送信の共通処理
        const FormHandler = {
            async submit(form, options = {}) {
                const formData = new FormData(form);
                const url = form.action;
                const method = form.method || 'POST';

                // デフォルトオプション
                const defaultOptions = {
                    showLoading: true,
                    showSuccess: true,
                    showError: true,
                    onSuccess: null,
                    onError: null,
                    onComplete: null
                };

                const config = {
                    ...defaultOptions,
                    ...options
                };

                // ローディング表示
                if (config.showLoading) {
                    const submitButton = form.querySelector('button[type="submit"]');
                    if (submitButton) {
                        submitButton.disabled = true;
                        submitButton.classList.add('loading');
                    }
                }

                try {
                    const response = await fetch(url, {
                        method: method,
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
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
                        throw new Error(errorData.message || 'エラーが発生しました');
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
                        const submitButton = form.querySelector('button[type="submit"]');
                        if (submitButton) {
                            submitButton.disabled = false;
                            submitButton.classList.remove('loading');
                        }
                    }

                    if (config.onComplete) {
                        config.onComplete();
                    }
                }
            }
        };

        // フォロー申請処理のグローバル関数
        async function handleFollowRequest(userId, action) {
            try {
                const data = await Alpine.store('user').handleFollowRequest(userId, action);

                if (data.message) {
                    MessageDisplay.success(data.message);
                }

                // フォロー申請リストを更新
                const appData = Alpine.$data(document.body);
                if (appData && typeof appData.loadFollowRequests === 'function') {
                    appData.loadFollowRequests();
                }

            } catch (error) {
                MessageDisplay.error(error.message);
            }
        }

        // フォロー申請更新のグローバル関数
        async function refreshFollowRequests() {
            const appData = Alpine.$data(document.body);
            if (appData && typeof appData.loadFollowRequests === 'function') {
                await appData.loadFollowRequests();
                MessageDisplay.success('フォロー申請を更新しました');
            }
        }

        // スクロールアニメーションの初期化
        document.addEventListener('DOMContentLoaded', function() {
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                        // パフォーマンス向上のため一度表示されたら監視を停止
                        observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);

            // .animate-on-scroll クラスを持つ要素を監視
            document.querySelectorAll('.animate-on-scroll').forEach(el => {
                observer.observe(el);
            });
        });

        // グローバル関数として公開
        window.MessageDisplay = MessageDisplay;
        window.FormHandler = FormHandler;
        window.handleFollowRequest = handleFollowRequest;
        window.refreshFollowRequests = refreshFollowRequests;

        // デバッグ用ログ（開発時のみ）
        if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
            console.log('認証状態:', window.isAuthenticated);
            console.log('ユーザーID:', window.currentUserId);
            console.log('App initialized successfully');
        }
    </script>
    @endverbatim
</body>

</html>