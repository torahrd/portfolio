// ヘッダードロップダウン用Alpine.js関数（CSP対応版）
export function headerDropdown() {
    return {
        open: false,
        
        toggleDropdown() {
            this.open = !this.open;
        },
        
        closeDropdown() {
            this.open = false;
        },
        
        shouldShowDropdown() {
            return this.open;
        },
        
        handleClickOutside() {
            this.open = false;
        },
    };
} 