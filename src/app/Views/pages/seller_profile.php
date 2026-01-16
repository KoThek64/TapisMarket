<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<div class="max-w-[1400px] mx-auto px-4 py-8">

    <!-- Profile Header -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-12 flex flex-col md:flex-row items-center gap-8">
        <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center text-3xl font-serif font-bold text-primary border border-gray-200 shadow-sm flex-shrink-0">
            <?= substr(strtoupper($seller->shop_name), 0, 1) ?>
        </div>

        <div class="flex-1 text-center md:text-left">
            <h1 class="text-3xl font-serif font-bold text-gray-900 mb-2"><?= esc($seller->shop_name) ?></h1>
            <?php if (!empty($seller->shop_description)): ?>
                <p class="text-gray-500 max-w-2xl text-sm leading-relaxed mx-auto md:mx-0"><?= esc($seller->shop_description) ?></p>
            <?php endif; ?>
        </div>

        <div class="flex flex-col items-center justify-center px-6 py-3 bg-gray-50 rounded-xl border border-gray-100 min-w-[120px]">
            <span class="block font-bold text-2xl text-primary"><?= $pager->getTotal() ?></span>
            <span class="text-xs text-gray-500 uppercase tracking-wide font-medium">Produits</span>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="mb-8">
        <h2 class="text-xl font-bold font-serif mb-6 flex items-center gap-3">
            <span class="w-8 h-px bg-primary"></span>
            Catalogue du vendeur
        </h2>

        <?php if (!empty($products)): ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <?php foreach ($products as $product): ?>
                    <?= view('partials/carpet_card', ['product' => $product]) ?>
                <?php endforeach; ?>
            </div>

            <div class="mt-12 flex justify-center">
                <?= $pager->links('default', 'tailwind') ?>
            </div>
        <?php else: ?>
            <div class="text-center py-20 bg-gray-50 rounded-2xl border border-dashed border-gray-300">
                <h3 class="text-lg font-bold text-gray-900 mb-2">La boutique est vide</h3>
                <p class="text-gray-500">Ce vendeur n'a pas encore mis de produits en ligne.</p>
            </div>
        <?php endif; ?>
    </div>

</div>
<?= $this->endSection() ?>