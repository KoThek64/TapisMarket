<header>
    <div class="header-left">
        <a href="<?= base_url('/') ?>" class="logo-container">
            <span>TapisMarket</span>
        </a>
    </div>

    <nav>
        <ul class="nav-links">
            <li><a href="<?= base_url('/') ?>" class="active">Accueil</a></li>
            <li><a href="<?= site_url("#") ?>">Catalogue</a></li>
            <li><a href="<?= site_url("/client/dashboard") ?>">Mon Compte</a></li>
        </ul>
    </nav>

    <div class="header-actions">
        <a href="cart" class="btn-icon" aria-label="Panier">
            <img src="https://cdn-icons-png.flaticon.com/512/3144/3144456.png" style="width:24px" alt="Panier" />
            <span class="badge-count">0</span>
        </a>

        <a href="/auth/login" class="btn-primary">
            <span>Connexion</span>
        </a>
    </div>
</header>