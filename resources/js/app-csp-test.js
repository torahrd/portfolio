import "./bootstrap";

// CSPビルドテスト専用 - 完全にクリーンな環境
import Alpine from "@alpinejs/csp";

window.Alpine = Alpine;

// コンポーネントをインポート
import { testCounter } from "./components/test-counter.js";
import { shopSearchCsp } from "./components/shop-search-csp.js";

// Alpine.jsの初期化イベントでコンポーネントを登録
document.addEventListener('alpine:init', () => {
    // テスト用カウンターコンポーネントを登録
    Alpine.data('testCounter', testCounter);

    // shopSearchCspコンポーネントを登録
    Alpine.data('shopSearchCsp', shopSearchCsp);
});

Alpine.start();

// 他のすべてのコンポーネントは除外
// これにより、コンソールエラーを完全に解消 