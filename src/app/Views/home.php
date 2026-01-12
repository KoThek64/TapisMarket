<?= $this->extend("layouts/default") ?>

<?= $this->section("content") ?>

<section class="relative bg-cream/30 pt-20 pb-32 overflow-hidden">
    <!-- Decorative background element -->
    <div class="absolute top-0 right-0 w-1/2 h-full bg-gradient-to-l from-white to-transparent opacity-50 pointer-events-none"></div>

    <div class="relative max-w-[1600px] mx-auto px-[5%] text-center pt-16">
        <span class="inline-block animate-fadeIn text-accent font-bold uppercase tracking-[0.2em] text-xs md:text-sm mb-6 bg-accent/5 px-4 py-2 rounded-full">
            Nouvelle collection 2026
        </span>
        
        <h1 class="font-serif text-5xl md:text-7xl lg:text-8xl font-bold text-primary mb-8 leading-tight">
            L'Art du Tapis,<br>
            <span class="italic text-accent">tissé pour vous.</span>
        </h1>
        
        <p class="text-muted text-lg md:text-xl max-w-2xl mx-auto mb-12 leading-relaxed">
            Découvrez des pièces uniques, faites à la main par des artisans du monde entier. 
            Authenticité et élégance garanties pour sublimer votre intérieur.
        </p>
        
        <div class="flex gap-4 justify-center flex-wrap">
            <a href="<?= base_url('catalog') ?>" class="group bg-primary text-white px-8 py-4 rounded font-bold uppercase tracking-widest hover:bg-accent transition-all duration-300 shadow-lg hover:shadow-accent/25 flex items-center gap-2">
                Explorer le catalogue
                <span class="group-hover:translate-x-1 transition-transform">→</span>
            </a>
        </div>
    </div>
</section>

<section id="featured" class="max-w-[1600px] mx-auto px-[5%] py-24">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-16 border-b border-border-light pb-6">
        <div>
            <h2 class="font-serif text-3xl md:text-4xl text-primary font-bold mb-3">Nos Coups de Cœur</h2>
            <p class="text-muted">Les derniers ajouts de notre catalogue</p>
        </div>
        <a href="<?= base_url('catalog') ?>" class="group font-bold text-accent flex items-center gap-2 hover:text-primary transition-colors py-2">
            Tout voir 
            <span class="group-hover:translate-x-1 transition-transform">→</span>
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
        <?php if (!empty($products) && is_array($products)): ?>
            <?php foreach ($products as $product): ?>
                
                <?= view('partials/carpet_card', ['product' => $product]) ?>

            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-span-full py-20 text-center bg-gray-50 rounded-xl border border-dashed border-gray-200">
                <p class="text-muted font-serif text-xl">Aucun produit mis en avant pour le moment.</p>
                <a href="<?= base_url('catalog') ?>" class="inline-block mt-4 text-accent font-bold hover:underline">Voir tout le catalogue</a>
            </div>
        <?php endif; ?>
    </div>
</section>

<?= $this->endSection() ?>