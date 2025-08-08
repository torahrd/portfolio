// ドロップダウン用Alpine.js関数（CSP対応版）
// ドロップダウン用Alpine.js関数（CSP対応版）
export function dropdown() {
    return {
        open: false,
        
        toggleDropdown() {
            this.open = !this.open;
        },
        
        closeDropdown() {
            this.open = false;
        },
        
        // CSP対応: getterプロパティとして定義
        get shouldShowDropdown() {
            return this.open;
        },
        
        handleClickOutside() {
            this.open = false;
        },
        
        handleClose() {
            this.open = false;
        },
    };
} 