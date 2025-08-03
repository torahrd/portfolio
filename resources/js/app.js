import "./bootstrap";

// CSPビルドテスト用に一時的に変更
import Alpine from "@alpinejs/csp";

window.Alpine = Alpine;

// テスト用カウンターコンポーネントを登録
import { testCounter } from "./components/test-counter.js";
Alpine.data('testCounter', testCounter);

// CSP対応版shopSearchコンポーネントを登録
import { shopSearchCsp } from "./components/shop-search.js";
Alpine.data('shopSearchCsp', shopSearchCsp);

// CSP対応版searchBarコンポーネントを登録
import { searchBar } from "./components/search-bar.js";
Alpine.data('searchBar', searchBar);

// CSP対応版modalコンポーネントを登録
import { modal } from "./components/modal.js";
Alpine.data('modal', modal);

// CSP対応版navigationコンポーネントを登録
import { navigation } from "./components/navigation.js";
Alpine.data('navigation', navigation);

// CSP対応版dropdownコンポーネントを登録
import { dropdown } from "./components/dropdown.js";
Alpine.data('dropdown', dropdown);

// CSP対応版headerDropdownコンポーネントを登録
import { headerDropdown } from "./components/header-dropdown.js";
Alpine.data('headerDropdown', headerDropdown);

Alpine.start();

// 既存のコンポーネントは一時的にコメントアウト
// import { commentSection } from "./components/comment-section.js";
// window.commentSection = commentSection;

// import { modal } from "./components/modal.js";
// window.modal = modal;

// import { shopSearch } from "./components/shop-search.js";
// window.shopSearch = shopSearch;

// Import post-reply functionality
import "./post-reply.js";

// Import post-like functionality
import "./post-like.js";
