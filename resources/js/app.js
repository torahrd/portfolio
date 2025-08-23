import "./bootstrap";

// CSPビルドに切り替え（セキュリティ強化）
import Alpine from "@alpinejs/csp";

window.Alpine = Alpine;

// テスト用カウンターコンポーネントを登録
import { testCounter } from "./components/test-counter.js";
Alpine.data('testCounter', testCounter);

// CSP対応版shopSearchコンポーネントを登録
import { shopSearchCsp } from "./components/shop-search-csp.js";
Alpine.data('shopSearchCsp', shopSearchCsp);

// CSP対応版searchBarコンポーネントを登録
import { searchBar } from "./components/search-bar.js";
Alpine.data('searchBar', searchBar);

// CSP対応版modalコンポーネントを登録
import { modal } from "./components/modal.js";
Alpine.data('modal', modal);

// CSP対応版commentCardコンポーネントを登録
import { commentCard } from "./components/comment-card.js";
Alpine.data('commentCard', commentCard);

// CSP対応版navigationコンポーネントを登録
import { navigation } from "./components/navigation.js";
Alpine.data('navigation', navigation);

// CSP対応版dropdownコンポーネントを登録
import { dropdown } from "./components/dropdown.js";
Alpine.data('dropdown', dropdown);

// CSP対応版headerDropdownコンポーネントを登録
import { headerDropdown } from "./components/header-dropdown.js";
Alpine.data('headerDropdown', headerDropdown);

// CSP対応版commentReplyTestコンポーネントを登録
import commentReplyTest from './components/comment-reply-test';
Alpine.data('commentReplyTest', commentReplyTest);

// CSP対応版commentSectionコンポーネントを登録
import { commentSection } from "./components/comment-section.js";
Alpine.data('commentSection', commentSection);

// CSP対応版shopTabsコンポーネントを登録
import { shopTabs } from "./components/shop-tabs.js";
Alpine.data('shopTabs', shopTabs);

Alpine.start();

// import { modal } from "./components/modal.js";
// window.modal = modal;

// import { shopSearch } from "./components/shop-search.js";
// window.shopSearch = shopSearch;

// Import post-reply functionality
// 二重送信問題のため一時的に無効化 - Alpine.jsのcommentSectionと競合
// import "./post-reply.js";

// Import post-like functionality
import "./post-like.js";
