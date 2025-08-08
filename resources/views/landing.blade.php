<!DOCTYPE html>
<html lang="ja">
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>TasteRetreat - 本当に価値のあるお店を、あなたの手で</title>

    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Google Maps API (デモ用 - 実際のプロジェクトではAPIキーを環境変数で管理) -->
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places&callback=initMap"></script>
    
    <style>
        .font-japanese {
            font-family: 'Noto Sans JP', sans-serif;
        }
        
        /* 新しい爽やかなカラーパレット */
        .taste-retreat-primary {
            background: linear-gradient(135deg, #4F9CF9 0%, #3B82F6 100%);
        }
        
        .taste-retreat-secondary {
            background: linear-gradient(135deg, #E6F3FF 0%, #F0F9FF 100%);
        }
        
        .taste-retreat-accent {
            background: linear-gradient(135deg, #10B981 0%, #059669 100%);
        }
        
        .taste-retreat-text-primary {
            color: #1E40AF;
        }
        
        .taste-retreat-text-secondary {
            color: #0369A1;
        }
        
        .taste-retreat-border-primary {
            border-color: #3B82F6;
        }
        
        /* 雲から晴れへのアニメーション */
        .clouds-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, #D1D5DB 0%, #E5E7EB 50%, #F3F4F6 100%);
            z-index: 9999;
            opacity: 1;
            animation: cloudsClearAway 4s ease-in-out forwards;
        }
        
        .cloud {
            position: absolute;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 50px;
            animation: floatClouds 3s ease-in-out infinite;
        }
        
        .cloud:before,
        .cloud:after {
            content: '';
            position: absolute;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 50px;
        }
        
        .cloud:before {
            width: 50px;
            height: 50px;
            top: -25px;
            left: 10px;
        }
        
        .cloud:after {
            width: 60px;
            height: 60px;
            top: -35px;
            right: 15px;
        }
        
        .cloud1 {
            width: 100px;
            height: 60px;
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }
        
        .cloud2 {
            width: 80px;
            height: 40px;
            top: 40%;
            right: 20%;
            animation-delay: 1s;
        }
        
        .cloud3 {
            width: 120px;
            height: 50px;
            top: 60%;
            left: 30%;
            animation-delay: 0.5s;
        }
        
        @keyframes cloudsClearAway {
            0% {
                opacity: 1;
                transform: scale(1);
            }
            70% {
                opacity: 0.3;
                transform: scale(1.1);
            }
            100% {
                opacity: 0;
                transform: scale(1.3);
                pointer-events: none;
            }
        }
        
        @keyframes floatClouds {
            0%, 100% {
                transform: translateY(0px) translateX(0px);
            }
            50% {
                transform: translateY(-10px) translateX(5px);
            }
        }
        
        /* 問題提起からソリューションへのフェード */
        .problem-statement {
            opacity: 0;
            transform: translateY(30px);
            animation: fadeInUp 2s ease-out 4.5s forwards;
        }
        
        .solution-statement {
            opacity: 0;
            transform: translateY(30px);
            animation: fadeInUp 2s ease-out 6s forwards;
        }
        
        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* CTA強調アニメーション */
        .primary-cta {
            position: relative;
            overflow: hidden;
            background: linear-gradient(45deg, #10B981, #059669);
            border: 3px solid transparent;
            background-clip: padding-box;
            animation: ctaGlow 3s ease-in-out infinite;
            box-shadow: 0 8px 32px rgba(16, 185, 129, 0.3);
        }
        
        .primary-cta:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 40px rgba(16, 185, 129, 0.4);
        }
        
        @keyframes ctaGlow {
            0%, 100% {
                box-shadow: 0 8px 32px rgba(16, 185, 129, 0.3);
            }
            50% {
                box-shadow: 0 8px 32px rgba(16, 185, 129, 0.6);
            }
        }
        
        /* レイアウト改善 - より広い余白 */
        .section-spacer {
            padding: 8rem 0;
        }
        
        .content-spacer {
            margin: 4rem 0;
        }
        
        .element-spacer {
            margin: 2rem 0;
        }
        
        /* 24節気デザイン */
        .seasons-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
        }
        
        .season-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(240, 249, 255, 0.9) 100%);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(59, 130, 246, 0.1);
            border-radius: 16px;
            padding: 2rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .season-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(59, 130, 246, 0.15);
        }
        
        .season-card:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #3B82F6, #10B981, #F59E0B, #EF4444);
        }
        
        .demo-map {
            height: 500px;
            border-radius: 20px;
            box-shadow: 0 16px 48px rgba(0, 0, 0, 0.1);
        }
        
        .shop-list-item {
            transition: all 0.3s ease;
            border-radius: 12px;
            margin-bottom: 1rem;
        }
        
        .shop-list-item.new-item {
            background: linear-gradient(135deg, #FEF3C7 0%, #FDE68A 100%);
            border-left: 4px solid #10B981;
            animation: fadeInSlide 0.8s ease-out;
            box-shadow: 0 4px 16px rgba(16, 185, 129, 0.2);
        }
        
        @keyframes fadeInSlide {
            from {
                opacity: 0;
                transform: translateX(-30px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateX(0) scale(1);
            }
        }
        
        /* スムーズな表示順序制御 */
        .stagger-in {
            opacity: 0;
            transform: translateY(20px);
            animation: staggerFadeIn 0.6s ease-out forwards;
        }
        
        .stagger-in:nth-child(1) { animation-delay: 0.1s; }
        .stagger-in:nth-child(2) { animation-delay: 0.2s; }
        .stagger-in:nth-child(3) { animation-delay: 0.3s; }
        .stagger-in:nth-child(4) { animation-delay: 0.4s; }
        .stagger-in:nth-child(5) { animation-delay: 0.5s; }
        
        @keyframes staggerFadeIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* レスポンシブ改善 */
        @media (max-width: 768px) {
            .section-spacer {
                padding: 4rem 0;
            }
            
            .content-spacer {
                margin: 2rem 0;
            }
            
            .demo-map {
                height: 300px;
            }
        }
    </style>
</head>
<body class="antialiased font-japanese bg-gradient-to-br from-blue-50 via-white to-green-50">

    <!-- 雲から晴れへのアニメーション -->
    <div class="clouds-overlay">
        <div class="cloud cloud1"></div>
        <div class="cloud cloud2"></div>
        <div class="cloud cloud3"></div>
    </div>

    <!-- Header -->
    <header class="fixed top-0 w-full bg-white/95 backdrop-blur-sm border-b border-blue-100 z-50">
        <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-10">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center">
                    <h1 class="text-3xl font-bold taste-retreat-text-primary">TasteRetreat</h1>
                </div>
                <nav class="hidden md:flex space-x-10">
                    <a href="#about" class="text-gray-700 hover:taste-retreat-text-primary transition-colors font-medium">サービスについて</a>
                    <a href="#demo" class="text-gray-700 hover:taste-retreat-text-primary transition-colors font-medium">体験してみる</a>
                    <a href="#seasons" class="text-gray-700 hover:taste-retreat-text-primary transition-colors font-medium">24節気</a>
                </nav>
                <div class="flex items-center space-x-6">
                    @guest
                        <a href="{{ route('login') }}" class="primary-cta text-white px-8 py-3 rounded-full text-lg font-semibold transition-all">ログイン</a>
                    @else
                        <a href="{{ route('home') }}" class="taste-retreat-primary text-white px-8 py-3 rounded-full text-lg font-semibold hover:bg-opacity-90 transition-all">ダッシュボード</a>
                    @endguest

                </div>
            </div>
        </div>
    </header>


    <!-- Hero Section with Problem/Solution Narrative -->
    <section class="section-spacer pt-32 lg:pt-40">
        <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-10">
            <div class="text-center">
                <!-- 問題提起 -->
                <div class="problem-statement content-spacer">
                    <h2 class="text-2xl md:text-3xl font-medium text-gray-600 mb-8 leading-relaxed">
                        グルメ情報に溢れる現代、<br>
                        <span class="text-gray-800 font-semibold">本当に信頼できる情報</span>を見つけるのは難しくありませんか？
                    </h2>
                    <div class="flex justify-center items-center space-x-8 text-gray-500 mb-8">
                        <div class="text-center">
                            <div class="text-3xl mb-2">📱</div>
                            <p class="text-sm">広告だらけの検索結果</p>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl mb-2">💰</div>
                            <p class="text-sm">ステマレビュー</p>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl mb-2">😵</div>
                            <p class="text-sm">情報過多で迷う</p>
                        </div>
                    </div>
                </div>

                <!-- ソリューション -->
                <div class="solution-statement content-spacer">
                    <h1 class="text-5xl md:text-7xl font-bold text-gray-900 leading-tight mb-8">
                        本当に価値のあるお店を、<br>
                        <span class="taste-retreat-text-primary">あなたの手で</span>
                    </h1>
                    <p class="text-xl md:text-2xl text-gray-700 mb-12 max-w-4xl mx-auto leading-relaxed">
                        広告に依存しない、信頼できるグルメ情報コミュニティ。<br>
                        <span class="font-semibold taste-retreat-text-secondary">24節気</span>に基づいて、人生で大切なお店を厳選し、<br>
                        あなただけの名店リストを作りましょう。
                    </p>
                    
                    <div class="flex flex-col sm:flex-row gap-6 justify-center element-spacer">
                        <a href="#demo" class="taste-retreat-primary text-white px-10 py-4 rounded-full text-xl font-semibold transition-all hover:shadow-lg hover:-translate-y-1 stagger-in">体験してみる</a>
                        <a href="#about" class="border-2 taste-retreat-border-primary taste-retreat-text-primary px-10 py-4 rounded-full text-xl font-semibold hover:taste-retreat-secondary transition-all hover:-translate-y-1 stagger-in">詳しく見る</a>
                        <a href="{{ route('login') }}" class="primary-cta text-white px-10 py-4 rounded-full text-xl font-bold transition-all stagger-in">今すぐログイン</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Interactive Demo Section -->
    <section id="demo" class="section-spacer taste-retreat-secondary" x-data="demoExperience">
        <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-10">
            <div class="text-center content-spacer">
                <h3 class="text-4xl md:text-5xl font-bold text-gray-900 mb-8">TasteRetreatを体験してみる</h3>
                <p class="text-xl text-gray-700 max-w-3xl mx-auto leading-relaxed">
                    実際にお店を検索して、地図で確認し、あなたの24節気名店リストに追加してみましょう
                </p>
            </div>

            <!-- Demo Interface -->
            <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-2xl p-10 mb-16 border border-blue-100">
                <div class="grid lg:grid-cols-2 gap-12">
                    <!-- Left: Shop Search and List -->
                    <div>
                        <h4 class="text-xl font-semibold mb-6 taste-retreat-text-primary">新しいお店を追加</h4>
                        
                        <!-- Search Form -->
                        <div class="space-y-4 mb-8">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">お店を検索</label>
                                <input 
                                    type="text" 
                                    x-model="searchQuery"
                                    @input="searchShops"
                                    placeholder="店名や場所を入力してください..."
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-opacity-50 focus:ring-yellow-400 focus:border-transparent"
                                >
                            </div>
                            
                            <!-- Search Results -->
                            <div x-show="showResults && searchResults.length > 0" class="border border-gray-200 rounded-lg max-h-60 overflow-y-auto">
                                <template x-for="(shop, index) in searchResults" :key="index">
                                    <div 
                                        @click="selectShop(shop)"
                                        class="p-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-b-0"
                                    >
                                        <div class="font-medium" x-text="shop.name"></div>
                                        <div class="text-sm text-gray-600" x-text="shop.address"></div>
                                    </div>
                                </template>
                            </div>
                            
                            <!-- Selected Shop -->
                            <div x-show="selectedShop" class="p-4 bg-green-50 border border-green-200 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="font-medium text-green-800" x-text="selectedShop?.name"></div>
                                        <div class="text-sm text-green-600" x-text="selectedShop?.address"></div>
                                    </div>
                                    <button 
                                        @click="addToMyList"
                                        class="taste-retreat-primary text-white px-4 py-2 rounded-lg hover:bg-opacity-90 transition-all"
                                    >
                                        リストに追加
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- 24節気名店リスト -->
                        <div>
                            <h5 class="text-xl font-semibold mb-6 taste-retreat-text-primary flex items-center">
                                <span class="mr-2">🍂</span>
                                24節気名店リスト 
                                <span class="ml-2 text-base font-normal">(<span x-text="myShopList.length"></span>/24)</span>
                            </h5>
                            
                            <div class="space-y-3 max-h-96 overflow-y-auto">
                                <template x-for="(shop, index) in myShopList" :key="shop.id">
                                    <div 
                                        class="p-4 border border-blue-200 rounded-xl shop-list-item bg-white/70 backdrop-blur-sm"
                                        :class="{ 'new-item': shop.isNew }"
                                    >
                                        <div class="flex items-center justify-between">
                                            <div class="flex-1">
                                                <div class="font-semibold flex items-center mb-1">
                                                    <span class="mr-2" x-text="shop.season || '🍃'"></span>
                                                    <span x-text="shop.name"></span>
                                                    <span x-show="shop.isNew" class="ml-2 bg-green-400 text-green-800 text-xs px-3 py-1 rounded-full font-semibold animate-pulse">NEW</span>
                                                </div>
                                                <div class="text-sm text-gray-600 mb-2" x-text="shop.address"></div>
                                                <div class="text-xs text-blue-600 font-medium" x-text="shop.seasonName || '新緑の季節'"></div>
                                            </div>
                                            <button 
                                                @click="focusOnMap(shop)"
                                                class="taste-retreat-primary text-white px-4 py-2 rounded-full text-sm font-medium hover:shadow-lg transition-all"
                                            >
                                                地図で見る
                                            </button>
                                        </div>
                                    </div>
                                </template>
                                
                                <!-- 空きスロット表示 -->
                                <template x-for="i in (24 - myShopList.length)" :key="'empty-' + i">
                                    <div class="p-4 border-2 border-dashed border-blue-200 rounded-xl bg-blue-50/30 flex items-center justify-center text-blue-400">
                                        <div class="text-center">
                                            <div class="text-2xl mb-1">＋</div>
                                            <div class="text-xs font-medium">節気を彩る名店を追加</div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                            
                            <!-- ログイン誘導CTA -->
                            <div x-show="myShopList.length > 0" class="mt-8 p-6 bg-gradient-to-r from-green-100 to-blue-100 border-2 border-green-200 rounded-2xl">
                                <div class="text-center">
                                    <h6 class="text-lg font-semibold taste-retreat-text-primary mb-2">
                                        🌸 24節気と共に歩む美食の旅を始めませんか？
                                    </h6>
                                    <p class="text-sm text-gray-700 mb-4">この続きを体験し、あなただけの季節の名店リストを完成させましょう</p>
                                    <a href="{{ route('login') }}" class="primary-cta text-white px-8 py-3 rounded-full text-lg font-bold transition-all inline-block">今すぐログイン</a>

                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Right: Interactive Map -->
                    <div>
                        <h4 class="text-xl font-semibold mb-6 taste-retreat-text-primary">地図で確認</h4>
                        <div id="demo-map" class="demo-map bg-gray-200 flex items-center justify-center text-gray-500">
                            <div class="text-center">
                                <div class="text-4xl mb-2">🗺️</div>
                                <div>地図を読み込み中...</div>
                            </div>
                        </div>
                        
                        <!-- Map Legend -->
                        <div class="mt-4 flex flex-wrap gap-4 text-sm">
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-blue-500 rounded-full mr-2"></div>
                                <span>既存の名店</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-red-500 rounded-full mr-2"></div>
                                <span>新規追加</span>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- 24節気セクション -->
    <section id="seasons" class="section-spacer bg-gradient-to-br from-green-50 via-white to-blue-50">
        <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-10">
            <div class="text-center content-spacer">
                <h3 class="text-4xl md:text-5xl font-bold text-gray-900 mb-8">24節気と共に歩む美食の旅</h3>
                <p class="text-xl text-gray-700 max-w-4xl mx-auto leading-relaxed mb-12">
                    古来より日本人が大切にしてきた24節気。<br>
                    季節の移ろいと共に、人生を彩る名店を一つずつ選び抜いていく。<br>
                    そんな<span class="font-semibold taste-retreat-text-primary">丁寧な暮らし</span>をTasteRetreatでご一緒に。
                </p>
            </div>

            <div class="seasons-grid element-spacer">
                <!-- 春 -->
                <div class="season-card stagger-in">
                    <div class="text-4xl mb-4">🌸</div>
                    <h4 class="text-xl font-bold mb-3 taste-retreat-text-primary">春 - 6節気</h4>
                    <p class="text-gray-700 text-sm leading-relaxed mb-4">
                        立春、雨水、啓蟄、春分、清明、穀雨
                    </p>
                    <div class="space-y-2 text-xs text-gray-600">
                        <div class="p-2 bg-pink-50 rounded-lg">新緑と共に味わう繊細な料理</div>
                        <div class="p-2 bg-pink-50 rounded-lg">桜の季節の特別な出会い</div>
                    </div>
                </div>

                <!-- 夏 -->
                <div class="season-card stagger-in">
                    <div class="text-4xl mb-4">🌻</div>
                    <h4 class="text-xl font-bold mb-3 taste-retreat-text-primary">夏 - 6節気</h4>
                    <p class="text-gray-700 text-sm leading-relaxed mb-4">
                        立夏、小満、芒種、夏至、小暑、大暑
                    </p>
                    <div class="space-y-2 text-xs text-gray-600">
                        <div class="p-2 bg-yellow-50 rounded-lg">暑さを忘れる涼やかな味わい</div>
                        <div class="p-2 bg-yellow-50 rounded-lg">夏祭りの思い出と共に</div>
                    </div>
                </div>

                <!-- 秋 -->
                <div class="season-card stagger-in">
                    <div class="text-4xl mb-4">🍂</div>
                    <h4 class="text-xl font-bold mb-3 taste-retreat-text-primary">秋 - 6節気</h4>
                    <p class="text-gray-700 text-sm leading-relaxed mb-4">
                        立秋、処暑、白露、秋分、寒露、霜降
                    </p>
                    <div class="space-y-2 text-xs text-gray-600">
                        <div class="p-2 bg-orange-50 rounded-lg">豊穣の恵みを堪能する</div>
                        <div class="p-2 bg-orange-50 rounded-lg">紅葉と共に深まる味覚</div>
                    </div>
                </div>

                <!-- 冬 -->
                <div class="season-card stagger-in">
                    <div class="text-4xl mb-4">❄️</div>
                    <h4 class="text-xl font-bold mb-3 taste-retreat-text-primary">冬 - 6節気</h4>
                    <p class="text-gray-700 text-sm leading-relaxed mb-4">
                        立冬、小雪、大雪、冬至、小寒、大寒
                    </p>
                    <div class="space-y-2 text-xs text-gray-600">
                        <div class="p-2 bg-blue-50 rounded-lg">心身を温める滋味深い料理</div>
                        <div class="p-2 bg-blue-50 rounded-lg">雪景色の中の特別なひととき</div>
                    </div>
                </div>
            </div>

            <div class="text-center element-spacer">
                <div class="inline-block p-8 bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-blue-100">
                    <h4 class="text-2xl font-bold taste-retreat-text-primary mb-4">
                        なぜ24という数字？
                    </h4>
                    <p class="text-gray-700 leading-relaxed max-w-2xl mx-auto">
                        古くから日本人は、自然のリズムに寄り添って生きてきました。<br>
                        24節気という智慧に学び、人生において本当に大切にしたいお店を<br>
                        <span class="font-semibold text-green-600">季節と共に、丁寧に選び抜く</span>。<br>
                        そんな美しい暮らし方をTasteRetreatは提案します。
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="section-spacer taste-retreat-secondary">
        <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-10">
            <div class="text-center content-spacer">
                <h3 class="text-4xl md:text-5xl font-bold text-gray-900 mb-8">これからの機能</h3>
                <p class="text-xl text-gray-700 max-w-3xl mx-auto">TasteRetreatがお届けする、新しいグルメ体験</p>
            </div>

            <div class="grid md:grid-cols-3 gap-10 element-spacer">
                <div class="text-center p-8 bg-white/60 backdrop-blur-sm rounded-2xl shadow-lg stagger-in">
                    <div class="w-20 h-20 taste-retreat-secondary rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-3xl">🔍</span>
                    </div>
                    <h4 class="text-2xl font-semibold mb-4 taste-retreat-text-primary">季節フィルタリング</h4>
                    <p class="text-gray-700 leading-relaxed">24節気に基づく季節感を大切にした検索とフィルタリング機能</p>
                </div>

                <div class="text-center p-8 bg-white/60 backdrop-blur-sm rounded-2xl shadow-lg stagger-in">
                    <div class="w-20 h-20 taste-retreat-secondary rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-3xl">🌸</span>
                    </div>
                    <h4 class="text-2xl font-semibold mb-4 taste-retreat-text-primary">季節のコミュニティ</h4>
                    <p class="text-gray-700 leading-relaxed">同じ季節感を大切にする人との出会いと情報交換</p>
                </div>

                <div class="text-center p-8 bg-white/60 backdrop-blur-sm rounded-2xl shadow-lg stagger-in">
                    <div class="w-20 h-20 taste-retreat-secondary rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-3xl">🍃</span>
                    </div>
                    <h4 class="text-2xl font-semibold mb-4 taste-retreat-text-primary">季節レコメンド</h4>
                    <p class="text-gray-700 leading-relaxed">今の季節にぴったりな、あなただけのおすすめ店舗をご提案</p>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-16 lg:py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h3 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">日本の美的感性を取り入れた<br>グルメ情報コミュニティ</h3>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto leading-relaxed">
                    TasteRetreatは、広告に依存しない信頼できるプラットフォームとして、ユーザーが人生で大切なお店を最大24店舗まで厳選して整理・共有できるサービスです。日本の伝統的な美意識である「引き算の美学」を取り入れ、本当に価値のある情報だけを大切にします。
                </p>
            </div>

            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div>
                    <h4 class="text-2xl font-bold mb-6 taste-retreat-text-primary">なぜ24店舗なのか？</h4>
                    <div class="space-y-4 text-gray-600">
                        <p>人生において本当に大切にしたいお店は、実はそれほど多くありません。24という数字は、四季（4）×六感（6）から導かれた、日本的な美意識に基づく数です。</p>
                        <p>限られた数だからこそ、一つ一つの店舗選びに想いが込められ、質の高い情報が生まれます。</p>
                        <p>あなたにとって本当に価値のあるお店だけを、丁寧に選び抜いてください。</p>
                    </div>
                </div>
                <div class="taste-retreat-cream p-8 rounded-2xl">
                    <h5 class="text-lg font-semibold mb-4 taste-retreat-text-primary">現在のあなたの名店リスト</h5>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center p-3 bg-white rounded-lg">
                            <span class="font-medium">銀座 久兵衛</span>
                            <span class="text-sm text-gray-500">寿司</span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-white rounded-lg">
                            <span class="font-medium">代官山 蔦</span>
                            <span class="text-sm text-gray-500">フランス料理</span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-white rounded-lg">
                            <span class="font-medium">恵比寿 ジョエル・ロブション</span>
                            <span class="text-sm text-gray-500">フランス料理</span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-white/50 rounded-lg border-2 border-dashed border-gray-300">
                            <span class="text-gray-400">+ 新しいお店を追加</span>
                        </div>
                        <div class="text-right">
                            <span class="text-sm font-medium taste-retreat-text-primary">12/24店舗</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="section-spacer bg-gradient-to-br from-green-500 via-blue-600 to-purple-600 relative overflow-hidden">
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="relative max-w-5xl mx-auto text-center px-6 sm:px-8 lg:px-10">
            <div class="content-spacer">
                <h3 class="text-4xl md:text-6xl font-bold text-white mb-8 leading-tight">
                    24節気と共に歩む<br>
                    <span class="text-yellow-300">美食の旅</span>をはじめませんか？
                </h3>
                <p class="text-xl md:text-2xl text-white/90 mb-12 max-w-3xl mx-auto leading-relaxed">
                    季節の移ろいと共に、あなただけの名店リストを丁寧に育てていく。<br>
                    そんな豊かな暮らしが、今日から始まります。
                </p>
                <div class="flex flex-col sm:flex-row gap-6 justify-center">
                    <a href="{{ route('login') }}" class="primary-cta text-white px-12 py-5 rounded-full text-2xl font-bold transition-all inline-block text-shadow">
                        今すぐログイン
                    </a>
                    <a href="#demo" class="border-3 border-white text-white px-12 py-5 rounded-full text-xl font-semibold hover:bg-white hover:text-blue-600 transition-all inline-block">
                        もう一度体験する
                    </a>
                </div>
                <div class="mt-8 text-white/80 text-sm">
                    ✨ 無料でアカウント作成 | 🌸 24節気の季節感を大切に | 🍃 広告なしの信頼できる情報
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h4 class="text-2xl font-bold taste-retreat-text-primary mb-4">TasteRetreat</h4>
                <p class="text-gray-400 mb-6">本当に価値のあるお店を、あなたの手で</p>
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


    <!-- Demo JavaScript -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('demoExperience', () => ({
                searchQuery: '',
                searchResults: [],
                selectedShop: null,
                showResults: false,
                myShopList: [
                    { id: 1, name: '銀座 久兵衛', address: '東京都中央区銀座8-7-6', lat: 35.6716, lng: 139.7669, isNew: false, season: '🌸', seasonName: '清明 - 桜舞い散る頃の江戸前鮨' },
                    { id: 2, name: '代官山 蔦', address: '東京都渋谷区猿楽町9-7', lat: 35.6499, lng: 139.6985, isNew: false, season: '🍂', seasonName: '霜降 - 紅葉とフランス料理の調和' },
                    { id: 3, name: 'ジョエル・ロブション', address: '東京都目黒区三田1-13-1', lat: 35.6328, lng: 139.7153, isNew: false, season: '❄️', seasonName: '大寒 - 冬の宵の特別な時間' }
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
                            center: { lat: 35.6762, lng: 139.6503 }, // Tokyo center
                            zoom: 12,
                            styles: [
                                {
                                    featureType: 'poi.business',
                                    stylers: [{ visibility: 'off' }]
                                }
                            ]
                        });
                        this.addExistingMarkers();
                    } else {
                        // Fallback: Show static map placeholder
                        document.getElementById('demo-map').innerHTML = `
                            <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                <div class="text-center text-gray-500">
                                    <div class="text-4xl mb-2">🗺️</div>
                                    <div>地図サービスの読み込みが完了していません</div>
                                    <div class="text-sm mt-2">実際のサービスでは高機能な地図が利用できます</div>
                                </div>
                            </div>
                        `;
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
                                fillColor: '#3B82F6',
                                fillOpacity: 1,
                                strokeColor: '#1E40AF',
                                strokeWeight: 2
                            }
                        });

                        const infoWindow = new google.maps.InfoWindow({
                            content: `
                                <div class="p-2">
                                    <h3 class="font-semibold text-gray-900">${shop.name}</h3>
                                    <p class="text-sm text-gray-600">${shop.address}</p>
                                </div>
                            `
                        });

                        marker.addListener('click', () => {
                            this.markers.forEach(m => m.infoWindow.close());
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
                                    'X-Requested-With': 'XMLHttpRequest'
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
                                // Fallback to mock data if API fails
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

                    // ランダムな季節情報を追加
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
                                fillColor: '#EF4444',
                                fillOpacity: 1,
                                strokeColor: '#DC2626',
                                strokeWeight: 2
                            },
                            animation: google.maps.Animation.BOUNCE
                        });

                        setTimeout(() => {
                            marker.setAnimation(null);
                        }, 1400);

                        const infoWindow = new google.maps.InfoWindow({
                            content: `
                                <div class="p-2">
                                    <h3 class="font-semibold text-gray-900">${newShop.name}</h3>
                                    <p class="text-sm text-gray-600">${newShop.address}</p>
                                    <span class="inline-block bg-yellow-400 text-yellow-800 text-xs px-2 py-1 rounded-full font-semibold mt-1">NEW</span>
                                </div>
                            `
                        });

                        marker.addListener('click', () => {
                            this.markers.forEach(m => m.infoWindow.close());
                            infoWindow.open(this.map, marker);
                        });

                        this.markers.push({ marker, infoWindow, shop: newShop });
                        
                        // Pan to new marker
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
                    
                    // Find and open the info window for this shop
                    const markerData = this.markers.find(m => m.shop.id === shop.id);
                    if (markerData) {
                        this.markers.forEach(m => m.infoWindow.close());
                        markerData.infoWindow.open(this.map, markerData.marker);
                    }
                }
            }));
        });

        // Initialize map when Google Maps API is loaded
        function initMap() {
            // This function is called by the Google Maps API
            // Alpine.js will handle the actual initialization
        }
    </script>

</body>
</html>