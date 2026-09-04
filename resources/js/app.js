import Alpine from 'alpinejs';

window.Alpine = Alpine;

const focusableElements = (container) =>
    [...container.querySelectorAll(
        'a[href], button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])',
    )].filter((element) => !element.hasAttribute('hidden') && element.offsetParent !== null);

const trapFocus = (event, container) => {
    const elements = focusableElements(container);

    if (elements.length === 0) {
        event.preventDefault();
        return;
    }

    const first = elements[0];
    const last = elements[elements.length - 1];

    if (event.shiftKey && document.activeElement === first) {
        event.preventDefault();
        last.focus();
    } else if (!event.shiftKey && document.activeElement === last) {
        event.preventDefault();
        first.focus();
    }
};

Alpine.data('appShell', () => ({
    drawerOpen: false,
    previousFocus: null,

    openDrawer() {
        this.previousFocus = document.activeElement;
        this.drawerOpen = true;
        document.documentElement.classList.add('overflow-hidden');

        this.$nextTick(() => this.$refs.drawerClose?.focus());
    },

    closeDrawer(restoreFocus = true) {
        if (!this.drawerOpen) {
            return;
        }

        this.drawerOpen = false;
        document.documentElement.classList.remove('overflow-hidden');

        if (restoreFocus) {
            this.$nextTick(() => this.previousFocus?.focus());
        }
    },

    trapDrawerFocus(event) {
        trapFocus(event, this.$refs.drawer);
    },
}));

Alpine.data('accessibleDialog', () => ({
    open: false,
    previousFocus: null,

    openDialog() {
        this.previousFocus = document.activeElement;
        this.open = true;
        document.documentElement.classList.add('overflow-hidden');

        this.$nextTick(() => {
            focusableElements(this.$refs.dialog)[0]?.focus();
        });
    },

    closeDialog() {
        if (!this.open) {
            return;
        }

        this.open = false;
        document.documentElement.classList.remove('overflow-hidden');
        this.$nextTick(() => this.previousFocus?.focus());
    },

    trapFocus(event) {
        trapFocus(event, this.$refs.dialog);
    },
}));

Alpine.data('taskMenu', () => ({
    open: false,

    openMenu(focusFirst = false) {
        this.open = true;

        if (focusFirst) {
            this.$nextTick(() => this.$refs.firstAction?.focus());
        }
    },

    toggleMenu() {
        if (this.open) {
            this.closeMenu();
        } else {
            this.openMenu();
        }
    },

    closeMenu(restoreFocus = false) {
        if (!this.open) {
            return;
        }

        this.open = false;

        if (restoreFocus) {
            this.$nextTick(() => this.$refs.trigger?.focus());
        }
    },
}));

Alpine.start();
