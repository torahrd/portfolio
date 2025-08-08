<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>TasteRetreat - "本当に価値のあるお店"と出会う体験を、あなた自身の手で。</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;600;700;900&family=Noto+Serif+JP:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Google Maps API -->
    <script async defer src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&libraries=places&callback=initMap"></script>
    
    <style>
        :root {
            /* 和モダンカラーパレット */
            --color-primary: #2F2C1D;          /* 墨色 */
            --color-secondary: #8B7355;        /* 栗色 */
            --color-accent: #C49A6C;           /* 蜂蜜色 */
            --color-light: #F5F1EB;            /* 和紙色 */
            --color-cream: #FBF8F3;            /* クリーム */
            --color-sage: #9BAE95;             /* 草色 */
            --color-cherry: #E8B4C8;           /* 桜色 */
            --color-gold: #D4AF37;             /* 金色 */
            
            /* グレーシステム */
            --gray-50: #FAFAF9;
            --gray-100: #F5F5F4;
            --gray-200: #E7E5E4;
            --gray-300: #D6D3D1;
            --gray-400: #A8A29E;
            --gray-500: #78716C;
            --gray-600: #57534E;
            --gray-700: #44403C;
            --gray-800: #292524;
            --gray-900: #1C1917;
        }

        .font-primary {
            font-family: 'Noto Sans JP', sans-serif;
        }
        
        .font-serif {
            font-family: 'Noto Serif JP', serif;
        }

        /* 背景グラデーション */
        .bg-washi {
            background: linear-gradient(135deg, 
                var(--color-cream) 0%, 
                var(--color-light) 30%, 
                #FFFFFF 60%, 
                var(--color-light) 100%);
        }

        .bg-section {
            background: linear-gradient(180deg, 
                rgba(245, 241, 235, 0.3) 0%, 
                rgba(255, 255, 255, 0.8) 50%, 
                rgba(245, 241, 235, 0.3) 100%);
        }

        /* 和モダンボタン */
        .btn-primary {
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%);
            color: white;
            padding: 16px 32px;
            border-radius: 50px;
            font-weight: 600;
            letter-spacing: 0.5px;
            box-shadow: 0 8px 32px rgba(47, 44, 29, 0.3);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 2px solid transparent;
            position: relative;
            overflow: hidden;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 40px rgba(47, 44, 29, 0.4);
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, 
                transparent, 
                rgba(255, 255, 255, 0.2), 
                transparent);
            transition: left 0.5s;
        }

        .btn-primary:hover::before {
            left: 100%;
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.9);
            color: var(--color-primary);
            border: 2px solid var(--color-secondary);
            padding: 16px 32px;
            border-radius: 50px;
            font-weight: 600;
            letter-spacing: 0.5px;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background: var(--color-light);
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(139, 115, 85, 0.2);
        }

        /* 和モダンカード */
        .card-washi {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(139, 115, 85, 0.1);
            border-radius: 24px;
            box-shadow: 
                0 8px 32px rgba(47, 44, 29, 0.06),
                inset 0 1px 0 rgba(255, 255, 255, 0.8);
            position: relative;
        }

        .card-washi::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, 
                transparent, 
                var(--color-accent), 
                transparent);
        }

        /* アニメーション */
        .fade-in {
            opacity: 0;
            transform: translateY(30px);
            animation: fadeIn 1s ease-out forwards;
        }

        .fade-in-delay-1 { animation-delay: 0.2s; }
        .fade-in-delay-2 { animation-delay: 0.4s; }
        .fade-in-delay-3 { animation-delay: 0.6s; }
        .fade-in-delay-4 { animation-delay: 0.8s; }

        @keyframes fadeIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .slide-in-left {
            opacity: 0;
            transform: translateX(-50px);
            animation: slideInLeft 1s ease-out forwards;
        }

        @keyframes slideInLeft {
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* 24節気視覚化 */
        .season-circle {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            position: relative;
            background: conic-gradient(
                var(--color-cherry) 0deg 90deg,
                var(--color-sage) 90deg 180deg,
                var(--color-accent) 180deg 270deg,
                #B8D4E3 270deg 360deg
            );
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }

        .season-circle::before {
            content: '';
            position: absolute;
            inset: 8px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
        }

        .season-circle .emoji {
            position: relative;
            z-index: 1;
        }

        /* 店舗カード */
        .shop-card {
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(139, 115, 85, 0.15);
            border-radius: 16px;
            padding: 20px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .shop-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, 
                var(--color-cherry), 
                var(--color-sage), 
                var(--color-accent));
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .shop-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 40px rgba(47, 44, 29, 0.12);
        }

        .shop-card:hover::before {
            transform: scaleX(1);
        }

        .shop-card.new-shop {
            background: linear-gradient(135deg, 
                rgba(232, 180, 200, 0.1) 0%, 
                rgba(255, 255, 255, 0.95) 100%);
            border-color: var(--color-cherry);
            animation: newShopPulse 2s ease-in-out;
        }

        @keyframes newShopPulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(232, 180, 200, 0.4); }
            50% { box-shadow: 0 0 20px 10px rgba(232, 180, 200, 0); }
        }

        /* 地図スタイル */
        .demo-map {
            height: 480px;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 16px 48px rgba(47, 44, 29, 0.15);
            border: 1px solid rgba(139, 115, 85, 0.2);
        }

        /* 問題提起セクション */
        .problem-section {
            position: relative;
            background: linear-gradient(135deg, 
                var(--gray-100) 0%, 
                var(--gray-200) 100%);
        }

        .problem-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                radial-gradient(circle at 25% 25%, rgba(139, 115, 85, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 75% 75%, rgba(196, 154, 108, 0.1) 0%, transparent 50%);
        }

        /* スクロール時の要素表示 */
        .reveal {
            opacity: 0;
            transform: translateY(50px);
            transition: all 0.8s ease;
        }

        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }

        /* レスポンシブ調整 */
        @media (max-width: 768px) {
            .season-circle {
                width: 80px;
                height: 80px;
                font-size: 1.5rem;
            }
            
            .demo-map {
                height: 300px;
            }
        }
    </style>
</head>
<body class="font-primary bg-washi">

    <!-- Header -->
    <header class="fixed top-0 w-full bg-white/95 backdrop-blur-md border-b border-gray-200/50 z-50">
        <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center">
                    <h1 class="text-2xl font-serif font-bold" style="color: var(--color-primary);">
                        TasteRetreat
                    </h1>
                </div>
                
                <nav class="hidden md:flex space-x-8">
                    <a href="#concept" class="text-gray-700 hover:text-gray-900 font-medium transition-colors">コンセプト</a>
                    <a href="#experience" class="text-gray-700 hover:text-gray-900 font-medium transition-colors">体験する</a>
                    <a href="#seasons" class="text-gray-700 hover:text-gray-900 font-medium transition-colors">24節気</a>
                </nav>
                
                <div class="flex items-center space-x-4">
                    @guest
                        <a href="{{ route('login') }}" class="btn-primary">
                            はじめる
                        </a>
                    @else
                        <a href="{{ route('home') }}" class="btn-secondary">
                            マイリストへ
                        </a>
                    @endguest
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="pt-32 pb-20 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
            <!-- 問題提起 -->
            <div class="text-center mb-16 fade-in">
                <div class="inline-block p-6 bg-white/60 backdrop-blur-sm rounded-2xl border border-gray-200/50 mb-12">
                    <p class="text-lg text-gray-600 mb-4">
                        グルメサイトの情報に迷っていませんか？
                    </p>
                    <div class="flex justify-center items-center space-x-6 text-gray-500">
                        <div class="text-center">
                            <div class="text-2xl mb-1">📱</div>
                            <p class="text-xs">広告だらけ</p>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl mb-1">😵</div>
                            <p class="text-xs">情報過多</p>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl mb-1">🤔</div>
                            <p class="text-xs">他人の評価</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- メインメッセージ -->
            <div class="text-center fade-in-delay-1">
                <h1 class="text-5xl md:text-7xl font-serif font-bold mb-8" style="color: var(--color-primary); line-height: 1.2;">
                    "<span style="color: var(--color-secondary);">本当に価値のあるお店</span>"と<br>
                    出会う体験を、<br>
                    <span style="color: var(--color-accent);">あなた自身の手で。</span>
                </h1>
                
                <p class="text-xl md:text-2xl text-gray-700 mb-12 max-w-4xl mx-auto leading-relaxed fade-in-delay-2">
                    TasteRetreatは、あなたの人生の食体験をサポートする相棒です。<br>
                    <span class="font-semibold" style="color: var(--color-secondary);">24節気</span>と共に歩む、<br>
                    日常の収集から特別な日まで。
                </p>
                
                <div class="flex flex-col sm:flex-row gap-6 justify-center items-center fade-in-delay-3">
                    <a href="#experience" class="btn-primary text-lg">
                        体験してみる
                    </a>
                    <a href="#concept" class="btn-secondary text-lg">
                        詳しく知る
                    </a>
                </div>
            </div>
        </div>

        <!-- 装飾的な要素 -->
        <div class="absolute top-20 right-10 opacity-20">
            <div class="season-circle">
                <span class="emoji">🌸</span>
            </div>
        </div>
        <div class="absolute bottom-20 left-10 opacity-20">
            <div class="season-circle">
                <span class="emoji">🍂</span>
            </div>
        </div>
    </section>

    <!-- コンセプト説明セクション -->
    <section id="concept" class="py-20 bg-section">
        <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-serif font-bold mb-8" style="color: var(--color-primary);">
                    あなただけの価値観で選ぶ
                </h2>
                <p class="text-xl text-gray-700 max-w-3xl mx-auto leading-relaxed">
                    他人のレコメンドではなく、あなた自身の感覚を大切に。<br>
                    日常的に気になる店を収集し、大切な日に迷わない。
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8 mb-16">
                <div class="card-washi p-8 text-center reveal">
                    <div class="text-4xl mb-4">🎯</div>
                    <h3 class="text-xl font-bold mb-4" style="color: var(--color-primary);">個人完結型</h3>
                    <p class="text-gray-600 leading-relaxed">
                        他人の評価に左右されず、あなたの価値観だけで店舗を選択・管理します
                    </p>
                </div>

                <div class="card-washi p-8 text-center reveal">
                    <div class="text-4xl mb-4">📝</div>
                    <h3 class="text-xl font-bold mb-4" style="color: var(--color-primary);">日常収集</h3>
                    <p class="text-gray-600 leading-relaxed">
                        普段から気になるお店を24店舗まで厳選。いざという時に迷いません
                    </p>
                </div>

                <div class="card-washi p-8 text-center reveal">
                    <div class="text-4xl mb-4">🌱</div>
                    <h3 class="text-xl font-bold mb-4" style="color: var(--color-primary);">人生と共に</h3>
                    <p class="text-gray-600 leading-relaxed">
                        価値観の変化と共にリストも更新。過去の選択で人生を振り返る
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- インタラクティブ体験セクション -->
    <section id="experience" class="py-20" x-data="tasteRetreatDemo">
        <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-serif font-bold mb-8" style="color: var(--color-primary);">
                    実際に体験してみましょう
                </h2>
                <p class="text-xl text-gray-700 max-w-3xl mx-auto">
                    お店を検索して、あなたの24節気名店リストに追加する体験をお試しください
                </p>
            </div>

            <div class="card-washi p-10 mb-16">
                <div class="grid lg:grid-cols-2 gap-12">
                    <!-- 左: 店舗検索とリスト -->
                    <div>
                        <h3 class="text-2xl font-bold mb-6" style="color: var(--color-primary);">
                            新しいお店を発見する
                        </h3>
                        
                        <!-- 検索フォーム -->
                        <div class="space-y-4 mb-8">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    お店や場所を検索
                                </label>
                                <input 
                                    type="text" 
                                    x-model="searchQuery"
                                    @input="searchShops"
                                    placeholder="店名、料理名、場所を入力..."
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-opacity-50 focus:border-transparent transition-all"
                                    style="focus:ring-color: var(--color-secondary);"
                                >
                            </div>
                            
                            <!-- 検索結果 -->
                            <div x-show="showResults && searchResults.length > 0" 
                                 class="border border-gray-200 rounded-xl max-h-60 overflow-y-auto bg-white shadow-lg">
                                <template x-for="(shop, index) in searchResults" :key="index">
                                    <div 
                                        @click="selectShop(shop)"
                                        class="p-4 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-b-0 transition-colors"
                                    >
                                        <div class="font-medium text-gray-900" x-text="shop.name"></div>
                                        <div class="text-sm text-gray-600" x-text="shop.address"></div>
                                    </div>
                                </template>
                            </div>
                            
                            <!-- 選択した店舗 -->
                            <div x-show="selectedShop" 
                                 class="p-4 bg-gradient-to-r from-green-50 to-blue-50 border-2 border-green-200 rounded-xl">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="font-semibold text-green-800" x-text="selectedShop?.name"></div>
                                        <div class="text-sm text-green-600" x-text="selectedShop?.address"></div>
                                    </div>
                                    <button 
                                        @click="addToMyList"
                                        class="btn-primary text-sm"
                                    >
                                        リストに追加
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- 24節気名店リスト -->
                        <div>
                            <div class="flex items-center mb-6">
                                <div class="season-circle mr-4" style="width: 50px; height: 50px; font-size: 1.2rem;">
                                    <span class="emoji">🍃</span>
                                </div>
                                <div>
                                    <h4 class="text-xl font-bold" style="color: var(--color-primary);">
                                        あなたの24節気名店リスト
                                    </h4>
                                    <p class="text-sm text-gray-600">
                                        <span x-text="myShopList.length"></span>/24店舗
                                    </p>
                                </div>
                            </div>
                            
                            <div class="space-y-3 max-h-80 overflow-y-auto">
                                <template x-for="(shop, index) in myShopList" :key="shop.id">
                                    <div class="shop-card" :class="{ 'new-shop': shop.isNew }">
                                        <div class="flex items-center justify-between">
                                            <div class="flex-1">
                                                <div class="flex items-center mb-2">
                                                    <span class="text-xl mr-2" x-text="shop.season || '🌱'"></span>
                                                    <span class="font-semibold" x-text="shop.name"></span>
                                                    <span x-show="shop.isNew" 
                                                          class="ml-2 bg-gradient-to-r from-pink-400 to-red-400 text-white text-xs px-2 py-1 rounded-full font-medium animate-pulse">
                                                        NEW
                                                    </span>
                                                </div>
                                                <div class="text-sm text-gray-600 mb-1" x-text="shop.address"></div>
                                                <div class="text-xs font-medium" 
                                                     style="color: var(--color-secondary);" 
                                                     x-text="shop.seasonName || '新緑の季節'">
                                                </div>
                                            </div>
                                            <button 
                                                @click="focusOnMap(shop)"
                                                class="btn-secondary text-sm"
                                            >
                                                地図で見る
                                            </button>
                                        </div>
                                    </div>
                                </template>
                                
                                <!-- 空きスロット -->
                                <template x-for="i in Math.max(0, 6 - myShopList.length)" :key="'empty-' + i">
                                    <div class="p-4 border-2 border-dashed border-gray-300 rounded-xl bg-gray-50/50 flex items-center justify-center text-gray-400">
                                        <div class="text-center">
                                            <div class="text-xl mb-1">＋</div>
                                            <div class="text-xs">お気に入りを追加</div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                            
                            <!-- 登録誘導 -->
                            <div x-show="myShopList.length > 3" 
                                 class="mt-8 p-6 bg-gradient-to-r from-yellow-50 via-orange-50 to-red-50 border-2 border-orange-200 rounded-2xl text-center">
                                <h5 class="text-lg font-bold mb-2" style="color: var(--color-primary);">
                                    🌸 24節気と共に歩む美食の旅を始めませんか？
                                </h5>
                                <p class="text-sm text-gray-700 mb-4">
                                    この続きを体験し、あなただけの季節の名店リストを完成させましょう
                                </p>
                                <a href="{{ route('login') }}" class="btn-primary">
                                    今すぐはじめる
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- 右: インタラクティブマップ -->
                    <div>
                        <h3 class="text-2xl font-bold mb-6" style="color: var(--color-primary);">
                            地図で確認する
                        </h3>
                        <div id="demo-map" class="demo-map bg-gray-100 flex items-center justify-center">
                            <div class="text-center text-gray-500">
                                <div class="text-4xl mb-2">🗺️</div>
                                <div>地図を読み込み中...</div>
                            </div>
                        </div>
                        
                        <!-- 地図凡例 -->
                        <div class="mt-4 flex flex-wrap gap-4 text-sm">
                            <div class="flex items-center">
                                <div class="w-3 h-3 rounded-full mr-2" style="background-color: var(--color-secondary);"></div>
                                <span>既存の名店</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-3 h-3 rounded-full mr-2" style="background-color: var(--color-cherry);"></div>
                                <span>新規追加</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 24節気セクション -->
    <section id="seasons" class="py-20 bg-section">
        <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-serif font-bold mb-8" style="color: var(--color-primary);">
                    24節気と共に歩む
                </h2>
                <p class="text-xl text-gray-700 max-w-4xl mx-auto leading-relaxed">
                    古来より日本人が大切にしてきた24節気の智慧に学び、<br>
                    季節の移ろいと共に、人生を彩る名店を選び抜く。<br>
                    そんな<span class="font-semibold" style="color: var(--color-secondary);">丁寧な暮らし</span>をご一緒に。
                </p>
            </div>

            <div class="grid md:grid-cols-4 gap-8 mb-16">
                <!-- 春 -->
                <div class="card-washi p-6 text-center reveal">
                    <div class="season-circle mx-auto mb-4">
                        <span class="emoji">🌸</span>
                    </div>
                    <h3 class="text-xl font-bold mb-3" style="color: var(--color-primary);">春</h3>
                    <p class="text-sm text-gray-700 mb-4">立春、雨水、啓蟄、春分、清明、穀雨</p>
                    <div class="space-y-2 text-xs text-gray-600">
                        <div class="p-2 bg-pink-50 rounded-lg">新緑と共に味わう繊細な料理</div>
                    </div>
                </div>

                <!-- 夏 -->
                <div class="card-washi p-6 text-center reveal">
                    <div class="season-circle mx-auto mb-4">
                        <span class="emoji">🌻</span>
                    </div>
                    <h3 class="text-xl font-bold mb-3" style="color: var(--color-primary);">夏</h3>
                    <p class="text-sm text-gray-700 mb-4">立夏、小満、芒種、夏至、小暑、大暑</p>
                    <div class="space-y-2 text-xs text-gray-600">
                        <div class="p-2 bg-yellow-50 rounded-lg">暑さを忘れる涼やかな味わい</div>
                    </div>
                </div>

                <!-- 秋 -->
                <div class="card-washi p-6 text-center reveal">
                    <div class="season-circle mx-auto mb-4">
                        <span class="emoji">🍂</span>
                    </div>
                    <h3 class="text-xl font-bold mb-3" style="color: var(--color-primary);">秋</h3>
                    <p class="text-sm text-gray-700 mb-4">立秋、処暑、白露、秋分、寒露、霜降</p>
                    <div class="space-y-2 text-xs text-gray-600">
                        <div class="p-2 bg-orange-50 rounded-lg">豊穣の恵みを堪能する</div>
                    </div>
                </div>

                <!-- 冬 -->
                <div class="card-washi p-6 text-center reveal">
                    <div class="season-circle mx-auto mb-4">
                        <span class="emoji">❄️</span>
                    </div>
                    <h3 class="text-xl font-bold mb-3" style="color: var(--color-primary);">冬</h3>
                    <p class="text-sm text-gray-700 mb-4">立冬、小雪、大雪、冬至、小寒、大寒</p>
                    <div class="space-y-2 text-xs text-gray-600">
                        <div class="p-2 bg-blue-50 rounded-lg">心身を温める滋味深い料理</div>
                    </div>
                </div>
            </div>

            <!-- なぜ24という数字？ -->
            <div class="text-center">
                <div class="inline-block p-8 card-washi max-w-3xl">
                    <h3 class="text-2xl font-bold mb-4" style="color: var(--color-primary);">
                        なぜ24という数字？
                    </h3>
                    <p class="text-gray-700 leading-relaxed">
                        人生において本当に大切にしたいお店は、実はそれほど多くありません。<br>
                        24節気という古来の智慧に学び、<br>
                        <span class="font-semibold" style="color: var(--color-secondary);">季節と共に、丁寧に選び抜く</span>。<br>
                        そんな美しい暮らし方をTasteRetreatは提案します。
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- 最終CTA -->
    <section class="py-20 relative overflow-hidden" style="background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 50%, var(--color-accent) 100%);">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="relative max-w-5xl mx-auto text-center px-6 sm:px-8 lg:px-12">
            <h2 class="text-4xl md:text-6xl font-serif font-bold text-white mb-8">
                あなただけの<br>
                <span class="text-yellow-300">美食の旅</span>を<br>
                始めませんか？
            </h2>
            <p class="text-xl md:text-2xl text-white/90 mb-12 max-w-3xl mx-auto leading-relaxed">
                24節気と共に歩む、丁寧な食の暮らし。<br>
                本当に価値のあるお店との出会いが、<br>
                今日から始まります。
            </p>
            <div class="flex flex-col sm:flex-row gap-6 justify-center">
                <a href="{{ route('login') }}" class="bg-white text-gray-900 px-12 py-5 rounded-full text-xl font-bold hover:bg-gray-100 transition-all inline-block shadow-lg hover:shadow-xl hover:-translate-y-1">
                    今すぐはじめる
                </a>
                <a href="#experience" class="border-2 border-white text-white px-12 py-5 rounded-full text-lg font-semibold hover:bg-white hover:text-gray-900 transition-all inline-block">
                    もう一度体験する
                </a>
            </div>
            <div class="mt-8 text-white/80 text-sm">
                ✨ 無料でご利用いただけます | 🌸 24節気の季節感を大切に | 🍃 広告なしの純粋な体験
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
            <div class="text-center">
                <h3 class="text-2xl font-serif font-bold mb-4" style="color: var(--color-accent);">TasteRetreat</h3>
                <p class="text-gray-400 mb-6">本当に価値のあるお店と出会う体験を、あなた自身の手で。</p>
                <div class="flex justify-center space-x-6">
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">利用規約</a>
                    <a href="{{ route('privacy-policy') }}" class="text-gray-400 hover:text-white transition-colors">プライバシーポリシー</a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">お問い合わせ</a>
                </div>
                <div class="mt-8 pt-8 border-t border-gray-800 text-gray-500 text-sm">
                    © 2025 TasteRetreat. All rights reserved.
                </div>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script>
        // スクロール時要素表示
        const observerCallback = (entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                }
            });
        };

        const observer = new IntersectionObserver(observerCallback, {
            threshold: 0.1
        });

        document.querySelectorAll('.reveal').forEach(el => {
            observer.observe(el);
        });

        // Alpine.js デモ機能
        document.addEventListener('alpine:init', () => {
            Alpine.data('tasteRetreatDemo', () => ({
                searchQuery: '',
                searchResults: [],
                selectedShop: null,
                showResults: false,
                myShopList: [
                    { 
                        id: 1, 
                        name: '銀座 久兵衛', 
                        address: '東京都中央区銀座8-7-6', 
                        lat: 35.6716, 
                        lng: 139.7669, 
                        isNew: false, 
                        season: '🌸', 
                        seasonName: '清明 - 桜舞い散る頃の江戸前鮨' 
                    },
                    { 
                        id: 2, 
                        name: '代官山 蔦', 
                        address: '東京都渋谷区猿楽町9-7', 
                        lat: 35.6499, 
                        lng: 139.6985, 
                        isNew: false, 
                        season: '🍂', 
                        seasonName: '霜降 - 紅葉とフランス料理の調和' 
                    },
                    { 
                        id: 3, 
                        name: 'ジョエル・ロブション', 
                        address: '東京都目黒区三田1-13-1', 
                        lat: 35.6328, 
                        lng: 139.7153, 
                        isNew: false, 
                        season: '❄️', 
                        seasonName: '大寒 - 冬の宵の特別な時間' 
                    }
                ],
                seasons: ['🌸', '🌿', '🌻', '🍂', '❄️', '🌱'],
                seasonNames: [
                    '立春 - 新たな美味との出会い',
                    '春分 - 桜と共に味わう',  
                    '立夏 - 初夏の爽やかな一皿',
                    '夏至 - 夏の夜長を彩る',
                    '立秋 - 秋の訪れを感じて',
                    '秋分 - 収穫の恵みに感謝',
                    '立冬 - 温かな料理に心を寄せて',
                    '冬至 - 一年で最も特別な夜に'
                ],
                map: null,
                markers: [],
                searchTimeout: null,

                init() {
                    this.initMap();
                },

                initMap() {
                    if (typeof google !== 'undefined') {
                        this.map = new google.maps.Map(document.getElementById('demo-map'), {
                            center: { lat: 35.6762, lng: 139.6503 },
                            zoom: 12,
                            styles: [
                                {
                                    featureType: 'poi.business',
                                    stylers: [{ visibility: 'off' }]
                                },
                                {
                                    featureType: 'all',
                                    elementType: 'geometry.fill',
                                    stylers: [{ saturation: -20 }]
                                }
                            ]
                        });
                        this.addExistingMarkers();
                    } else {
                        setTimeout(() => this.initMap(), 1000);
                    }
                },

                addExistingMarkers() {
                    if (!this.map) return;
                    
                    this.myShopList.forEach(shop => {
                        const marker = new google.maps.Marker({
                            position: { lat: shop.lat, lng: shop.lng },
                            map: this.map,
                            title: shop.name,
                            icon: {
                                path: google.maps.SymbolPath.CIRCLE,
                                scale: 8,
                                fillColor: '#8B7355',
                                fillOpacity: 1,
                                strokeColor: '#2F2C1D',
                                strokeWeight: 2
                            }
                        });

                        const infoWindow = new google.maps.InfoWindow({
                            content: `
                                <div class="p-3">
                                    <h3 class="font-semibold text-gray-900 mb-1">${shop.name}</h3>
                                    <p class="text-sm text-gray-600 mb-1">${shop.address}</p>
                                    <p class="text-xs text-blue-600 font-medium">${shop.seasonName}</p>
                                </div>
                            `
                        });

                        marker.addListener('click', () => {
                            this.markers.forEach(m => m.infoWindow && m.infoWindow.close());
                            infoWindow.open(this.map, marker);
                        });

                        this.markers.push({ marker, infoWindow, shop });
                    });
                },

                async searchShops() {
                    if (this.searchQuery.length < 2) {
                        this.searchResults = [];
                        this.showResults = false;
                        return;
                    }

                    clearTimeout(this.searchTimeout);
                    this.searchTimeout = setTimeout(async () => {
                        try {
                            const response = await fetch('/api/demo/shops/search', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                },
                                body: JSON.stringify({
                                    query: this.searchQuery
                                })
                            });

                            if (response.ok) {
                                const shops = await response.json();
                                this.searchResults = shops.filter(shop => 
                                    shop.name.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                                    shop.address.toLowerCase().includes(this.searchQuery.toLowerCase())
                                );
                                this.showResults = true;
                            } else {
                                this.useMockData();
                            }
                        } catch (error) {
                            console.error('Search API error:', error);
                            this.useMockData();
                        }
                    }, 500);
                },

                useMockData() {
                    const mockResults = [
                        { id: 101, name: 'タニヤ タイ料理', address: '東京都渋谷区道玄坂2-10-7', lat: 35.6580, lng: 139.6982 },
                        { id: 102, name: 'すし田中', address: '東京都港区六本木6-8-29', lat: 35.6627, lng: 139.7313 },
                        { id: 103, name: 'フレンチ・ラパン', address: '東京都千代田区丸の内1-9-1', lat: 35.6812, lng: 139.7671 },
                        { id: 104, name: 'イタリアーノ・ベッロ', address: '東京都中央区銀座3-4-12', lat: 35.6719, lng: 139.7661 }
                    ];

                    this.searchResults = mockResults.filter(shop => 
                        shop.name.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                        shop.address.toLowerCase().includes(this.searchQuery.toLowerCase())
                    );
                    this.showResults = true;
                },

                selectShop(shop) {
                    this.selectedShop = shop;
                    this.showResults = false;
                    this.searchQuery = shop.name;
                },

                addToMyList() {
                    if (!this.selectedShop || this.myShopList.length >= 24) return;

                    const randomSeasonIndex = Math.floor(Math.random() * this.seasons.length);
                    const randomSeasonNameIndex = Math.floor(Math.random() * this.seasonNames.length);
                    
                    const newShop = { 
                        ...this.selectedShop, 
                        isNew: true,
                        season: this.seasons[randomSeasonIndex],
                        seasonName: this.seasonNames[randomSeasonNameIndex]
                    };
                    this.myShopList.push(newShop);
                    
                    // Add marker to map
                    if (this.map) {
                        const marker = new google.maps.Marker({
                            position: { lat: newShop.lat, lng: newShop.lng },
                            map: this.map,
                            title: newShop.name,
                            icon: {
                                path: google.maps.SymbolPath.CIRCLE,
                                scale: 10,
                                fillColor: '#E8B4C8',
                                fillOpacity: 1,
                                strokeColor: '#2F2C1D',
                                strokeWeight: 2
                            },
                            animation: google.maps.Animation.BOUNCE
                        });

                        setTimeout(() => {
                            marker.setAnimation(null);
                        }, 1400);

                        const infoWindow = new google.maps.InfoWindow({
                            content: `
                                <div class="p-3">
                                    <h3 class="font-semibold text-gray-900 mb-1">${newShop.name}</h3>
                                    <p class="text-sm text-gray-600 mb-1">${newShop.address}</p>
                                    <p class="text-xs text-blue-600 font-medium">${newShop.seasonName}</p>
                                    <span class="inline-block bg-pink-400 text-white text-xs px-2 py-1 rounded-full font-semibold mt-1">NEW</span>
                                </div>
                            `
                        });

                        marker.addListener('click', () => {
                            this.markers.forEach(m => m.infoWindow && m.infoWindow.close());
                            infoWindow.open(this.map, marker);
                        });

                        this.markers.push({ marker, infoWindow, shop: newShop });
                        this.map.panTo({ lat: newShop.lat, lng: newShop.lng });
                    }

                    // Reset selection
                    this.selectedShop = null;
                    this.searchQuery = '';
                    
                    // Remove "NEW" label after 3 seconds
                    setTimeout(() => {
                        newShop.isNew = false;
                    }, 3000);
                },

                focusOnMap(shop) {
                    if (!this.map) return;
                    
                    this.map.panTo({ lat: shop.lat, lng: shop.lng });
                    this.map.setZoom(15);
                    
                    const markerData = this.markers.find(m => m.shop.id === shop.id);
                    if (markerData) {
                        this.markers.forEach(m => m.infoWindow && m.infoWindow.close());
                        markerData.infoWindow.open(this.map, markerData.marker);
                    }
                }
            }));
        });

        // Initialize map when Google Maps API is loaded
        function initMap() {
            // Alpine.js will handle the actual initialization
        }
    </script>
</body>
</html>