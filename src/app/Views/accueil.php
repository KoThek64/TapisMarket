<?= $this->extend("layouts/" . ($layout ?? "default")) ?>

<?= $this->section("content") ?>

<section class="hero">
    <div class="hero-content">
        <span class="hero-tag">Nouvelle collection 2025</span>
        <h1>L'Art du Tapis,<br>tissé pour chez vous.</h1>
        <p>Découvrez des pièces uniques, faites à la main par des artisans du monde entier. Authenticité et élégance
            garanties.</p>
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
                        <img src="<?= base_url('images/' . esc($product->image)) ?>" alt="<?= esc($product->title) ?>"
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

<?= $this->endSection() ?>