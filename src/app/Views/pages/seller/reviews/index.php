<?= $this->extend('layouts/seller_section') ?>

<?= $this->section('header_content') ?>
<?php
$avg = $stats['avg_rating'] ?? 0;
$starsHtml = '<div class="flex text-amber-500 gap-0.5 justify-end">';
for ($i = 1; $i <= 5; $i++) {
    $fillClass = $i <= round($avg) ? 'fill-current' : 'text-gray-200 fill-current';
    $starsHtml .= '<svg class="w-4 h-4 ' . $fillClass . '" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.038 3.181a1.002 1.002 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1.001 1.001 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1.001 1.001 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1.001 1.001 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1.002 1.002 0 00.951-.69l1.037-3.181z"/></svg>';
}
$starsHtml .= '</div>';
?>
<div class="flex items-center gap-4 bg-white/50 backdrop-blur-sm px-4 py-2 rounded-xl shadow-sm border border-border">
    <div class="text-right border-r border-border pr-4 mr-4">
        <span class="block text-xs font-bold text-muted uppercase">Avis Totaux</span>
        <span class="block text-xl font-bold text-primary"><?= ($stats['count'] ?? 0) ?></span>
        <span class="block text-[10px] text-muted">Dont <?= ($stats['published_count'] ?? 0) ?> publiés</span>
    </div>
    <div class="text-right">
        <span class="block text-xs font-bold text-muted uppercase">Note Moyenne</span>
        <div class="flex flex-col items-end">
            <span class="block text-xl font-serif font-bold text-accent"><?= number_format($avg, 1) ?> / 5</span>
            <?= $starsHtml ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="max-w-6xl mx-auto space-y-8">

    <!-- Sorting Toolbar -->
    <div
        class="flex flex-col sm:flex-row justify-between items-center gap-4 bg-white p-4 rounded-xl shadow-sm border border-border">
        <div class="flex items-center gap-3">
            <span class="text-sm font-bold text-muted uppercase">Trier par :</span>
            <?php
            $currentSort = $currentSort ?? 'date_desc';
            $sortOptions = [
                'date_desc' => 'Plus récents',
                'date_asc' => 'Plus anciens',
                'rating_desc' => 'Meilleures notes',
                'rating_asc' => 'Moins bonnes notes'
            ];
            ?>
            <form action="<?= base_url('seller/reviews') ?>" method="GET">
                <div class="relative">
                    <select name="sort"
                        class="pl-4 pr-10 py-2 bg-gray-50 border border-border rounded-lg focus:ring-2 focus:ring-accent/50 focus:border-accent appearance-none cursor-pointer text-sm font-medium text-gray-700 hover:bg-white transition-colors"
                        onchange="this.form.submit()">
                        <?php foreach ($sortOptions as $value => $label): ?>
                            <option value="<?= esc($value) ?>" <?= $currentSort === $value ? 'selected' : '' ?>>
                                <?= esc($label) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-muted">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
            </form>
        </div>

        <!-- Counter info -->
        <div class="text-sm text-muted">
            Affichage de <span class="font-bold text-primary"><?= count($reviews) ?></span> avis
        </div>
    </div>

    <?php if (empty($reviews)): ?>
        <div class="bg-white rounded-2xl shadow-sm border border-border p-12 text-center">
            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-muted">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                </svg>
            </div>
            <h3 class="text-lg font-bold text-primary mb-2">Aucun avis</h3>
            <p class="text-muted">Vos produits n'ont pas encore été évalués.</p>
        </div>
    <?php else: ?>
        <div class="grid md:grid-cols-2 gap-6">
            <?php foreach ($reviews as $review): ?>
                <div class="bg-white rounded-2xl shadow-sm border border-border p-6 hover:shadow-md transition-shadow">

                    <!-- Header: User & Rating -->
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 bg-amber-50 rounded-full flex items-center justify-center text-amber-600 font-bold border border-amber-100 shadow-sm">
                                <?= substr($review->firstname, 0, 1) ?>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h4 class="font-bold text-primary"><?= esc($review->firstname . ' ' . $review->lastname) ?>
                                    </h4>
                                    <?php if (isset($review->orders_count)): ?>
                                        <?php if ($review->orders_count > 0): ?>
                                            <span
                                                class="inline-flex items-center justify-center bg-blue-50 text-blue-700 text-[10px] font-bold px-1.5 py-0.5 rounded border border-blue-100"
                                                title="Ce client a passé <?= $review->orders_count ?> commande(s) chez vous">
                                                <?= $review->orders_count ?> CMD
                                            </span>
                                        <?php else: ?>
                                            <span
                                                class="inline-flex items-center justify-center bg-gray-100 text-gray-500 text-[10px] font-bold px-1.5 py-0.5 rounded border border-gray-200"
                                                title="Aucune commande validée trouvée pour ce client">
                                                Non vérifié
                                            </span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                                <div class="flex text-amber-500 text-xs gap-0.5 mt-0.5">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <svg class="w-4 h-4 <?= $i <= $review->rating ? 'fill-current' : 'text-gray-200 fill-current' ?>"
                                            viewBox="0 0 20 20">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.038 3.181a1.002 1.002 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1.001 1.001 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1.001 1.001 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1.001 1.001 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1.002 1.002 0 00.951-.69l1.037-3.181z" />
                                        </svg>
                                    <?php endfor; ?>
                                </div>
                            </div>
                        </div>
                        <span class="text-xs text-muted"><?= date('d/m/Y', strtotime($review->published_at)) ?></span>
                    </div>

                    <!-- Product Link -->
                    <div class="mb-4">
                        <a href="<?= base_url('seller/products/' . $review->product_id . '/edit') ?>"
                            class="text-xs font-medium text-accent hover:underline flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                            Sur: <?= esc($review->product_title) ?>
                        </a>
                    </div>

                    <!-- Content -->
                    <p class="text-gray-600 text-sm leading-relaxed mb-4">
                        "<?= esc($review->comment) ?>"
                    </p>

                    <!-- Status Badge -->
                    <div class="flex items-center justify-between pt-4 border-t border-gray-50">
                        <?php if ($review->moderation_status === 'PUBLISHED'): ?>
                            <span
                                class="badge flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-green-50 text-green-700">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Publié
                            </span>
                        <?php else: ?>
                            <span
                                class="badge flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-red-50 text-red-700">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Refusé
                            </span>
                        <?php endif; ?>
                    </div>

                </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <div class="mt-8 flex justify-center">
            <?= $pager->links('default', 'tailwind') ?>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>

