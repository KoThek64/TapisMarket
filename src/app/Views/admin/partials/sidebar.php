<?php
    $uri = service('uri');
    $segment = $uri->getTotalSegments() >= 2 ? $uri->getSegment(2) : ''; 
    
    function getMenuClass($currentSegment, $targetSegment) {
        if ($targetSegment === '' && empty($currentSegment)) {
            return 'bg-primary text-white font-medium shadow-lg shadow-primary/20 transform scale-[1.02]';
        }
        if ($currentSegment === $targetSegment) {
            return 'bg-primary text-white font-medium shadow-lg shadow-primary/20 transform scale-[1.02]';
        }
        return 'text-muted hover:bg-cream hover:text-accent font-medium';
    }
?>

<aside class="w-72 bg-white border-r border-border flex flex-col shadow-xl z-30 hidden lg:flex">
    <div class="h-24 flex items-center px-8 border-b border-border bg-white/50 backdrop-blur">
        <span class="font-serif text-2xl font-bold text-primary tracking-tight">
            <span class="text-accent">Admin</span>Market
        </span>
    </div>
    
    <nav class="flex-1 px-4 py-8 space-y-2">
        <p class="px-4 text-xs font-bold text-muted uppercase tracking-widest mb-4">Menu Principal</p>
        
        <a href="<?= site_url('admin') ?>" class="flex items-center gap-3 px-4 py-3.5 rounded-xl transition-all <?= getMenuClass($segment, '') ?>">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
            Tableau de bord
        </a>
        
        <a href="<?= site_url('admin/categories') ?>" class="flex items-center gap-3 px-4 py-3.5 rounded-xl transition-all <?= getMenuClass($segment, 'categories') ?>">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
            Catégories
        </a>
        
        <a href="<?= site_url('admin/products') ?>" class="flex items-center gap-3 px-4 py-3.5 rounded-xl transition-all <?= getMenuClass($segment, 'products') ?>">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            Produits
            <?php if(isset($pendingProductsCount) && $pendingProductsCount > 0): ?>
                <span class="ml-auto bg-orange-100 text-orange-700 py-0.5 px-2 rounded-full text-xs font-bold"><?= $pendingProductsCount ?></span>
            <?php endif; ?>
        </a>
        
        <a href="<?= site_url('admin/users') ?>" class="flex items-center gap-3 px-4 py-3.5 rounded-xl transition-all <?= getMenuClass($segment, 'users') ?>">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            Utilisateurs
            <?php if(isset($pendingSellersCount) && $pendingSellersCount > 0): ?>
                <span class="ml-auto bg-orange-100 text-orange-700 py-0.5 px-2 rounded-full text-xs font-bold"><?= $pendingSellersCount ?></span>
            <?php endif; ?>
        </a>

        <a href="<?= site_url('admin/reviews') ?>" class="flex items-center gap-3 px-4 py-3.5 rounded-xl transition-all <?= getMenuClass($segment, 'reviews') ?>">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
            Avis Clients
        </a>

        <a href="<?= site_url('admin/orders') ?>" class="flex items-center gap-3 px-4 py-3.5 rounded-xl transition-all <?= getMenuClass($segment, 'orders') ?>">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            Commandes
        </a>
    </nav>
    
    <div class="p-6 border-t border-border">
        <a href="<?= site_url('auth/logout') ?>" class="flex items-center justify-center gap-2 w-full py-3.5 text-red-600 bg-red-50 hover:bg-red-100 rounded-xl transition-all font-bold text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
            Déconnexion
        </a>
    </div>
</aside>