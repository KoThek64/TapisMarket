<div id="sidebar-overlay" onclick="toggleSidebar()" class="fixed inset-0 bg-black/50 z-20 hidden lg:hidden backdrop-blur-sm transition-opacity"></div>

<aside id="sidebar" class="fixed lg:static inset-y-0 left-0 z-30 w-72 bg-white border-r border-border flex flex-col shadow-xl transition-transform duration-300 transform -translate-x-full lg:translate-x-0">
    
    <div class="h-24 flex items-center justify-between px-8 border-b border-border bg-white/50 backdrop-blur">
        <a href="<?= site_url('/') ?>" class="font-serif text-2xl font-bold text-primary tracking-tight">
            <span class="text-accent"><?= $section['accent'] ?></span><?= $section['non_accent'] ?>
        </a>
        
        <button onclick="toggleSidebar()" class="lg:hidden p-2 text-gray-500 hover:text-red-500 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>

    <nav class="flex-1 px-4 py-8 space-y-2 overflow-y-auto">
        <p class="px-4 text-xs font-bold text-muted uppercase tracking-widest mb-4"><?= $side_bar_title ?></p>

        <?php foreach ($items as $item): ?>
            <?= view('partials/sidebar/item', $item) ?>
        <?php endforeach; ?>
    </nav>

    <div class="p-6 border-t border-border">
        <a href="<?= site_url('auth/logout') ?>"
            class="flex items-center justify-center gap-2 w-full py-3.5 text-red-600 bg-red-50 hover:bg-red-100 rounded-xl transition-all font-bold text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                </path>
            </svg>
            Déconnexion
        </a>
    </div>
</aside>