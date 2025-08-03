// ナビゲーション用Alpine.js関数（CSP対応版）
export function navigation() {
    return {
        open: false,
        
        toggleMenu() {
            this.open = !this.open;
        },
        
        shouldShowMenu() {
            return this.open;
        },
        
        shouldShowHamburger() {
            return !this.open;
        },
        
        shouldShowClose() {
            return this.open;
        },
    };
} 