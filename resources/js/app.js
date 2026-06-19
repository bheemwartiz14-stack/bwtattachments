import Alpine from 'alpinejs';

window.Alpine = Alpine;

document.addEventListener('alpine:init', () => {
    Alpine.data('sidebar', () => ({
        sidebarOpen: false,
        init() {
            this.sidebarOpen = window.innerWidth >= 1024;
            this.$el.addEventListener('toggle-sidebar', () => {
                this.sidebarOpen = !this.sidebarOpen;
            });
        },
    }));

    Alpine.data('dropdown', () => ({
        open: false,
        toggle() {
            this.open = !this.open;
        },
        close() {
            this.open = false;
        },
    }));

    Alpine.data('notifications', () => ({
        open: false,
        items: [
            { id: 1, title: 'New product added', description: 'Bucket GP-12 has been added', time: '2m ago', unread: true },
            { id: 2, title: 'Quotation generated', description: 'Q-2024-0042 was created', time: '15m ago', unread: true },
            { id: 3, title: 'Client registered', description: 'ABC Construction joined', time: '1h ago', unread: false },
        ],
        unreadCount() {
            return this.items.filter(i => i.unread).length;
        },
        toggle() {
            this.open = !this.open;
        },
        close() {
            this.open = false;
        },
        markAllRead() {
            this.items.forEach(i => i.unread = false);
        },
    }));

    Alpine.data('darkMode', () => ({
        dark: false,
        init() {
            this.dark = localStorage.getItem('dark') === 'true' ||
                (!localStorage.getItem('dark') && window.matchMedia('(prefers-color-scheme: dark)').matches);
            if (this.dark) {
                document.documentElement.classList.add('dark');
            }
            this.$el.addEventListener('toggle-dark-mode', () => this.toggle());
        },
        toggle() {
            this.dark = !this.dark;
            localStorage.setItem('dark', this.dark);
            document.documentElement.classList.toggle('dark', this.dark);
        },
    }));

    Alpine.data('viewToggle', () => ({
        view: 'grid',
        setView(v) { this.view = v; },
    }));

    Alpine.data('productFilters', () => ({
        search: '',
        category: '',
        subcategory: '',
        connectionType: '',
        weightMin: 0,
        weightMax: 50000,
        showFilters: true,
        toggleFilters() {
            this.showFilters = !this.showFilters;
        },
        clearFilters() {
            this.search = '';
            this.category = '';
            this.subcategory = '';
            this.connectionType = '';
            this.weightMin = 0;
            this.weightMax = 50000;
        },
    }));

    Alpine.data('quotation', () => ({
        items: [
            { id: 1, code: 'BKT-GP-12', name: 'Bucket GP-12', weight: '1200kg', width: '1800mm', price: 4500, margin: 15, qty: 1 },
            { id: 2, code: 'BKT-HD-08', name: 'Bucket HD-08', weight: '850kg', width: '1500mm', price: 3200, margin: 12, qty: 2 },
        ],
        margin: 15,
        addItem(product) {
            this.items.push({ ...product, id: Date.now(), qty: 1, margin: this.margin });
        },
        removeItem(id) {
            this.items = this.items.filter(i => i.id !== id);
        },
        updateMargin(margin) {
            this.margin = margin;
            this.items.forEach(i => i.margin = margin);
        },
        calculateFinalPrice(item) {
            return item.price * (1 + item.margin / 100) * item.qty;
        },
        total() {
            return this.items.reduce((sum, i) => sum + this.calculateFinalPrice(i), 0);
        },
        totalWithoutMargin() {
            return this.items.reduce((sum, i) => sum + i.price * i.qty, 0);
        },
    }));

    Alpine.data('multiStepForm', () => ({
        step: 1,
        totalSteps: 5,
        next() { if (this.step < this.totalSteps) this.step++; },
        prev() { if (this.step > 1) this.step--; },
        isFirst() { return this.step === 1; },
        isLast() { return this.step === this.totalSteps; },
    }));

    Alpine.data('toast', () => ({
        visible: false,
        message: '',
        type: 'success',
        show(msg, type = 'success') {
            this.message = msg;
            this.type = type;
            this.visible = true;
            setTimeout(() => { this.visible = false; }, 4000);
        },
        hide() {
            this.visible = false;
        },
    }));

    Alpine.data('modal', () => ({
        open: false,
        show() { this.open = true; },
        hide() { this.open = false; },
    }));
});

Alpine.start();