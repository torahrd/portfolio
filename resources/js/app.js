import "./bootstrap";

import Alpine from "alpinejs";

window.Alpine = Alpine;

// コメントセクション用Alpine関数をグローバル登録
import { commentSection } from "./components/comment-section.js";
window.commentSection = commentSection;

import { modal } from "./components/modal.js";
window.modal = modal;

import { searchBar } from "./components/search-bar.js";
window.searchBar = searchBar;

Alpine.start();

// Import post-reply functionality
import "./post-reply.js";
