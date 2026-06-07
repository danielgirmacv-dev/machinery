import Alpine from 'alpinejs';

window.Alpine = Alpine;

document.addEventListener('alpine:init', () => {
    Alpine.store('theme', {
        init() {
            const saved = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            this.mode = saved || (prefersDark ? 'dark' : 'light');
            this.apply();
        },
        mode: 'light',
        toggle() {
            this.mode = this.mode === 'light' ? 'dark' : 'light';
            localStorage.setItem('theme', this.mode);
            this.apply();
        },
        apply() {
            document.documentElement.classList.toggle('dark', this.mode === 'dark');
        },
    });

    Alpine.store('sidebar', {
        collapsed: localStorage.getItem('sidebarCollapsed') === 'true',
        mobileOpen: false,
        toggle() {
            this.collapsed = !this.collapsed;
            localStorage.setItem('sidebarCollapsed', String(this.collapsed));
        },
        openMobile() {
            this.mobileOpen = true;
        },
        closeMobile() {
            this.mobileOpen = false;
        },
    });
});

Alpine.start();
