// TasteRetreat ランディングページ JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // 24節気データ
    const seasonalData = [
        { name: '立春', season: 'spring', color: '#10b981' },
        { name: '雨水', season: 'spring', color: '#059669' },
        { name: '啓蟄', season: 'spring', color: '#047857' },
        { name: '春分', season: 'spring', color: '#065f46' },
        { name: '清明', season: 'spring', color: '#064e3b' },
        { name: '穀雨', season: 'spring', color: '#022c22' },
        { name: '立夏', season: 'summer', color: '#3b82f6' },
        { name: '小満', season: 'summer', color: '#2563eb' },
        { name: '芒種', season: 'summer', color: '#1d4ed8' },
        { name: '夏至', season: 'summer', color: '#1e40af' },
        { name: '小暑', season: 'summer', color: '#1e3a8a' },
        { name: '大暑', season: 'summer', color: '#172554' },
        { name: '立秋', season: 'autumn', color: '#f59e0b' },
        { name: '処暑', season: 'autumn', color: '#d97706' },
        { name: '白露', season: 'autumn', color: '#b45309' },
        { name: '秋分', season: 'autumn', color: '#92400e' },
        { name: '寒露', season: 'autumn', color: '#78350f' },
        { name: '霜降', season: 'autumn', color: '#451a03' },
        { name: '立冬', season: 'winter', color: '#6366f1' },
        { name: '小雪', season: 'winter', color: '#5b21b6' },
        { name: '大雪', season: 'winter', color: '#581c87' },
        { name: '冬至', season: 'winter', color: '#4c1d95' },
        { name: '小寒', season: 'winter', color: '#3730a3' },
        { name: '大寒', season: 'winter', color: '#312e81' }
    ];

    // 現在の節気を取得（簡易版）
    function getCurrentSeason() {
        const now = new Date();
        const dayOfYear = Math.floor((now - new Date(now.getFullYear(), 0, 0)) / 86400000);
        const seasonIndex = Math.floor((dayOfYear / 365) * 24) % 24;
        return seasonIndex;
    }

    // 24節気円グラフを生成
    function generateSeasonalCircle(containerId, size = 'large') {
        const container = document.getElementById(containerId);
        if (!container) return;

        const segments = container.querySelector('g');
        if (!segments) return;

        const radius = size === 'large' ? 150 : 120;
        const centerX = 200;
        const centerY = 200;
        const segmentAngle = 360 / 24;

        seasonalData.forEach((season, index) => {
            const startAngle = index * segmentAngle;
            const endAngle = (index + 1) * segmentAngle;
            
            // SVGパスを計算
            const startAngleRad = (startAngle * Math.PI) / 180;
            const endAngleRad = (endAngle * Math.PI) / 180;
            
            const x1 = centerX + radius * Math.cos(startAngleRad);
            const y1 = centerY + radius * Math.sin(startAngleRad);
            const x2 = centerX + radius * Math.cos(endAngleRad);
            const y2 = centerY + radius * Math.sin(endAngleRad);
            
            const largeArcFlag = endAngle - startAngle <= 180 ? "0" : "1";
            
            const pathData = [
                "M", centerX, centerY,
                "L", x1, y1,
                "A", radius, radius, 0, largeArcFlag, 1, x2, y2,
                "Z"
            ].join(" ");

            // セグメント要素を作成
            const path = document.createElementNS("http://www.w3.org/2000/svg", "path");
            path.setAttribute("d", pathData);
            path.setAttribute("fill", season.color);
            path.setAttribute("opacity", "0.7");
            path.setAttribute("class", "seasonal-segment");
            path.setAttribute("data-season", season.name);
            path.setAttribute("data-index", index);
            
            // ホバー効果
            path.addEventListener('mouseenter', function() {
                this.setAttribute('opacity', '0.9');
                showSeasonTooltip(season.name, index);
            });
            
            path.addEventListener('mouseleave', function() {
                this.setAttribute('opacity', '0.7');
                hideSeasonTooltip();
            });

            segments.appendChild(path);

            // 節気名ラベル（大きいサイズのみ）
            if (size === 'large') {
                const labelAngle = startAngle + segmentAngle / 2;
                const labelAngleRad = (labelAngle * Math.PI) / 180;
                const labelRadius = radius + 20;
                const labelX = centerX + labelRadius * Math.cos(labelAngleRad);
                const labelY = centerY + labelRadius * Math.sin(labelAngleRad);

                const text = document.createElementNS("http://www.w3.org/2000/svg", "text");
                text.setAttribute("x", labelX);
                text.setAttribute("y", labelY);
                text.setAttribute("class", "seasonal-label");
                text.textContent = season.name;
                
                segments.appendChild(text);
            }
        });
    }

    // ツールチップ表示
    function showSeasonTooltip(seasonName, index) {
        // 簡易ツールチップ（実装は省略）
        console.log(`${seasonName} (${index + 1}/24)`);
    }

    function hideSeasonTooltip() {
        // ツールチップ非表示（実装は省略）
    }

    // 円グラフの回転機能
    function makeRotatable(circleId) {
        const circle = document.getElementById(circleId);
        if (!circle) return;

        let isDragging = false;
        let startAngle = 0;
        let currentRotation = 0;

        function getAngle(event, element) {
            const rect = element.getBoundingClientRect();
            const centerX = rect.left + rect.width / 2;
            const centerY = rect.top + rect.height / 2;
            const x = event.clientX - centerX;
            const y = event.clientY - centerY;
            return Math.atan2(y, x) * (180 / Math.PI);
        }

        circle.addEventListener('mousedown', function(e) {
            isDragging = true;
            startAngle = getAngle(e, this);
            this.style.cursor = 'grabbing';
        });

        document.addEventListener('mousemove', function(e) {
            if (!isDragging) return;
            
            const currentAngle = getAngle(e, circle);
            const deltaAngle = currentAngle - startAngle;
            currentRotation += deltaAngle;
            
            circle.style.transform = `rotate(${currentRotation}deg)`;
            startAngle = currentAngle;
        });

        document.addEventListener('mouseup', function() {
            if (isDragging) {
                isDragging = false;
                circle.style.cursor = 'grab';
            }
        });

        // タッチイベント対応
        circle.addEventListener('touchstart', function(e) {
            isDragging = true;
            const touch = e.touches[0];
            startAngle = getAngle(touch, this);
            e.preventDefault();
        });

        document.addEventListener('touchmove', function(e) {
            if (!isDragging) return;
            
            const touch = e.touches[0];
            const currentAngle = getAngle(touch, circle);
            const deltaAngle = currentAngle - startAngle;
            currentRotation += deltaAngle;
            
            circle.style.transform = `rotate(${currentRotation}deg)`;
            startAngle = currentAngle;
            e.preventDefault();
        });

        document.addEventListener('touchend', function() {
            isDragging = false;
        });
    }

    // デモ検索機能
    function initDemoSearch() {
        const searchInput = document.getElementById('demo-search-input');
        const searchResults = document.getElementById('demo-search-results');
        const addButton = document.getElementById('add-to-list-btn');
        
        if (!searchInput || !searchResults) return;

        // ダミーの検索結果
        const dummyResults = [
            { name: '一蘭 渋谷店', address: '東京都渋谷区道玄坂2-29-11', type: 'ラーメン' },
            { name: 'ラーメン二郎 渋谷店', address: '東京都渋谷区桜丘町25-18', type: 'ラーメン' },
            { name: '麺屋 武蔵 渋谷店', address: '東京都渋谷区渋谷3-15-2', type: 'ラーメン' },
            { name: '渋谷 らーめん横丁', address: '東京都渋谷区渋谷1-25-8', type: 'ラーメン' },
            { name: 'つけ麺 道', address: '東京都渋谷区宇田川町36-6', type: 'つけ麺' }
        ];

        let selectedShop = null;

        searchInput.addEventListener('input', function() {
            const query = this.value.trim();
            
            if (query.length < 2) {
                searchResults.classList.add('hidden');
                return;
            }

            // 検索結果を表示
            const filteredResults = dummyResults.filter(shop => 
                shop.name.includes(query) || 
                shop.address.includes(query) ||
                shop.type.includes(query)
            );

            if (filteredResults.length > 0) {
                displaySearchResults(filteredResults);
                searchResults.classList.remove('hidden');
            } else {
                searchResults.classList.add('hidden');
            }
        });

        function displaySearchResults(results) {
            searchResults.innerHTML = results.map(shop => `
                <div class="search-result-item px-4 py-3 cursor-pointer" data-shop='${JSON.stringify(shop)}'>
                    <div class="font-medium text-neutral-900">${shop.name}</div>
                    <div class="text-sm text-neutral-600">${shop.address}</div>
                    <div class="text-xs text-amber-600 mt-1">${shop.type}</div>
                </div>
            `).join('');

            // 結果アイテムのクリックイベント
            searchResults.querySelectorAll('.search-result-item').forEach(item => {
                item.addEventListener('click', function() {
                    selectedShop = JSON.parse(this.dataset.shop);
                    searchInput.value = selectedShop.name;
                    searchResults.classList.add('hidden');
                    
                    // 地図を更新
                    updateDemoMap(selectedShop);
                    
                    // 追加ボタンを有効化
                    if (addButton) {
                        addButton.disabled = false;
                        addButton.textContent = `「${selectedShop.name}」を名店リストに追加`;
                    }
                });
            });
        }

        // 外部クリックで検索結果を非表示
        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                searchResults.classList.add('hidden');
            }
        });

        // 名店リスト追加ボタン
        if (addButton) {
            addButton.addEventListener('click', function() {
                if (!selectedShop) return;
                
                // 成功メッセージを表示
                const successDiv = document.getElementById('add-success');
                if (successDiv) {
                    successDiv.classList.remove('hidden');
                    
                    // デモ用の円グラフを更新
                    addShopToSeasonalCircle(selectedShop);
                    
                    // ボタンを無効化
                    this.disabled = true;
                    this.textContent = '追加完了';
                    this.classList.add('bg-green-500', 'hover:bg-green-600');
                    this.classList.remove('bg-amber-500', 'hover:bg-amber-600');
                }
            });
        }
    }

    // デモ地図の更新
    function updateDemoMap(shop) {
        const mapContainer = document.getElementById('demo-map');
        if (!mapContainer) return;

        // 簡易的な地図表示（実際のGoogle Maps APIは後で実装）
        mapContainer.innerHTML = `
            <div class="w-full h-full bg-gradient-to-br from-blue-100 to-green-100 rounded-lg flex items-center justify-center relative overflow-hidden">
                <div class="absolute inset-0 opacity-20">
                    <div class="w-full h-full bg-gradient-to-br from-green-200 via-blue-200 to-purple-200"></div>
                </div>
                <div class="relative z-10 text-center">
                    <div class="w-8 h-8 bg-red-500 rounded-full mx-auto mb-2 animate-bounce"></div>
                    <div class="bg-white/90 backdrop-blur-sm rounded-lg px-3 py-2 shadow-lg">
                        <p class="font-medium text-neutral-900 text-sm">${shop.name}</p>
                        <p class="text-xs text-neutral-600">${shop.address}</p>
                    </div>
                </div>
                <div class="absolute top-2 right-2 bg-white/80 rounded px-2 py-1">
                    <p class="text-xs text-neutral-600">デモ地図</p>
                </div>
            </div>
        `;
    }

    // 名店リストに店舗を追加（デモ用）
    function addShopToSeasonalCircle(shop) {
        // 立春の位置（index 0）にマーカーを追加
        const demoCircle = document.getElementById('demo-seasonal-segments');
        if (!demoCircle) return;

        // 店舗マーカーを追加
        const marker = document.createElementNS("http://www.w3.org/2000/svg", "circle");
        marker.setAttribute("cx", "200");
        marker.setAttribute("cy", "50");
        marker.setAttribute("r", "6");
        marker.setAttribute("fill", "#ef4444");
        marker.setAttribute("class", "animate-pulse");
        
        demoCircle.appendChild(marker);

        // 店舗名ラベルを追加
        const label = document.createElementNS("http://www.w3.org/2000/svg", "text");
        label.setAttribute("x", "200");
        label.setAttribute("y", "40");
        label.setAttribute("text-anchor", "middle");
        label.setAttribute("class", "text-xs fill-red-600 font-medium");
        label.textContent = shop.name.substring(0, 6) + '...';
        
        demoCircle.appendChild(label);
    }

    // Google Maps API初期化（実際のAPI使用）
    window.initDemoMap = function() {
        // Google Maps APIが利用可能な場合のみ実行
        if (typeof google !== 'undefined' && google.maps) {
            console.log('Google Maps API loaded successfully');
            // 実際の地図機能は必要に応じて実装
        }
    };

    // スクロールアニメーション
    function initScrollAnimations() {
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-fade-in-up');
                }
            });
        }, observerOptions);

        // アニメーション対象要素を監視
        document.querySelectorAll('.card-hover, .seasonal-circle').forEach(el => {
            observer.observe(el);
        });
    }

    // ヘッダーのスクロール効果
    function initHeaderScroll() {
        const header = document.querySelector('header');
        if (!header) return;

        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                header.classList.add('header-scrolled');
            } else {
                header.classList.remove('header-scrolled');
            }
        });
    }

    // スムーススクロール
    function initSmoothScroll() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    }

    // 初期化
    function init() {
        // 24節気円グラフを生成
        generateSeasonalCircle('seasonal-segments', 'large');
        generateSeasonalCircle('main-seasonal-segments', 'large');
        generateSeasonalCircle('demo-seasonal-segments', 'small');
        
        // 回転機能を有効化
        makeRotatable('seasonal-preview');
        makeRotatable('main-seasonal-circle');
        
        // デモ検索機能を初期化
        initDemoSearch();
        
        // アニメーションを初期化
        initScrollAnimations();
        initHeaderScroll();
        initSmoothScroll();
        
        console.log('TasteRetreat Landing Page initialized');
    }

    // 初期化実行
    init();
});

// エラーハンドリング
window.addEventListener('error', function(e) {
    console.error('Landing page error:', e.error);
});

// パフォーマンス監視
window.addEventListener('load', function() {
    if ('performance' in window) {
        const loadTime = performance.now();
        console.log(`Landing page loaded in ${loadTime.toFixed(2)}ms`);
    }
});