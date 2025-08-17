// Google Maps with MarkerClusterer
import { MarkerClusterer } from '@googlemaps/markerclusterer';

// グローバルに公開（Bladeテンプレートから参照可能にする）
window.MarkerClusterer = MarkerClusterer;

// 初期化関数もグローバルに公開
window.initMap = function() {
    // この関数はBlade側で定義される
    if (typeof window.initMapCallback === 'function') {
        window.initMapCallback();
    }
};