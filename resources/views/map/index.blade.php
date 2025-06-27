<x-app-layout>
  <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-bold mb-4">店舗マップ</h1>
    <!-- <style>#map{min-height:400px;height:500px;width:100%;}</style> -->
    <div id="map" class="w-full rounded shadow h-[300px] md:h-[400px] lg:h-[500px]"></div>
    <!-- 今後: 検索ボックス・表示切替UIなど -->
  </div>

  <!-- Google Maps JS API 読み込み（APIキーは.env管理を推奨） -->
  <script src="https://maps.googleapis.com/maps/api/js?key={{ config('google.maps.api_key') }}&libraries=places"></script>
  <!-- MarkerClustererライブラリ（重なり対策） -->
  <script src="https://unpkg.com/@googlemaps/markerclusterer/dist/index.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // デフォルトは東京駅
      let center = {
        lat: 35.681236,
        lng: 139.767125
      };
      let map;
      let markers = []; // マーカー配列（クラスタリング用）

      // 現在地取得
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
          center = {
            lat: position.coords.latitude,
            lng: position.coords.longitude
          };
          map.setCenter(center);
          // 現在地マーカー
          new google.maps.Marker({
            position: center,
            map: map,
            title: '現在地',
            icon: {
              path: google.maps.SymbolPath.CIRCLE,
              scale: 8,
              fillColor: '#4285F4',
              fillOpacity: 1,
              strokeWeight: 2,
              strokeColor: '#fff'
            }
          });
        }, function() {
          // 位置情報取得失敗時
          console.log('現在地を取得できませんでした。東京駅を中心に表示します。');
        });
      }

      map = new google.maps.Map(document.getElementById('map'), {
        center: center,
        zoom: 13,
        mapId: 'DEMO_MAP_ID', // マップID設定
      });
      // console.log('Google Mapsオブジェクト:', map);

      // 店舗データ取得＆マーカー描画
      fetch('/api/shops/map-data')
        .then(res => res.json())
        .then(shops => {
          shops.forEach(shop => {
            // lat/lngがnullまたは数値でない場合はスキップ
            if (
              typeof shop.lat !== 'number' ||
              typeof shop.lng !== 'number' ||
              isNaN(shop.lat) ||
              isNaN(shop.lng)
            ) {
              return;
            }
            // レスポンシブ対応：画面幅に応じてマーカーサイズ調整
            const isMobile = window.innerWidth < 768;
            const markerSize = isMobile ? 56 : 48;
            const imageSize = isMobile ? 56 : 48;

            // カスタムHTMLマーカー
            const markerDiv = document.createElement('div');
            markerDiv.style.display = 'flex';
            markerDiv.style.flexDirection = 'column';
            markerDiv.style.alignItems = 'center';
            markerDiv.style.cursor = 'pointer';
            markerDiv.style.transition = 'transform 0.2s ease-in-out';
            markerDiv.style.userSelect = 'none';
            markerDiv.setAttribute('aria-label', `${shop.name}の詳細を見る`);

            // ホバー効果
            markerDiv.addEventListener('mouseenter', () => {
              markerDiv.style.transform = 'scale(1.1)';
            });
            markerDiv.addEventListener('mouseleave', () => {
              markerDiv.style.transform = 'scale(1)';
            });

            // 画像
            const img = document.createElement('img');
            img.src = shop.image_url || '/images/placeholder-food.jpg';
            img.alt = shop.name;
            img.style.width = `${imageSize}px`;
            img.style.height = `${imageSize}px`;
            img.style.objectFit = 'cover';
            img.style.borderRadius = '8px';
            img.style.border = `3px solid ${shop.is_open ? '#22c55e' : '#a3a3a3'}`; // グリーン/グレー
            img.style.boxShadow = '0 2px 8px rgba(0,0,0,0.15)';
            markerDiv.appendChild(img);

            // 店舗名（レスポンシブ対応）
            const nameDiv = document.createElement('div');
            nameDiv.textContent = shop.name;
            nameDiv.style.background = 'rgba(255,255,255,0.95)';
            nameDiv.style.fontSize = isMobile ? '11px' : '12px';
            nameDiv.style.padding = isMobile ? '3px 8px' : '2px 6px';
            nameDiv.style.borderRadius = '4px';
            nameDiv.style.marginTop = '4px';
            nameDiv.style.boxShadow = '0 1px 4px rgba(0,0,0,0.08)';
            nameDiv.style.maxWidth = '120px';
            nameDiv.style.textAlign = 'center';
            nameDiv.style.whiteSpace = 'nowrap';
            nameDiv.style.overflow = 'hidden';
            nameDiv.style.textOverflow = 'ellipsis';
            markerDiv.appendChild(nameDiv);

            // OverlayViewでHTMLマーカーを地図に追加
            class CustomMarker extends google.maps.OverlayView {
              constructor(position, element, url) {
                super();
                this.position = position;
                this.element = element;
                this.url = url;
              }
              onAdd() {
                this.getPanes().overlayMouseTarget.appendChild(this.element);
                // クリック領域を拡大（マーカー全体をタップ可能に）
                this.element.addEventListener('click', () => {
                  window.location.href = this.url;
                });
                // タッチデバイス対応
                this.element.addEventListener('touchend', (e) => {
                  e.preventDefault();
                  window.location.href = this.url;
                });
              }
              draw() {
                const projection = this.getProjection();
                const pos = projection.fromLatLngToDivPixel(new google.maps.LatLng(this.position));
                this.element.style.position = 'absolute';
                this.element.style.left = `${pos.x - (markerSize / 2)}px`;
                this.element.style.top = `${pos.y - markerSize}px`;
                this.element.style.zIndex = 10;
              }
              onRemove() {
                if (this.element.parentNode) {
                  this.element.parentNode.removeChild(this.element);
                }
              }
            }

            const marker = new CustomMarker({
                lat: shop.lat,
                lng: shop.lng
              },
              markerDiv,
              shop.shop_url
            );
            marker.setMap(map);
            markers.push(marker);
          });

          // MarkerClustererでマーカーをクラスタリング（カスタムマーカー非対応のため一時停止）
          // new markerClusterer.MarkerClusterer({
          //   map,
          //   markers,
          //   algorithm: new markerClusterer.SuperClusterAlgorithm({
          //     radius: 100,
          //     maxZoom: 15,
          //   }),
          //   renderer: { ... }
          // });
        })
        .catch(error => {
          console.error('店舗データの取得に失敗しました:', error);
        });

      // レスポンシブ対応：ウィンドウリサイズ時のマーカーサイズ調整
      window.addEventListener('resize', () => {
        // 必要に応じてマーカーを再描画
        google.maps.event.trigger(map, 'resize');
      });
    });
  </script>
</x-app-layout>