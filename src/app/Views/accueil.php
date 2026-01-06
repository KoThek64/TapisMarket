<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>TapisMarket - Accueil</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="<?= base_url('Styles/style.css') ?>">
    <link href="https://fonts.googleapis.com/css2?family=Onest:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">
</head>

<body>

    <header>
        <div class="header-left">
            <a href="<?= base_url('/') ?>" class="logo-container">
                <span>TapisMarket</span>
            </a>
        </div>

        <nav>
            <ul class="nav-links">
                <li><a href="<?= base_url('/') ?>" class="active">Accueil</a></li>
                <li><a href="#">Catalogue</a></li> 
                <li><a href="#">Mon Compte</a></li>
            </ul>
        </nav>

        <div class="header-actions">
            <a href="#" class="btn-icon" aria-label="Panier">
                <img src="https://cdn-icons-png.flaticon.com/512/3144/3144456.png" style="width:24px" alt="Panier" />
                <span class="badge-count">0</span>
            </a>

            <a href="#" class="btn-primary">
                <span>Connexion</span>
            </a>
        </div>
    </header>

    <main>
        <section class="hero">
            <div class="hero-content">
                <span class="hero-tag">Nouvelle collection 2025</span>
                <h1>L'Art du Tapis,<br>tissé pour chez vous.</h1>
                <p>Découvrez des pièces uniques, faites à la main par des artisans du monde entier. Authenticité et élégance garanties.</p>
                <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
                    <a href="#" class="btn-primary" style="padding: 15px 30px;">Explorer le catalogue</a>
                </div>
            </div>
        </section>

        <section id="featured" class="section-container">
            <div class="section-header">
                <div class="section-title">
                    <h2>Nos Coups de Cœur</h2>
                    <p style="color:var(--text-muted)">Les derniers ajouts de notre catalogue</p>
                </div>
                <a href="#" style="font-weight:600; color:var(--accent); display:flex; align-items:center; gap:5px;">
                    Tout voir <span>→</span>
                </a>
            </div>

            <div class="product-grid">
                <?php if (!empty($products) && is_array($products)): ?>
                    <?php foreach ($products as $product): ?>
                        <?php 
                            $id = $product->id ?? null; 
                        ?>
                        
                        <article class="card">
                            <a href="<?= base_url('product/' . $id) ?>" class="card-image-wrapper">
                                <img src="<?= base_url('images/' . esc($product->image)) ?>" 
                                    alt="<?= esc($product->title) ?>" 
                                    class="card-image"
                                    onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1600166898405-da9535204843?q=80&w=400';">
                            </a>
                            
                            <div class="card-details">
                                <div>
                                    
                                    
                                    <h3 class="card-title">
                                        <a href="<?= base_url('product/' . $id) ?>">
                                            <?= esc($product->title) ?>
                                        </a>
                                    </h3>
                                    
                                    <p class="card-subtitle"><?= esc($product->short_description) ?></p>
                                </div>
                                
                                <div class="card-footer">
                                    <span class="price"><?= $product->getFormattedPrice() ?></span>
                                    <a href="<?= base_url('product/' . $id) ?>" class="btn-circle">+</a>
                                </div>
                            </div>
                        </article>

                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Aucun produit trouvé.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <footer class="footer-dark">
        <div class="footer-container">
            <div class="footer-col">
                <h3>À propos</h3>
                <p>Votre place de marché de confiance pour découvrir et acheter des tapis exceptionnels.</p>
            </div>
            <div class="footer-col">
                <h3>Liens</h3>
                <ul class="footer-links">
                    <li><a href="#">Catalogue</a></li>
                    <li><a href="#">Connexion</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h3>Contact</h3>
                <ul class="footer-links">
                    <li>📍 Paris, France</li>
                    <li>✉️ contact@tapisweby.com</li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© 2025 TapisWeby. Tous droits réservés.</p>
        </div>
    </footer>
</body>
</html>