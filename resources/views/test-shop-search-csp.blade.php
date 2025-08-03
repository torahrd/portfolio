<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>shopSearch CSP対応テスト</title>
    
    <!-- ViteでビルドされたCSS -->
    @vite(['resources/css/app.css'])
    
    <!-- CSPビルドテスト専用のJavaScript -->
    @vite(['resources/js/app-csp-test.js'])
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8 text-center">shopSearch CSP対応テスト</h1>
        
        <div class="max-w-4xl mx-auto">
            <!-- テスト用カウンターコンポーネント -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h2 class="text-xl font-semibold mb-4">テスト用カウンター</h2>
                <x-test-counter title="CSPビルドテスト" />
            </div>
            
            <!-- shopSearchCspコンポーネント -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h2 class="text-xl font-semibold mb-4">shopSearch CSP対応版</h2>
                <x-shop-search-csp 
                    name="shop_id"
                    label="店舗名を検索してください（CSP対応版）"
                    mode="post"
                />
            </div>
            
            <!-- テスト結果表示エリア -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-semibold mb-4">テスト結果</h2>
                <div id="test-results" class="space-y-2">
                    <div class="text-sm">
                        <strong>ステータス:</strong> 
                        <span id="csp-status" class="text-yellow-600">テスト中...</span>
                    </div>
                    <div class="text-sm">
                        <strong>エラー:</strong> 
                        <span id="csp-errors" class="text-red-600">なし</span>
                    </div>
                    <div class="text-sm">
                        <strong>コンソールエラー数:</strong> 
                        <span id="console-error-count" class="text-blue-600">0</span>
                    </div>
                    <div class="text-sm">
                        <strong>shopSearch動作:</strong> 
                        <span id="shop-search-status" class="text-yellow-600">テスト中...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- テスト用スクリプト -->
    <script nonce="{{ csp_nonce() }}">
        document.addEventListener('DOMContentLoaded', function() {
            let errorCount = 0;
            
            // CSPエラーの監視
            document.addEventListener('securitypolicyviolation', function(e) {
                console.error('CSP Violation:', e);
                errorCount++;
                document.getElementById('csp-errors').textContent = 
                    `${e.violatedDirective}: ${e.blockedURI}`;
                document.getElementById('csp-status').textContent = 'エラー発生';
                document.getElementById('csp-status').className = 'text-red-600';
                document.getElementById('console-error-count').textContent = errorCount;
            });
            
            // Alpine.jsの初期化確認
            setTimeout(() => {
                if (window.Alpine) {
                    document.getElementById('csp-status').textContent = '正常';
                    document.getElementById('csp-status').className = 'text-green-600';
                    
                    // shopSearchCspコンポーネントの確認
                    if (window.Alpine.data('shopSearchCsp')) {
                        document.getElementById('shop-search-status').textContent = '登録済み';
                        document.getElementById('shop-search-status').className = 'text-green-600';
                    } else {
                        document.getElementById('shop-search-status').textContent = '未登録';
                        document.getElementById('shop-search-status').className = 'text-red-600';
                    }
                } else {
                    document.getElementById('csp-status').textContent = 'Alpine.js未初期化';
                    document.getElementById('csp-status').className = 'text-red-600';
                }
            }, 1000);
            
            // コンソールエラーの監視
            const originalError = console.error;
            const originalWarn = console.warn;
            
            console.error = function(...args) {
                errorCount++;
                document.getElementById('console-error-count').textContent = errorCount;
                originalError.apply(console, args);
            };
            
            console.warn = function(...args) {
                errorCount++;
                document.getElementById('console-error-count').textContent = errorCount;
                originalWarn.apply(console, args);
            };
        });
    </script>
</body>
</html> 