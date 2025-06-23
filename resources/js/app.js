import "./bootstrap";

import Alpine from "alpinejs";

window.Alpine = Alpine;

// コメントセクション用Alpine関数をグローバル登録
import { commentSection } from "./components/comment-section.js";
window.commentSection = commentSection;

Alpine.start();

// Import post-reply functionality
import "./post-reply.js";
