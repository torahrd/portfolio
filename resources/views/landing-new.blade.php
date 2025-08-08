@extends('layouts.guest')

@section('content')
    <style>
        /* 24節気カラーパレット（控えめに使用） */
        :root {
            --season-spring: #F8BBD0;  /* 桜色 */
            --season-summer: #81C784;  /* 新緑 */
            --season-autumn: #FFB74D;  /* 紅葉 */
            --season-winter: #64B5F6;  /* 雪空 */
        }

        /* Button Styles */
        .btn {
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.2s ease;
            cursor: pointer;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-primary {
            background: #3B82F6;
            color: white;
        }

        .btn-primary:hover {
            background: #2563EB;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
        }

        .btn-secondary {
            background: white;
            color: #0F172A;
            border: 2px solid #E5E7EB;
        }

        .btn-secondary:hover {
            background: #F9FAFB;
            border-color: #D1D5DB;
        }

        .btn-ghost {
            background: transparent;
            color: #6B7280;
            padding: 8px 16px;
        }

        .btn-ghost:hover {
            background: #F3F4F6;
        }

        /* Card Styles */
        .card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        /* Demo Section */
        .demo-container {
            background: #F9FAFB;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .demo-step {
            padding: 32px;
            min-height: 500px;
            display: flex;
            flex-direction: column;
        }

        .step-indicator {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 24px;
            margin-bottom: 40px;
        }

        .step-dot {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #D1D5DB;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
        }

        .step-dot.active {
            background: #3B82F6;
            transform: scale(1.2);
        }

        .step-dot.completed {
            background: #10B981;
        }

        .step-line {
            width: 60px;
            height: 2px;
            background: #D1D5DB;
            transition: all 0.3s ease;
        }

        .step-line.completed {
            background: #10B981;
        }

        /* Search Input */
        .search-input {
            width: 100%;
            padding: 16px 20px;
            font-size: 18px;
            border: 2px solid #E5E7EB;
            border-radius: 12px;
            transition: all 0.2s ease;
            background: white;
        }

        .search-input:focus {
            outline: none;
            border-color: #3B82F6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        /* Shop Card */
        .shop-item {
            background: white;
            border: 1px solid #E5E7EB;
            border-radius: 12px;
            padding: 16px;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .shop-item:hover {
            border-color: #3B82F6;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .shop-item.selected {
            border-color: #3B82F6;
            background: rgba(59, 130, 246, 0.05);
        }

        /* Post Card */
        .post-card {
            background: white;
            border: 1px solid #E5E7EB;
            border-radius: 16px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .post-image {
            width: 100%;
            height: 200px;
            background: #E5E7EB;
            position: relative;
            overflow: hidden;
        }

        .post-content {
            padding: 20px;
        }

        /* Collection Grid */
        .collection-grid {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 12px;
            padding: 24px;
            background: white;
            border-radius: 16px;
            border: 2px solid #E5E7EB;
        }

        .collection-slot {
            aspect-ratio: 1;
            background: #F3F4F6;
            border: 2px dashed #D1D5DB;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: #9CA3AF;
            transition: all 0.2s ease;
            position: relative;
        }

        .collection-slot.filled {
            background: linear-gradient(135deg, #667EEA 0%, #764BA2 100%);
            border: none;
            color: white;
            font-size: 20px;
            font-weight: 600;
            animation: slideIn 0.5s ease;
        }

        /* 季節のヒント */
        .collection-slot.filled::after {
            content: '';
            position: absolute;
            top: 2px;
            right: 2px;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--season-spring);
        }

        .collection-slot.filled:nth-child(n+7)::after {
            background: var(--season-summer);
        }

        .collection-slot.filled:nth-child(n+13)::after {
            background: var(--season-autumn);
        }

        .collection-slot.filled:nth-child(n+19)::after {
            background: var(--season-winter);
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: scale(0.8);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* Map Styles */
        .map-container {
            height: 400px;
            width: 100%;
            border-radius: 12px;
            overflow: hidden;
            background: #E5E7EB;
        }

        /* Animations */
        .fade-in {
            animation: fadeIn 0.6s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Loading State */
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid #D1D5DB;
            border-radius: 50%;
            border-top-color: #3B82F6;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Success Animation */
        .success-check {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: #10B981;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 30px;
            animation: scaleIn 0.5s ease;
        }

        @keyframes scaleIn {
            from {
                transform: scale(0);
            }
            to {
                transform: scale(1);
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .collection-grid {
                grid-template-columns: repeat(4, 1fr);
            }

            .demo-step {
                padding: 20px;
                min-height: 400px;
            }
        }
    </style>

    <!-- Google Analytics 4 -->
    <script>
        // カスタムイベントトラッキング関数
        function trackEvent(eventName, parameters) {
            if (typeof gtag !== 'undefined') {
                gtag('event', eventName, parameters);
            }
        }
    </script>

    <!-- Hero Section - Simple & Clear -->
    <section class="pt-20 pb-16 px-4">
        <div class="max-w-4xl mx-auto text-center">
            <h1 class="text-5xl md:text-6xl font-bold mb-6 leading-tight">
                あなただけの<br>
                <span class="bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">美食コレクション</span>
            </h1>
            <p class="text-xl text-gray-600 mb-8 max-w-2xl mx-auto">
                季節と共に歩む、24の厳選されたお店。<br>
                広告なし、評価なし、純粋にあなたの感性だけで。
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="#demo" class="btn btn-primary px-8 py-4 text-lg" onclick="trackEvent('demo_start', {location: 'hero'})">
                    今すぐ体験する
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </a>
                <div class="text-sm text-gray-500">
                    登録不要・無料で試せます
                </div>
            </div>
        </div>
    </section>

    <!-- Value Props - Quick & Clear -->
    <section class="py-16 px-4 bg-gray-50">
        <div class="max-w-6xl mx-auto">
            <div class="grid md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-full flex items-center justify-center" style="background: linear-gradient(135deg, var(--season-spring), var(--season-summer), var(--season-autumn), var(--season-winter));">
                        <span class="text-2xl text-white font-bold">24</span>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">季節と共に24店舗</h3>
                    <p class="text-gray-600">
                        立春から大寒まで、季節の移ろいと共に<br>
                        本当に大切なお店だけを厳選。
                    </p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-green-100 flex items-center justify-center">
                        <span class="text-2xl">🎯</span>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">あなただけの価値観</h3>
                    <p class="text-gray-600">
                        他人の評価は一切なし。<br>
                        純粋にあなたの感性で選択。
                    </p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-purple-100 flex items-center justify-center">
                        <span class="text-2xl">📝</span>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">思い出と共に</h3>
                    <p class="text-gray-600">
                        訪れた記憶を写真と共に保存。<br>
                        あなたの美食史を作る。
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Interactive Demo Section -->
    <section id="demo" class="py-20 px-4" x-data="demoApp()">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold mb-4">
                    3ステップで体験
                </h2>
                <p class="text-lg text-gray-600">
                    実際の操作感をお試しください
                </p>
            </div>

            <div class="demo-container">
                <!-- Step Indicator -->
                <div class="step-indicator pt-8">
                    <div class="step-dot" :class="{'active': currentStep === 1, 'completed': currentStep > 1}">1</div>
                    <div class="step-line" :class="{'completed': currentStep > 1}"></div>
                    <div class="step-dot" :class="{'active': currentStep === 2, 'completed': currentStep > 2}">2</div>
                    <div class="step-line" :class="{'completed': currentStep > 2}"></div>
                    <div class="step-dot" :class="{'active': currentStep === 3, 'completed': currentStep > 3}">3</div>
                </div>

                <!-- Step 1: Search -->
                <div x-show="currentStep === 1" class="demo-step">
                    <div class="text-center mb-8">
                        <h3 class="text-2xl font-bold mb-2">お店を探す</h3>
                        <p class="text-gray-600">まずは気になるお店を検索してみましょう</p>
                    </div>

                    <div class="max-w-2xl mx-auto w-full">
                        <input 
                            type="text" 
                            x-model="searchQuery"
                            @input="searchShops"
                            placeholder="店名・エリア・料理ジャンルで検索..."
                            class="search-input"
                        >

                        <!-- Search Results -->
                        <div x-show="searchResults.length > 0" class="mt-4 space-y-2">
                            <template x-for="shop in searchResults" :key="shop.id">
                                <div 
                                    @click="selectShop(shop)"
                                    class="shop-item"
                                    :class="{'selected': selectedShop?.id === shop.id}"
                                >
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <div class="font-semibold" x-text="shop.name"></div>
                                            <div class="text-sm text-gray-500" x-text="shop.area"></div>
                                            <div class="text-xs text-gray-400 mt-1">
                                                <span x-text="shop.genre"></span>
                                                <span x-show="shop.season" class="ml-2 text-blue-500">
                                                    <span x-text="shop.season"></span>におすすめ
                                                </span>
                                            </div>
                                        </div>
                                        <div x-show="selectedShop?.id === shop.id" class="text-blue-500">
                                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Mock Suggestions -->
                        <div x-show="searchQuery.length === 0" class="mt-8">
                            <p class="text-sm text-gray-500 mb-3">人気の検索</p>
                            <div class="flex flex-wrap gap-2">
                                <button @click="searchQuery = '寿司'; searchShops()" class="btn btn-ghost btn-sm">寿司</button>
                                <button @click="searchQuery = 'イタリアン'; searchShops()" class="btn btn-ghost btn-sm">イタリアン</button>
                                <button @click="searchQuery = '銀座'; searchShops()" class="btn btn-ghost btn-sm">銀座</button>
                                <button @click="searchQuery = 'ラーメン'; searchShops()" class="btn btn-ghost btn-sm">ラーメン</button>
                            </div>
                        </div>

                        <div x-show="selectedShop" class="mt-8 text-center">
                            <button @click="goToStep2()" class="btn btn-primary px-8">
                                次へ進む
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Create Post -->
                <div x-show="currentStep === 2" class="demo-step">
                    <div class="text-center mb-8">
                        <h3 class="text-2xl font-bold mb-2">思い出を記録</h3>
                        <p class="text-gray-600">訪問した記憶を写真とコメントで残しましょう</p>
                    </div>

                    <div class="max-w-4xl mx-auto w-full">
                        <div class="grid md:grid-cols-2 gap-8">
                            <!-- Left: Post Creation -->
                            <div>
                                <div class="post-card">
                                    <div class="post-image">
                                        <div x-show="!photoSelected" class="h-full flex items-center justify-center text-gray-400">
                                            <div class="text-center">
                                                <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                <button @click="selectPhoto()" class="text-sm text-blue-500 hover:text-blue-600">
                                                    写真を選択
                                                </button>
                                            </div>
                                        </div>
                                        <div x-show="photoSelected" class="absolute inset-0 bg-gradient-to-br from-purple-400 to-pink-400 flex items-center justify-center text-white">
                                            <span class="text-6xl" x-text="selectedPhoto?.emoji || '🍽️'"></span>
                                        </div>
                                        
                                        <!-- ダミー画像選択モーダル -->
                                        <div x-show="showPhotoSelector" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" @click.self="showPhotoSelector = false">
                                            <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
                                                <h3 class="text-lg font-semibold mb-4">料理の種類を選択</h3>
                                                <div class="grid grid-cols-3 gap-4">
                                                    <template x-for="photo in dummyPhotos" :key="photo.id">
                                                        <button @click="selectDummyPhoto(photo.id)" class="p-4 border-2 border-gray-200 rounded-lg hover:border-blue-500 transition-colors">
                                                            <div class="text-4xl mb-2" x-text="photo.emoji"></div>
                                                            <div class="text-sm text-gray-600" x-text="photo.name"></div>
                                                        </button>
                                                    </template>
                                                </div>
                                                <button @click="showPhotoSelector = false" class="mt-4 w-full btn btn-ghost">キャンセル</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="post-content">
                                        <div class="font-semibold text-lg mb-2" x-text="selectedShop?.name"></div>
                                        <textarea 
                                            x-model="postComment"
                                            placeholder="感想を書く（任意）"
                                            class="w-full p-3 border border-gray-200 rounded-lg resize-none h-24 text-sm"
                                        ></textarea>
                                        <div class="flex gap-2 mt-3">
                                            <button class="btn btn-ghost btn-sm">
                                                <span>😋</span> 美味しい
                                            </button>
                                            <button class="btn btn-ghost btn-sm">
                                                <span>🎉</span> 特別な日
                                            </button>
                                            <button class="btn btn-ghost btn-sm">
                                                <span>👨‍👩‍👧</span> 家族と
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right: Map -->
                            <div>
                                <div id="demo-map" class="map-container"></div>
                                <div class="mt-3 p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center gap-2 text-sm text-gray-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span x-text="selectedShop?.area"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 text-center">
                            <button @click="goToStep3()" class="btn btn-primary px-8">
                                投稿してリストに追加
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Add to Collection -->
                <div x-show="currentStep === 3" class="demo-step">
                    <div class="text-center mb-8">
                        <h3 class="text-2xl font-bold mb-2">24の名店リスト</h3>
                        <p class="text-gray-600">あなたの美食コレクションが始まりました</p>
                    </div>

                    <div class="max-w-4xl mx-auto w-full">
                        <!-- Collection Grid -->
                        <div class="collection-grid">
                            <template x-for="i in 24" :key="i">
                                <div class="collection-slot" :class="{'filled': i <= collectionCount}">
                                    <span x-show="i <= collectionCount" x-text="i"></span>
                                    <span x-show="i > collectionCount">+</span>
                                </div>
                            </template>
                        </div>

                        <!-- Season Legend -->
                        <div class="mt-4 flex justify-center gap-4 text-xs text-gray-500">
                            <div class="flex items-center gap-1">
                                <div class="w-2 h-2 rounded-full" style="background: var(--season-spring)"></div>
                                <span>春</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <div class="w-2 h-2 rounded-full" style="background: var(--season-summer)"></div>
                                <span>夏</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <div class="w-2 h-2 rounded-full" style="background: var(--season-autumn)"></div>
                                <span>秋</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <div class="w-2 h-2 rounded-full" style="background: var(--season-winter)"></div>
                                <span>冬</span>
                            </div>
                        </div>

                        <!-- Collection Info -->
                        <div class="mt-8 text-center">
                            <div class="inline-flex items-center gap-4 p-4 bg-green-50 rounded-lg">
                                <div class="success-check">✓</div>
                                <div class="text-left">
                                    <div class="font-semibold text-green-800">追加完了！</div>
                                    <div class="text-sm text-green-600">
                                        <span x-text="selectedShop?.name"></span>があなたのコレクションに加わりました
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        残り<span x-text="24 - collectionCount"></span>枠
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- CTA -->
                        <div class="mt-12 p-8 bg-gradient-to-br from-blue-50 to-purple-50 rounded-2xl text-center">
                            <h4 class="text-2xl font-bold mb-3">続けてコレクションを作りませんか？</h4>
                            <p class="text-gray-600 mb-6">
                                無料で始められます。メールアドレスだけで簡単登録。
                            </p>
                            
                            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                                <a href="{{ route('register') }}" class="btn btn-primary px-8" onclick="trackEvent('signup_click', {location: 'demo_cta'})">
                                    無料で始める
                                </a>
                                <a href="{{ route('login') }}" class="btn btn-secondary px-8" onclick="trackEvent('login_click', {location: 'demo_cta'})">
                                    ログインはこちら
                                </a>
                            </div>

                            <div class="mt-4 text-xs text-gray-500">
                                メールアドレスで簡単登録・SNSログインも可能
                            </div>
                        </div>

                        <!-- Restart Demo -->
                        <div class="mt-8 text-center">
                            <button @click="resetDemo()" class="btn btn-ghost">
                                もう一度体験する
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        // Alpine.js Component
        function demoApp() {
            return {
                currentStep: 1,
                searchQuery: '',
                searchResults: [],
                selectedShop: null,
                photoSelected: false,
                postComment: '',
                collectionCount: 3,
                map: null,
                showPhotoSelector: false,
                selectedPhoto: null,
                dummyPhotos: [
                    { id: 0, emoji: '🍣', name: '寿司' },
                    { id: 1, emoji: '🍜', name: 'ラーメン' },
                    { id: 2, emoji: '🍝', name: 'パスタ' },
                    { id: 3, emoji: '🥩', name: 'ステーキ' },
                    { id: 4, emoji: '🍛', name: 'カレー' },
                    { id: 5, emoji: '🍱', name: '和食' }
                ],

                init() {
                    this.initMap();
                },

                initMap() {
                    if (typeof google !== 'undefined' && document.getElementById('demo-map')) {
                        this.map = new google.maps.Map(document.getElementById('demo-map'), {
                            center: { lat: 35.6762, lng: 139.6503 },
                            zoom: 13,
                            disableDefaultUI: true,
                            styles: [
                                {
                                    featureType: "all",
                                    elementType: "geometry",
                                    stylers: [{ color: "#f5f5f5" }]
                                },
                                {
                                    featureType: "water",
                                    elementType: "geometry",
                                    stylers: [{ color: "#c9c9c9" }]
                                }
                            ]
                        });
                    }
                },

                searchShops() {
                    if (this.searchQuery.length < 2) {
                        this.searchResults = [];
                        return;
                    }

                    // Mock search results with seasonal touches
                    const mockShops = [
                        { id: 1, name: '鮨 さいとう', area: '東京・六本木', genre: '寿司', season: '春分' },
                        { id: 2, name: 'レストラン・リューズ', area: '東京・六本木', genre: 'フレンチ', season: '夏至' },
                        { id: 3, name: '麺屋 翔', area: '東京・新宿', genre: 'ラーメン', season: '冬至' },
                        { id: 4, name: 'トラットリア・ダ・トンマーゾ', area: '東京・広尾', genre: 'イタリアン', season: '秋分' },
                        { id: 5, name: '焼肉 叙々苑', area: '東京・銀座', genre: '焼肉', season: '大暑' },
                    ];

                    this.searchResults = mockShops.filter(shop => 
                        shop.name.includes(this.searchQuery) ||
                        shop.area.includes(this.searchQuery) ||
                        shop.genre.includes(this.searchQuery)
                    );

                    if (this.searchResults.length === 0) {
                        this.searchResults = mockShops.slice(0, 3);
                    }
                },

                selectShop(shop) {
                    this.selectedShop = shop;
                },

                goToStep2() {
                    if (this.selectedShop) {
                        this.currentStep = 2;
                        setTimeout(() => this.initMap(), 100);
                        trackEvent('demo_step_2', {shop_name: this.selectedShop.name});
                    }
                },

                selectPhoto() {
                    this.showPhotoSelector = true;
                },

                selectDummyPhoto(photoId) {
                    this.selectedPhoto = this.dummyPhotos[photoId];
                    this.photoSelected = true;
                    this.showPhotoSelector = false;
                },

                goToStep3() {
                    this.currentStep = 3;
                    this.collectionCount++;
                    trackEvent('demo_step_3', {collection_count: this.collectionCount});
                },

                resetDemo() {
                    this.currentStep = 1;
                    this.searchQuery = '';
                    this.searchResults = [];
                    this.selectedShop = null;
                    this.photoSelected = false;
                    this.postComment = '';
                    this.selectedPhoto = null;
                    this.showPhotoSelector = false;
                    trackEvent('demo_restart', {});
                }
            }
        }

        // Initialize map - グローバル関数として定義
        window.initMap = function() {
            // Alpine.jsのコンポーネントが初期化されている場合、マップを初期化
            if (document.querySelector('[x-data="demoApp()"]')) {
                // Alpine.jsコンポーネントのinitMapを呼び出す
                const alpineComponent = document.querySelector('[x-data="demoApp()"]')._x_dataStack;
                if (alpineComponent && alpineComponent[0] && alpineComponent[0].initMap) {
                    alpineComponent[0].initMap();
                }
            }
        }
    </script>
@endsection