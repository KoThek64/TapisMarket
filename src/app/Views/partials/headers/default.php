<?php
$uri = uri_string();
$isHome = ($uri == '' || $uri == '/');
$isCatalog = (strpos($uri, 'catalog') !== false || strpos($uri, 'product') !== false);
$isAccount = (strpos($uri, 'dashboard') !== false || strpos($uri, 'auth') !== false || strpos($uri, 'client') !== false || strpos($uri, 'seller') !== false || strpos($uri, 'admin') !== false);

$linkClass = "font-medium hover:text-accent transition-colors border-b-2 border-transparent";
$activeClass = "font-medium text-accent-light border-b-2 border-accent-light";
?>
<header class="sticky top-0 z-50 bg-white border-b border-gray-200 shadow-sm">
    <div class="max-w-[1600px] mx-auto px-[5%] py-4 flex justify-between items-center">
        <div class="flex items-center">
            <a href="<?= base_url('/') ?>" class="font-serif text-2xl font-bold text-primary">
                TapisMarket
            </a>
        </div>

        <nav class="hidden md:block">
            <ul class="flex gap-8 list-none">
                <li>
                    <a href="<?= base_url('/') ?>" class="<?= $isHome ? $activeClass : $linkClass ?>">
                        Accueil
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('catalog') ?>" class="<?= $isCatalog ? $activeClass : $linkClass ?>">
                        Catalogue
                    </a>
                </li>
                <?php
                use \App\Enums\UserRole;
                $dashboardUrl = base_url('/auth/login');
                if ($role = user_role()) {
                    $dashboardUrl = match ($role) {
                        UserRole::CLIENT => base_url('/client'),
                        UserRole::SELLER => base_url('/seller'),
                        UserRole::ADMIN => base_url('/admin'),
                    };
                }
                ?>
                <li>
                    <a href="<?= $dashboardUrl ?>" class="<?= $isAccount ? $activeClass : $linkClass ?>">
                        Mon Compte
                    </a>
                </li>
            </ul>
        </nav>

        <div class="flex items-center gap-6">
            <a href="<?= base_url('/cart') ?>" class="relative group" aria-label="Panier">
                <img src="https://cdn-icons-png.flaticon.com/512/3144/3144456.png"
                    class="w-6 transition-transform group-hover:scale-110" alt="Panier" />
                <?php $cartCount = count_cart_items(); ?>
                <?php if ($cartCount > 0): ?>
                    <span
                        class="absolute -top-2 -right-2 bg-accent text-white text-[10px] font-bold rounded-full w-5 h-5 flex items-center justify-center border-2 border-white shadow-sm">
                        <?= $cartCount > 99 ? '99+' : $cartCount ?>
                    </span>
                <?php endif; ?>
            </a>

            <?php if (user_id()): ?>
                <a href="<?= base_url('/auth/logout') ?>"
                    class="inline-flex items-center justify-center gap-2 bg-primary text-white font-bold rounded transition-all duration-300 hover:bg-accent hover:-translate-y-1 hover:shadow-lg px-5 py-2.5 text-sm font-semibold hover:bg-opacity-90 shadow-none hover:shadow-md">
                    <span>Déconnexion</span>
                </a>
            <?php else: ?>
                <a href="<?= base_url('/auth/login') ?>"
                    class="inline-flex items-center justify-center gap-2 bg-primary text-white font-bold rounded transition-all duration-300 hover:bg-accent hover:-translate-y-1 hover:shadow-lg px-5 py-2.5 text-sm font-semibold hover:bg-opacity-90 shadow-none hover:shadow-md">
                    <span>Connexion</span>
                </a>
            <?php endif; ?>
        </div>
    </div>
</header>
