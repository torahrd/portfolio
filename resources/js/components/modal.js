// モーダル用Alpine.js関数（CSP対応版）
export function modal() {
    return {
        show: false,
        name: '',
        maxWidth: '2xl',
        
        focusables() {
            let selector =
                "a, button, input:not([type='hidden']), textarea, select, details, [tabindex]:not([tabindex='-1'])";
            return [...this.$el.querySelectorAll(selector)].filter(
                (el) => !el.hasAttribute("disabled")
            );
        },
        firstFocusable() {
            return this.focusables()[0];
        },
        lastFocusable() {
            return this.focusables().slice(-1)[0];
        },
        nextFocusable() {
            return (
                this.focusables()[this.nextFocusableIndex()] ||
                this.firstFocusable()
            );
        },
        prevFocusable() {
            return (
                this.focusables()[this.prevFocusableIndex()] ||
                this.lastFocusable()
            );
        },
        nextFocusableIndex() {
            return (
                (this.focusables().indexOf(document.activeElement) + 1) %
                (this.focusables().length + 1)
            );
        },
        prevFocusableIndex() {
            return (
                Math.max(0, this.focusables().indexOf(document.activeElement)) -
                1
            );
        },
        shouldFocusNext() {
            this.nextFocusable().focus();
        },
        shouldFocusPrev() {
            this.prevFocusable().focus();
        },
        openModal() {
            this.show = true;
        },
        closeModal() {
            this.show = false;
        },
        handleEscape() {
            this.show = false;
        },
        handleTab(event) {
            event.preventDefault();
            this.shouldFocusNext();
        },
        handleShiftTab(event) {
            event.preventDefault();
            this.shouldFocusPrev();
        },
        handleModalOpen(event) {
            if (event.detail === this.name) {
                this.show = true;
            }
        },
        handleModalClose(event) {
            if (event.detail === this.name) {
                this.show = false;
            }
        },
        shouldShowModal() {
            return this.show;
        },
        shouldHideBody() {
            return this.show;
        },

        init() {
            this.$watch("show", (value) => {
                if (value) {
                    document.body.classList.add("overflow-hidden");
                    document.body.classList.add("modal-open");
                    setTimeout(() => this.firstFocusable()?.focus(), 100);
                } else {
                    document.body.classList.remove("overflow-hidden");
                    document.body.classList.remove("modal-open");
                }
            });
        },
    };
}
