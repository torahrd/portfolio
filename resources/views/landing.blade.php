<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TasteRetreat - 季節と共に巡る、あなただけの名店リスト</title>
    <meta name="description" content="24節気に合わせて、本当に大切な24店舗だけを登録。広告に依存しない、ユーザー主導のグルメ情報コミュニティ。">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;600;700&family=Noto+Serif+JP:wght@300;400;500&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/css/landing.css'])
</head>
<body class="font-sans antialiased">
    <!-- ヘッダー -->
    <header class="fixed top-0 left-0 right-0 z-50 bg-white/90 backdrop-blur-sm border-b border-neutral-200/50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 bg-gradient-to-br from-amber-500 to-orange-600 rounded-lg flex items-center justify-center">
                        <span class="text-white font-bold text-lg">味</span>
                    </div>
                    <span class="text-xl font-bold text-neutral-900">TasteRetreat</span>
                </div>
                
                <nav class="hidden md:flex items-center space-x-8">
                    <a href="#features" class="text-neutral-600 hover:text-amber-600 transition-colors">機能</a>
                    <a href="#experience" class="text-neutral-600 hover:text-amber-600 transition-colors">体験</a>
                    <a href="#about" class="text-neutral-600 hover:text-amber-600 transition-colors">について</a>
                </nav>
                
                <div class="flex items-center space-x-3">
                    <a href="{{ route('login') }}" class="text-neutral-600 hover:text-neutral-900 transition-colors">ログイン</a>
                    <a href="{{ route('register') }}" class="bg-amber-500 text-white px-4 py-2 rounded-lg hover:bg-amber-600 transition-colors">始める</a>
                </div>
            </div>
        </div>
    </header>

    <!-- メインコンテンツ -->
    <main>
        <!-- ヒーローセクション -->
        <section class="pt-24 pb-16 bg-gradient-to-b from-amber-50/30 to-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h1 class="text-4xl md:text-6xl font-light text-neutral-900 mb-6 leading-tight">
                        季節と共に巡る、<br>
                        <span class="font-serif text-amber-700">あなただけの名店リスト</span>
                    </h1>
                    <p class="text-xl text-neutral-600 mb-8 max-w-3xl mx-auto leading-relaxed">
                        24節気に合わせて、本当に大切な24店舗だけを登録。<br>
                        広告に依存しない、ユーザー主導のグルメ情報コミュニティ。
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="#experience" class="bg-amber-500 text-white px-8 py-3 rounded-lg hover:bg-amber-600 transition-all duration-300 transform hover:scale-105">
                            体験してみる
                        </a>
                        <a href="{{ route('register') }}" class="border border-amber-500 text-amber-600 px-8 py-3 rounded-lg hover:bg-amber-50 transition-colors">
                            無料で始める
                        </a>
                    </div>
                </div>
                
                <!-- 24節気円グラフのプレビュー -->
                <div class="mt-16 flex justify-center">
                    <div class="relative w-80 h-80 md:w-96 md:h-96">
                        <div class="seasonal-circle" id="seasonal-preview">
                            <svg viewBox="0 0 400 400" class="w-full h-full transform -rotate-90">
                                <!-- 背景円 -->
                                <circle cx="200" cy="200" r="180" fill="none" stroke="#f3f4f6" stroke-width="2"/>
                                
                                <!-- 24節気のセグメント -->
                                <g id="seasonal-segments">
                                    <!-- JavaScriptで動的生成 -->
                                </g>
                                
                                <!-- 現在の節気を示すマーカー -->
                                <circle cx="200" cy="20" r="6" fill="#f59e0b" class="animate-pulse"/>
                                <text x="200" y="15" text-anchor="middle" class="text-xs fill-amber-600 font-medium">今</text>
                            </svg>
                            
                            <!-- 中央のロゴ -->
                            <div class="absolute inset-0 flex items-center justify-center">
                                <div class="w-16 h-16 bg-gradient-to-br from-amber-500 to-orange-600 rounded-full flex items-center justify-center shadow-lg">
                                    <span class="text-white font-bold text-2xl">味</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 問題提起セクション -->
        <section class="py-16 bg-neutral-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-light text-neutral-900 mb-6">
                        外食するとき、あなたは<br>
                        <span class="text-amber-700 font-serif">こんな悩みを抱えていませんか？</span>
                    </h2>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="text-center p-6 bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow">
                        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-neutral-900 mb-3">良いお店が見つからない</h3>
                        <p class="text-neutral-600 text-sm leading-relaxed">
                            検索しても似たような店ばかり。<br>
                            本当に美味しいお店に出会えない。
                        </p>
                    </div>
                    
                    <div class="text-center p-6 bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow">
                        <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-neutral-900 mb-3">レビューが信用できない</h3>
                        <p class="text-neutral-600 text-sm leading-relaxed">
                            広告やステマが混在。<br>
                            本当の評価が分からない。
                        </p>
                    </div>
                    
                    <div class="text-center p-6 bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2h0"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-neutral-900 mb-3">SNS情報が整理できない</h3>
                        <p class="text-neutral-600 text-sm leading-relaxed">
                            情報が散らばって管理が大変。<br>
                            お気に入りを見つけても忘れてしまう。
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- 解決策セクション -->
        <section class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-light text-neutral-900 mb-6">
                        <span class="text-amber-700 font-serif">TasteRetreat</span>が<br>
                        これらの悩みを解決します
                    </h2>
                </div>
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                    <div class="text-center">
                        <div class="w-20 h-20 bg-gradient-to-br from-green-400 to-emerald-500 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A2 2 0 013 15.382V6.618a2 2 0 011.553-1.946l5.894-1.684a2 2 0 011.106 0l5.894 1.684A2 2 0 0121 6.618v8.764a2 2 0 01-1.553 1.946L15 20m-6 0V4m6 16V4"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-medium text-neutral-900 mb-4">地図上での整理</h3>
                        <p class="text-neutral-600 leading-relaxed">
                            お気に入りの店舗を地図上で視覚的に管理。<br>
                            エリアごとの名店が一目で分かります。
                        </p>
                    </div>
                    
                    <div class="text-center">
                        <div class="w-20 h-20 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-medium text-neutral-900 mb-4">投稿・共有</h3>
                        <p class="text-neutral-600 leading-relaxed">
                            写真と感想を投稿して、<br>
                            信頼できる仲間と情報を共有。
                        </p>
                    </div>
                    
                    <div class="text-center">
                        <div class="w-20 h-20 bg-gradient-to-br from-purple-400 to-pink-500 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-medium text-neutral-900 mb-4">正直なレビュー</h3>
                        <p class="text-neutral-600 leading-relaxed">
                            広告なし、ステマなし。<br>
                            本当の体験だけを共有。
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- 価値提案セクション（24節気名店リスト） -->
        <section class="py-20 bg-gradient-to-b from-amber-50/50 to-orange-50/30">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-5xl font-light text-neutral-900 mb-6">
                        <span class="text-amber-700 font-serif">24節気</span>名店リスト
                    </h2>
                    <p class="text-xl text-neutral-600 max-w-4xl mx-auto leading-relaxed">
                        一年を24の季節に分けた「24節気」に合わせて、<br>
                        本当に大切な24店舗だけを登録できる特別な機能です。
                    </p>
                </div>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                    <!-- 24節気円グラフ（インタラクティブ） -->
                    <div class="flex justify-center">
                        <div class="relative w-96 h-96">
                            <div class="seasonal-circle-main" id="main-seasonal-circle">
                                <svg viewBox="0 0 400 400" class="w-full h-full">
                                    <!-- 外側の装飾円 -->
                                    <circle cx="200" cy="200" r="190" fill="none" stroke="#fbbf24" stroke-width="1" opacity="0.3"/>
                                    <circle cx="200" cy="200" r="170" fill="none" stroke="#f59e0b" stroke-width="2"/>
                                    
                                    <!-- 24節気のセグメント -->
                                    <g id="main-seasonal-segments">
                                        <!-- JavaScriptで動的生成 -->
                                    </g>
                                    
                                    <!-- 季節の境界線 -->
                                    <g stroke="#d97706" stroke-width="3" opacity="0.6">
                                        <line x1="200" y1="30" x2="200" y2="50"/>  <!-- 春分 -->
                                        <line x1="370" y1="200" x2="200" y2="200"/> <!-- 夏至 -->
                                        <line x1="200" y1="370" x2="200" y2="350"/> <!-- 秋分 -->
                                        <line x1="30" y1="200" x2="50" y2="200"/>  <!-- 冬至 -->
                                    </g>
                                    
                                    <!-- 現在の節気マーカー -->
                                    <g id="current-season-marker">
                                        <circle cx="200" cy="30" r="8" fill="#f59e0b" class="animate-pulse"/>
                                        <text x="200" y="20" text-anchor="middle" class="text-sm fill-amber-700 font-medium">今</text>
                                    </g>
                                </svg>
                                
                                <!-- 中央のロゴ -->
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <div class="w-20 h-20 bg-gradient-to-br from-amber-500 to-orange-600 rounded-full flex items-center justify-center shadow-xl">
                                        <span class="text-white font-bold text-3xl">味</span>
                                    </div>
                                </div>
                                
                                <!-- 回転コントロール -->
                                <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2">
                                    <div class="bg-white/90 backdrop-blur-sm rounded-full px-4 py-2 shadow-lg">
                                        <p class="text-xs text-neutral-600">ドラッグして回転</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- 説明テキスト -->
                    <div class="space-y-6">
                        <div class="bg-white p-6 rounded-xl shadow-sm">
                            <h3 class="text-xl font-medium text-neutral-900 mb-3 flex items-center">
                                <span class="w-6 h-6 bg-amber-500 rounded-full flex items-center justify-center text-white text-sm font-bold mr-3">1</span>
                                24店舗の厳選
                            </h3>
                            <p class="text-neutral-600 leading-relaxed">
                                無制限ではなく、24店舗という制限があることで、本当に大切なお店だけを選ぶことができます。
                            </p>
                        </div>
                        
                        <div class="bg-white p-6 rounded-xl shadow-sm">
                            <h3 class="text-xl font-medium text-neutral-900 mb-3 flex items-center">
                                <span class="w-6 h-6 bg-amber-500 rounded-full flex items-center justify-center text-white text-sm font-bold mr-3">2</span>
                                季節との調和
                            </h3>
                            <p class="text-neutral-600 leading-relaxed">
                                24節気に合わせて店舗を配置。春の桜を見ながらのカフェ、夏祭りの屋台、秋の紅葉と共に味わう懐石料理。
                            </p>
                        </div>
                        
                        <div class="bg-white p-6 rounded-xl shadow-sm">
                            <h3 class="text-xl font-medium text-neutral-900 mb-3 flex items-center">
                                <span class="w-6 h-6 bg-amber-500 rounded-full flex items-center justify-center text-white text-sm font-bold mr-3">3</span>
                                思い出との結びつき
                            </h3>
                            <p class="text-neutral-600 leading-relaxed">
                                お店を訪れた季節、その時の気持ち、一緒にいた人。料理と共に大切な思い出も保存できます。
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 体験型コンテンツセクション -->
        <section id="experience" class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-light text-neutral-900 mb-6">
                        <span class="text-amber-700 font-serif">体験</span>してみましょう
                    </h2>
                    <p class="text-xl text-neutral-600 max-w-3xl mx-auto">
                        実際にお店を検索して、名店リストに追加する体験ができます
                    </p>
                </div>
                
                <!-- 体験ステップ -->
                <div class="max-w-4xl mx-auto">
                    <!-- ステップ1: お店を検索 -->
                    <div class="mb-12 p-8 bg-gradient-to-r from-amber-50 to-orange-50 rounded-xl">
                        <h3 class="text-2xl font-medium text-neutral-900 mb-6 text-center">
                            ステップ1: お店を検索してみる
                        </h3>
                        
                        <div class="max-w-md mx-auto">
                            <div class="relative" id="demo-search">
                                <input
                                    type="text"
                                    placeholder="例：渋谷 ラーメン"
                                    class="w-full px-4 py-3 pl-12 border border-neutral-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                                    id="demo-search-input">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                                
                                <!-- 検索結果 -->
                                <div id="demo-search-results" class="hidden absolute left-0 right-0 mt-2 bg-white rounded-lg shadow-lg border border-neutral-200 py-2 z-40 max-h-80 overflow-y-auto">
                                    <!-- JavaScriptで動的生成 -->
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- ステップ2: 地図で確認 -->
                    <div class="mb-12 p-8 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl">
                        <h3 class="text-2xl font-medium text-neutral-900 mb-6 text-center">
                            ステップ2: 地図で位置を確認
                        </h3>
                        
                        <div class="bg-white rounded-lg p-4 shadow-inner">
                            <div id="demo-map" class="w-full h-64 bg-neutral-100 rounded-lg flex items-center justify-center">
                                <div class="text-center text-neutral-500">
                                    <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A2 2 0 013 15.382V6.618a2 2 0 011.553-1.946l5.894-1.684a2 2 0 011.106 0l5.894 1.684A2 2 0 0121 6.618v8.764a2 2 0 01-1.553 1.946L15 20m-6 0V4m6 16V4"/>
                                    </svg>
                                    <p class="text-sm">お店を検索すると地図が表示されます</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- ステップ3: 名店リストに追加 -->
                    <div class="p-8 bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl">
                        <h3 class="text-2xl font-medium text-neutral-900 mb-6 text-center">
                            ステップ3: 24節気名店リストに追加
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                            <!-- 小さな24節気円グラフ -->
                            <div class="flex justify-center">
                                <div class="relative w-64 h-64">
                                    <svg viewBox="0 0 400 400" class="w-full h-full transform -rotate-90">
                                        <circle cx="200" cy="200" r="150" fill="none" stroke="#f3f4f6" stroke-width="2"/>
                                        <g id="demo-seasonal-segments">
                                            <!-- JavaScriptで動的生成 -->
                                        </g>
                                        <!-- 追加予定の位置を示すマーカー -->
                                        <circle cx="200" cy="50" r="8" fill="#f59e0b" class="animate-pulse" id="add-position"/>
                                    </svg>
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-orange-600 rounded-full flex items-center justify-center">
                                            <span class="text-white font-bold text-lg">味</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- 追加ボタンとメッセージ -->
                            <div class="text-center md:text-left">
                                <div class="mb-6">
                                    <p class="text-lg text-neutral-700 mb-4">
                                        選択したお店を<br>
                                        <span class="text-amber-700 font-serif">「立春」</span>の位置に追加しますか？
                                    </p>
                                    <p class="text-sm text-neutral-500">
                                        ※ 実際のサービスでは、お好きな節気を選択できます
                                    </p>
                                </div>
                                
                                <button
                                    id="add-to-list-btn"
                                    class="bg-amber-500 text-white px-6 py-3 rounded-lg hover:bg-amber-600 transition-all duration-300 transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed"
                                    disabled>
                                    名店リストに追加
                                </button>
                                
                                <div id="add-success" class="hidden mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                                    <div class="flex items-center text-green-700">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        名店リストに追加されました！
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 登録誘導セクション -->
        <section class="py-20 bg-gradient-to-br from-amber-500 to-orange-600 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-3xl md:text-4xl font-light mb-6">
                    あなたの<span class="font-serif">名店リスト</span>を<br>
                    今すぐ作り始めませんか？
                </h2>
                <p class="text-xl text-amber-100 mb-8 max-w-3xl mx-auto leading-relaxed">
                    無料でアカウントを作成して、<br>
                    季節と共に巡る特別なグルメ体験を始めましょう。
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('register') }}" class="bg-white text-amber-600 px-8 py-4 rounded-lg hover:bg-amber-50 transition-all duration-300 transform hover:scale-105 font-medium text-lg">
                        無料でアカウント作成
                    </a>
                    <a href="{{ route('login') }}" class="border-2 border-white text-white px-8 py-4 rounded-lg hover:bg-white/10 transition-colors font-medium text-lg">
                        既にアカウントをお持ちの方
                    </a>
                </div>
                
                <div class="mt-8 text-amber-100 text-sm">
                    <p>✓ 完全無料　✓ 広告なし　✓ いつでも退会可能</p>
                </div>
            </div>
        </section>

        <!-- 将来展望セクション -->
        <section class="py-16 bg-neutral-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-light text-neutral-900 mb-6">
                        これからの<span class="text-amber-700 font-serif">TasteRetreat</span>
                    </h2>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="text-center p-6 bg-white rounded-xl shadow-sm">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <h3 class="font-medium text-neutral-900 mb-2">コミュニティ機能</h3>
                        <p class="text-sm text-neutral-600">グルメ仲間との交流</p>
                    </div>
                    
                    <div class="text-center p-6 bg-white rounded-xl shadow-sm">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <h3 class="font-medium text-neutral-900 mb-2">データ分析</h3>
                        <p class="text-sm text-neutral-600">食の傾向を可視化</p>
                    </div>
                    
                    <div class="text-center p-6 bg-white rounded-xl shadow-sm">
                        <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h3 class="font-medium text-neutral-900 mb-2">モバイルアプリ</h3>
                        <p class="text-sm text-neutral-600">外出先での利用最適化</p>
                    </div>
                    
                    <div class="text-center p-6 bg-white rounded-xl shadow-sm">
                        <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"/>
                            </svg>
                        </div>
                        <h3 class="font-medium text-neutral-900 mb-2">AI推薦</h3>
                        <p class="text-sm text-neutral-600">好みに合わせた提案</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- フッター -->
    <footer class="bg-neutral-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="w-8 h-8 bg-gradient-to-br from-amber-500 to-orange-600 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-lg">味</span>
                        </div>
                        <span class="text-xl font-bold">TasteRetreat</span>
                    </div>
                    <p class="text-neutral-400 text-sm leading-relaxed">
                        季節と共に巡る、あなただけの名店リスト。<br>
                        美味しい体験を大切な思い出と共に。
                    </p>
                </div>
                
                <div>
                    <h3 class="font-medium mb-4">サービス</h3>
                    <ul class="space-y-2 text-sm text-neutral-400">
                        <li><a href="#" class="hover:text-white transition-colors">24節気名店リスト</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">地図機能</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">投稿・共有</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">コミュニティ</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="font-medium mb-4">サポート</h3>
                    <ul class="space-y-2 text-sm text-neutral-400">
                        <li><a href="{{ route('privacy-policy') }}" class="hover:text-white transition-colors">プライバシーポリシー</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">利用規約</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">お問い合わせ</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">ヘルプ</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-neutral-800 mt-8 pt-8 text-center text-sm text-neutral-400">
                <p>&copy; 2025 TasteRetreat. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    @vite(['resources/js/landing.js'])
    
    <!-- Google Maps API (体験用) -->
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('google.maps.api_key') }}&libraries=places&callback=initDemoMap" async defer></script>
</body>
</html>