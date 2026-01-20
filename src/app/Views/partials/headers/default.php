<?php
$uri = uri_string();
$isHome = ($uri == '' || $uri == '/');
$isCatalog = (strpos($uri, 'catalog') !== false || strpos($uri, 'product') !== false);
$isAccount = (strpos($uri, 'dashboard') !== false || strpos($uri, 'auth') !== false || strpos($uri, 'client') !== false || strpos($uri, 'seller') !== false || strpos($uri, 'admin') !== false);

$linkClass = "font-medium hover:text-accent transition-colors border-b-2 border-transparent";
$activeClass = "font-medium text-accent-light border-b-2 border-accent-light";

use \App\Enums\UserRole;
$dashboardUrl = base_url('/auth/login');
if ($role = user_role()) {
    $dashboardUrl = match ($role) {
        UserRole::CLIENT => base_url('/client'),
        UserRole::SELLER => base_url('/seller'),
        UserRole::ADMIN => base_url('/admin'),
    };
}
$cartCount = count_cart_items();
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
                <li>
                    <a href="<?= $dashboardUrl ?>" class="<?= $isAccount ? $activeClass : $linkClass ?>">
                        Mon Compte
                    </a>
                </li>
            </ul>
        </nav>

        <div class="hidden md:flex items-center gap-6">
            <a href="<?= base_url('/cart') ?>" class="relative group" aria-label="Panier">
                <img src="https://cdn-icons-png.flaticon.com/512/3144/3144456.png"
                    class="w-6 transition-transform group-hover:scale-110" alt="Panier" />
                <?php if ($cartCount > 0): ?>
                    <span
                        class="absolute -top-2 -right-2 bg-accent text-white text-[10px] font-bold rounded-full w-5 h-5 flex items-center justify-center border-2 border-white shadow-sm">
                        <?= $cartCount > 9 ? '9+' : $cartCount ?>
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

        <!-- Mobile Menu Button -->
        <button id="mobile-menu-btn" class="md:hidden flex flex-col justify-center items-center w-8 h-8 gap-1.5 focus:outline-none" aria-label="Menu">
             <span class="block w-6 h-0.5 bg-primary transition-all duration-300"></span>
             <span class="block w-6 h-0.5 bg-primary transition-all duration-300"></span>
             <span class="block w-6 h-0.5 bg-primary transition-all duration-300"></span>
        </button>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden md:hidden absolute top-full left-0 w-full bg-white border-t border-gray-100 shadow-lg animate-in slide-in-from-top-2 duration-200">
        <nav class="flex flex-col p-4 gap-4">
            <a href="<?= base_url('/') ?>" class="<?= $isHome ? 'text-accent font-semibold' : 'text-gray-700' ?> hover:text-accent transition-colors flex items-center gap-2">
                Accueil
            </a>
            <a href="<?= base_url('catalog') ?>" class="<?= $isCatalog ? 'text-accent font-semibold' : 'text-gray-700' ?> hover:text-accent transition-colors flex items-center gap-2">
                Catalogue
            </a>
            <a href="<?= $dashboardUrl ?>" class="<?= $isAccount ? 'text-accent font-semibold' : 'text-gray-700' ?> hover:text-accent transition-colors flex items-center gap-2">
                Mon Compte
            </a>
            <a href="<?= base_url('/cart') ?>" class="text-gray-700 hover:text-accent transition-colors flex items-center gap-2 justify-between">
                <span>Panier</span>
                <?php if ($cartCount > 0): ?>
                    <span class="bg-accent text-white text-xs font-bold rounded-full px-2 py-0.5"><?= $cartCount > 9 ? '9+' : $cartCount ?></span>
                <?php endif; ?>
            </a>
            <hr class="border-gray-100">
            <?php if (user_id()): ?>
                <a href="<?= base_url('/auth/logout') ?>" class="text-red-500 hover:text-red-600 font-medium flex items-center gap-2">
                    Déconnexion
                </a>
            <?php else: ?>
                <a href="<?= base_url('/auth/login') ?>" class="text-primary hover:text-accent font-medium flex items-center gap-2">
                    Connexion
                </a>
            <?php endif; ?>
        </nav>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const btn = document.getElementById('mobile-menu-btn');
            const menu = document.getElementById('mobile-menu');
            
            btn.addEventListener('click', () => {
                menu.classList.toggle('hidden');
                
                // Burger animation
                const spans = btn.getElementsByTagName('span');
                if (!menu.classList.contains('hidden')) {
                    spans[0].classList.add('rotate-45', 'translate-y-2');
                    spans[1].classList.add('opacity-0');
                    spans[2].classList.add('-rotate-45', '-translate-y-2');
                } else {
                    spans[0].classList.remove('rotate-45', 'translate-y-2');
                    spans[1].classList.remove('opacity-0');
                    spans[2].classList.remove('-rotate-45', '-translate-y-2');
                }
            });
        });
    </script>
</header>
