<!DOCTYPE html>
<html lang="ja">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="TasteRetreatは、あなたの食体験を磨く小さな額縁です。">
    <title>TasteRetreat - 行きたい、また行きたい——ただそれだけを、そっと記す。</title>

    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Zen+Kaku+Gothic+New:wght@400;500;600;700&display=swap" rel="stylesheet">

    @if(config('analytics.enabled'))
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('analytics.measurement_id') }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        
        // デフォルトの同意状態（Cookie同意前）
        gtag('consent', 'default', {
            'analytics_storage': 'denied',
            'ad_storage': 'denied',
            'wait_for_update': 500
        });
        
        gtag('js', new Date());
        
        gtag('config', '{{ config('analytics.measurement_id') }}', {
            'anonymize_ip': {{ config('analytics.tracking.anonymize_ip') ? 'true' : 'false' }},
            'debug_mode': {{ config('analytics.debug_mode') ? 'true' : 'false' }}
        });
    </script>
    @endif
    
    @vite(['resources/css/app.css'])
    
    <style>
        /* 共通変数 */
        :root {
            --primary-color: #4A9B8E;
            --primary-hover: #3A8B7E;
            --text-primary: #333333;
            --text-secondary: #666666;
            --border-color: #E0E0E0;
            --bg-light: #FAFAFA;
        }
        
        /* フォント */
        body {
            font-family: 'Zen Kaku Gothic New', "游ゴシック体", "Yu Gothic", YuGothic, "ヒラギノ角ゴ Pro", "Hiragino Kaku Gothic Pro", "メイリオ", Meiryo, sans-serif;
            line-height: 1.7;
            color: var(--text-primary);
            margin: 0;
            padding: 0;
        }
        
        /* セクション間の余白 */
        section {
            padding: 80px 0;
        }
        
        /* コンテナ */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        /* ボタン共通スタイル */
        .btn {
            display: inline-block;
            padding: 16px 32px;
            border-radius: 30px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
            font-size: 16px;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(74, 155, 142, 0.3);
        }
        
        .btn-outline {
            background-color: white;
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
        }
        
        .btn-outline:hover {
            background-color: var(--primary-color);
            color: white;
            transform: translateY(-2px);
        }
        
        /* ヒーローセクション */
        .hero {
            min-height: 100vh;
            min-height: 600px;
            background-color: var(--bg-light);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        
        .hero-content {
            text-align: center;
            padding: 40px 20px;
        }
        
        .hero-title {
            font-size: 48px;
            font-weight: bold;
            line-height: 1.6;
            margin-bottom: 24px;
            color: var(--text-primary);
        }
        
        .hero-subtitle {
            font-size: 18px;
            color: var(--text-secondary);
            margin-bottom: 48px;
        }
        
        .hero-buttons {
            display: flex;
            gap: 16px;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        /* カード */
        .card {
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 40px 24px;
            min-height: 280px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.12);
        }
        
        .card-icon {
            font-size: 48px;
            margin-bottom: 20px;
        }
        
        .card-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 16px;
            color: var(--text-primary);
        }
        
        .card-description {
            font-size: 16px;
            color: var(--text-secondary);
            line-height: 1.6;
        }
        
        /* 24節気円形チャート */
        .season-wheel {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 60px 0;
        }
        
        .season-wheel svg {
            filter: drop-shadow(0 4px 12px rgba(0, 0, 0, 0.1));
        }
        
        .season-segment {
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .season-segment:hover {
            filter: brightness(0.9);
            transform-origin: center;
        }
        
        .season-segment:hover path {
            transform: scale(1.05);
            transform-origin: center;
        }
        
        /* セクションタイトル */
        .section-title {
            text-align: center;
            font-size: 36px;
            font-weight: bold;
            margin-bottom: 24px;
            color: var(--text-primary);
        }
        
        .section-subtitle {
            text-align: center;
            font-size: 18px;
            color: var(--text-secondary);
            margin-bottom: 60px;
            line-height: 1.8;
        }
        
        /* グリッドレイアウト */
        .grid {
            display: grid;
            gap: 32px;
        }
        
        .grid-cols-3 {
            grid-template-columns: repeat(3, 1fr);
        }
        
        /* モバイル対応 */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 28px;
            }
            
            .hero-subtitle {
                font-size: 14px;
            }
            
            .hero-buttons {
                flex-direction: column;
                width: 100%;
                padding: 0 20px;
            }
            
            .btn {
                width: 100%;
                text-align: center;
            }
            
            .grid-cols-3 {
                grid-template-columns: 1fr;
            }
            
            .season-wheel svg {
                width: 300px;
                height: 300px;
            }
            
            section {
                padding: 60px 0;
            }
        }
        
        /* ヘッダー */
        header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border-color);
            z-index: 1000;
            padding: 16px 0;
        }
        
        .header-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: var(--primary-color);
            text-decoration: none;
        }
        
        nav {
            display: flex;
            gap: 32px;
            align-items: center;
        }
        
        nav a {
            color: var(--text-primary);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        
        nav a:hover {
            color: var(--primary-color);
        }
        
        /* モバイルメニュー */
        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            cursor: pointer;
            padding: 8px;
        }
        
        @media (max-width: 768px) {
            nav {
                display: none;
            }
            
            .mobile-menu-btn {
                display: block;
            }
        }
    </style>
</head>

<body>
    <!-- ヘッダー -->
    <header>
        <div class="header-container">
            <a href="/" class="logo">TasteRetreat</a>
            <nav>
                <a href="#how">使い方</a>
                <a href="#seasons">24の季節</a>
                <a href="{{ route('login') }}" class="btn btn-primary">ログイン</a>
            </nav>
            <button class="mobile-menu-btn" aria-label="メニュー">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="3" y1="6" x2="21" y2="6"/>
                    <line x1="3" y1="12" x2="21" y2="12"/>
                    <line x1="3" y1="18" x2="21" y2="18"/>
                </svg>
            </button>
        </div>
    </header>
    
    <!-- 1. ヒーローセクション -->
    <section class="hero">
        <div class="hero-content">
            <h1 class="hero-title">
                行きたい、また行きたい——<br>
                2つの「行きたい」を、一つに記す。
            </h1>
            <p class="hero-subtitle">
                TasteRetreatは、あなたの食体験を導く小さな羅針盤です。
            </p>
            <div class="hero-buttons">
                <a href="{{ route('login') }}" class="btn btn-primary">ログインして始める</a>
                <a href="#how" class="btn btn-outline">概要を見る</a>

            </div>

        </div>
    </section>
    
    <!-- 2. 使い方セクション -->
    <section id="how" style="background: white;">
        <div class="container">
            <h2 class="section-title">使い方</h2>
            <div class="grid grid-cols-3">
                <!-- カード1: 記す -->
                <div class="card">
                    <div class="card-icon">📍</div>
                    <h3 class="card-title">記す</h3>
                    <p class="card-description">
                        行きたいお店・行ったお店を見覚えに記しましょう。
                    </p>
                </div>
                
                <!-- カード2: 見返す -->
                <div class="card">
                    <div class="card-icon">📱</div>
                    <h3 class="card-title">見返す</h3>
                    <p class="card-description">
                        訪れたお店を振り返り、メモを追記しましょう。
                    </p>
                    <div style="margin-top: 20px; padding: 12px; background: var(--bg-light); border-radius: 8px; font-size: 14px;">
                        例: "Local Cafe"
                    </div>
                </div>
                
                <!-- カード3: 選び直す -->
                <div class="card">
                    <div class="card-icon">📚</div>
                    <h3 class="card-title">選び直す</h3>
                    <p class="card-description">
                        お気に入りの店を見つけ、何度も食体験を楽しみます。
                    </p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- 3. 24の季節セクション -->
    <section id="seasons" style="background: var(--bg-light);">
        <div class="container">
            <h2 class="section-title">24の季節 × あなただけの名店</h2>
            <p class="section-subtitle">
                忘れられない体験だけを、24の季節に重ねて記す。<br>
                名店の変遷で、あなたの人生を記す。
            </p>
            
            <div class="season-wheel">
                <svg width="400" height="400" viewBox="0 0 400 400" xmlns="http://www.w3.org/2000/svg">
                    @php
                        $seasons = [
                            '立春', '雨水', '啓蟄', '春分', '清明', '穀雨',
                            '立夏', '小満', '芒種', '夏至', '小暑', '大暑',
                            '立秋', '処暑', '白露', '秋分', '寒露', '霜降',
                            '立冬', '小雪', '大雪', '冬至', '小寒', '大寒'
                        ];
                        
                        $colors = [
                            '#E8F5E9', '#C8E6C9', '#A5D6A7', '#81C784', '#66BB6A', '#4CAF50',
                            '#43A047', '#388E3C', '#2E7D32', '#4A9B8E', '#45897D', '#40786C',
                            '#3B675B', '#36564A', '#314539', '#66BB6A', '#4CAF50', '#43A047',
                            '#388E3C', '#2E7D32', '#4A9B8E', '#45897D', '#40786C', '#3B675B'
                        ];
                        
                        $centerX = 200;
                        $centerY = 200;
                        $outerRadius = 180;
                        $innerRadius = 80;
                        $angleStep = 360 / 24;
                    @endphp
                    
                    <!-- 外側の円 -->
                    <circle cx="200" cy="200" r="190" fill="white" stroke="#E0E0E0" stroke-width="1"/>
                    
                    <!-- 24節気のセグメント -->
                    @foreach($seasons as $index => $season)
                        @php
                            $startAngle = -90 + ($index * $angleStep);
                            $endAngle = $startAngle + $angleStep;
                            
                            $startAngleRad = deg2rad($startAngle);
                            $endAngleRad = deg2rad($endAngle);
                            
                            $x1 = $centerX + $innerRadius * cos($startAngleRad);
                            $y1 = $centerY + $innerRadius * sin($startAngleRad);
                            $x2 = $centerX + $outerRadius * cos($startAngleRad);
                            $y2 = $centerY + $outerRadius * sin($startAngleRad);
                            $x3 = $centerX + $outerRadius * cos($endAngleRad);
                            $y3 = $centerY + $outerRadius * sin($endAngleRad);
                            $x4 = $centerX + $innerRadius * cos($endAngleRad);
                            $y4 = $centerY + $innerRadius * sin($endAngleRad);
                            
                            $largeArcFlag = 0;
                            
                            $textAngle = $startAngle + $angleStep / 2;
                            $textAngleRad = deg2rad($textAngle);
                            $textRadius = ($innerRadius + $outerRadius) / 2;
                            $textX = $centerX + $textRadius * cos($textAngleRad);
                            $textY = $centerY + $textRadius * sin($textAngleRad);
                        @endphp
                        
                        <g class="season-segment">
                            <path d="M {{ $x1 }} {{ $y1 }}
                                     L {{ $x2 }} {{ $y2 }}
                                     A {{ $outerRadius }} {{ $outerRadius }} 0 {{ $largeArcFlag }} 1 {{ $x3 }} {{ $y3 }}
                                     L {{ $x4 }} {{ $y4 }}
                                     A {{ $innerRadius }} {{ $innerRadius }} 0 {{ $largeArcFlag }} 0 {{ $x1 }} {{ $y1 }}"
                                  fill="{{ $colors[$index] }}"
                                  stroke="white"
                                  stroke-width="2"/>
                            
                            <text x="{{ $textX }}" y="{{ $textY }}"
                                  text-anchor="middle"
                                  dominant-baseline="middle"
                                  fill="#333"
                                  font-size="12"
                                  font-weight="500"
                                  transform="rotate({{ $textAngle + 90 }}, {{ $textX }}, {{ $textY }})">
                                {{ $season }}
                            </text>
                        </g>
                    @endforeach
                    
                    <!-- 中央の円と文字 -->
                    <circle cx="200" cy="200" r="75" fill="white" stroke="#E0E0E0" stroke-width="1"/>
                    <text x="200" y="190" text-anchor="middle" font-size="18" font-weight="bold" fill="#333">
                        24の季節
                    </text>
                    <text x="200" y="210" text-anchor="middle" font-size="14" fill="#666">
                        お店を記します
                    </text>
                </svg>
            </div>
            
            <div style="text-align: center; margin-top: 40px;">
                <a href="#" class="btn btn-primary">お店を探す</a>
            </div>
        </div>
    </section>
    
    <!-- フッター -->
    <footer style="background: #2C3E50; color: white; padding: 40px 0;">
        <div class="container">
            <div style="text-align: center;">
                <h3 style="margin-bottom: 20px;">TasteRetreat</h3>
                <div style="display: flex; gap: 32px; justify-content: center; margin-bottom: 20px;">
                    <a href="#" style="color: white; text-decoration: none;">プライバシー</a>
                    <a href="#" style="color: white; text-decoration: none;">利用規約</a>
                    <a href="#" style="color: white; text-decoration: none;">お問い合わせ</a>
                </div>

                <p style="opacity: 0.7; font-size: 14px;">© 2025 TasteRetreat. All rights reserved.</p>
            </div>
        </div>
    </footer>
    

    <script>
        // スムーススクロール
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    const headerHeight = document.querySelector('header').offsetHeight;
                    const targetPosition = target.offsetTop - headerHeight;
                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>
    
    <!-- Cookie同意バナー -->
    <x-cookie-consent />
</body>
</html>