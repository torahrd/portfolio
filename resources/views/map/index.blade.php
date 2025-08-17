@extends('layouts.app')

@push('scripts')
  @vite(['resources/js/map-index.js'])
@endpush

@section('content')
  <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-bold mb-4">店舗マップ</h1>
    <!-- <style>#map{min-height:400px;height:500px;width:100%;}</style> -->
    <div id="map" class="w-full rounded shadow h-[300px] md:h-[400px] lg:h-[500px]"></div>
    <!-- 今後: 検索ボックス・表示切替UIなど -->
  </div>

  <!-- Google Maps JS API 読み込み（APIキーは.env管理） -->
  <script src="https://maps.googleapis.com/maps/api/js?key={{ config('google.maps.api_key') }}&libraries=places"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // レトロスタイルの定義（Google公式サンプル）
      const retroStyle = [{
          elementType: "geometry",
          stylers: [{
            color: "#ebe3cd"
          }]
        },
        {
          elementType: "labels.text.fill",
          stylers: [{
            color: "#523735"
          }]
        },
        {
          elementType: "labels.text.stroke",
          stylers: [{
            color: "#f5f1e6"
          }]
        },
        {
          featureType: "administrative",
          elementType: "geometry.stroke",
          stylers: [{
            color: "#c9b2a6"
          }],
        },
        {
          featureType: "administrative.land_parcel",
          elementType: "geometry.stroke",
          stylers: [{
            color: "#dcd2be"
          }],
        },
        {
          featureType: "administrative.land_parcel",
          elementType: "labels.text.fill",
          stylers: [{
            color: "#ae9e90"
          }],
        },
        {
          featureType: "landscape.natural",
          elementType: "geometry",
          stylers: [{
            color: "#dfd2ae"
          }],
        },
        {
          featureType: "poi",
          elementType: "geometry",
          stylers: [{
            color: "#dfd2ae"
          }],
        },
        {
          featureType: "poi",
          elementType: "labels.text.fill",
          stylers: [{
            color: "#93817c"
          }],
        },
        {
          featureType: "poi.park",
          elementType: "geometry.fill",
          stylers: [{
            color: "#a5b076"
          }],
        },
        {
          featureType: "poi.park",
          elementType: "labels.text.fill",
          stylers: [{
            color: "#447530"
          }],
        },
        {
          featureType: "road",
          elementType: "geometry",
          stylers: [{
            color: "#f5f1e6"
          }],
        },
        {
          featureType: "road.arterial",
          elementType: "geometry",
          stylers: [{
            color: "#fdfcf8"
          }],
        },
        {
          featureType: "road.highway",
          elementType: "geometry",
          stylers: [{
            color: "#f8c967"
          }],
        },
        {
          featureType: "road.highway",
          elementType: "geometry.stroke",
          stylers: [{
            color: "#e9bc62"
          }],
        },
        {
          featureType: "road.highway.controlled_access",
          elementType: "geometry",
          stylers: [{
            color: "#e98d58"
          }],
        },
        {
          featureType: "road.highway.controlled_access",
          elementType: "geometry.stroke",
          stylers: [{
            color: "#db8555"
          }],
        },
        {
          featureType: "road.local",
          elementType: "labels.text.fill",
          stylers: [{
            color: "#806b63"
          }],
        },
        {
          featureType: "transit.line",
          elementType: "geometry",
          stylers: [{
            color: "#dfd2ae"
          }],
        },
        {
          featureType: "transit.line",
          elementType: "labels.text.fill",
          stylers: [{
            color: "#8f7d77"
          }],
        },
        {
          featureType: "transit.line",
          elementType: "labels.text.stroke",
          stylers: [{
            color: "#ebe3cd"
          }],
        },
        {
          featureType: "transit.station",
          elementType: "geometry",
          stylers: [{
            color: "#dfd2ae"
          }],
        },
        {
          featureType: "water",
          elementType: "geometry.fill",
          stylers: [{
            color: "#b9d3c2"
          }],
        },
        {
          featureType: "water",
          elementType: "labels.text.fill",
          stylers: [{
            color: "#92998d"
          }],
        },
      ];

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
        styles: retroStyle, // レトロスタイルを適用
      });
      // console.log('Google Mapsオブジェクト:', map);

      // 店舗データ取得＆マーカー描画
      fetch('/api/shops/map-data')
        .then(res => res.json())
        .then(shops => {
          shops.forEach(shop => {
            const lat = Number(shop.lat);
            const lng = Number(shop.lng);
            if (
              typeof lat !== 'number' ||
              typeof lng !== 'number' ||
              isNaN(lat) ||
              isNaN(lng)
            ) {
              return;
            }
            const marker = new google.maps.Marker({
              position: {
                lat,
                lng
              },
              map: map,
              title: shop.name
            });
            marker.addListener('click', () => {
              window.location.href = shop.shop_url;
            });
            markers.push(marker);
          });

          // マーカークラスタリングを有効化
          if (markers.length > 0 && window.MarkerClusterer) {
            new window.MarkerClusterer({
              map,
              markers
            });
          }
        })
        .catch(error => {
          // エラー時は必要に応じてUI表示や通知を追加
        });

      // レスポンシブ対応：ウィンドウリサイズ時のマーカーサイズ調整
      window.addEventListener('resize', () => {
        // 必要に応じてマーカーを再描画
        google.maps.event.trigger(map, 'resize');
      });
    });
  </script>
@endsection