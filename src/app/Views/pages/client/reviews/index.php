<?= $this->extend('layouts/client_section') ?>

<?= $this->section('header_content') ?>
<a href="<?= site_url('/') ?>"
    class="px-4 py-2 border border-border rounded-xl hover:bg-white transition text-sm font-bold flex items-center gap-2 bg-gray-50/50 text-gray-700">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
    </svg>
    Retour à la boutique
</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="space-y-8">

    <?php if (empty($reviews)): ?>
        <div class="bg-white rounded-2xl shadow-sm border border-border p-16 text-center">
            <div class="w-16 h-16 bg-yellow-50 rounded-full flex items-center justify-center text-3xl mx-auto mb-4">⭐</div>
            <h3 class="font-serif text-xl font-bold text-primary mb-2">Aucun avis</h3>
            <p class="text-muted">Vous n'avez pas encore publié d'avis sur vos achats.</p>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <?php foreach ($reviews as $review): ?>
                <div
                    class="bg-white p-6 rounded-2xl shadow-sm border border-border hover:shadow-md transition duration-300 flex flex-col h-full group">

                    <div class="flex justify-between items-start mb-4">
                        <div class="flex gap-1">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <svg class="w-4 h-4 <?= $i <= $review->rating ? 'text-accent fill-current' : 'text-gray-200' ?>"
                                    viewBox="0 0 20 20">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            <?php endfor; ?>
                        </div>
                        <span
                            class="text-[10px] font-bold uppercase tracking-wider text-muted bg-gray-50 px-2 py-1 rounded border border-border">
                            <?php
                            $date = $review->published_at ?? $review->created_at;
                            if (!empty($date) && date('Y', strtotime((string) $date)) != '1970' && substr((string) $date, 0, 4) != '-000') {
                                echo date('d/m/Y', strtotime((string) $date));
                            } else {
                                echo 'Récemment';
                            }
                            ?>
                        </span>
                    </div>

                    <div class="mb-4">
                        <p class="text-xs text-muted uppercase font-bold tracking-wide mb-1">Produit concerné</p>
                        <?php if (empty($review->product_deleted_at)): ?>
                            <a href="<?= site_url('product/' . $review->product_alias) ?>"
                                class="font-serif font-bold text-lg text-primary truncate hover:text-accent transition block">
                                <?= esc($review->product_name ?? 'Produit #' . $review->product_id) ?>
                            </a>
                        <?php else: ?>
                            <h3 class="font-serif font-bold text-lg text-gray-400 truncate"
                                title="Ce produit n'est plus disponible">
                                Produit supprimé : <?= esc($review->product_name ?? '') ?> #<?= $review->product_id ?>
                            </h3>
                        <?php endif; ?>
                    </div>

                    <div class="flex-1">
                        <p class="text-muted text-sm leading-relaxed italic relative pl-4 border-l-2 border-accent/30">
                            "<?= esc($review->comment) ?>"
                        </p>
                    </div>

                    <div class="mt-6 pt-4 border-t border-border flex justify-between items-center">
                        <?php
                        $status = $review->moderation_status ?? 'PENDING';
                        $statusInfo = match ($status) {
                            default => ['class' => 'text-green-600 bg-green-50 border-green-100', 'label' => 'Publié'],
                            'REFUSED' => ['class' => 'text-red-600 bg-red-50 border-red-100', 'label' => 'Refusé'],
                        };
                        ?>
                        <div class="flex items-center gap-2">
                            <span
                                class="inline-flex items-center px-2 py-1 rounded text-xs font-bold border <?= $statusInfo['class'] ?>">
                                <?= $statusInfo['label'] ?>
                            </span>
                            <?php if (empty($review->product_deleted_at)): ?>
                                <a href="<?= site_url('client/reviews/' . $review->product_id . '/edit/') ?>"
                                    class="text-xs text-muted hover:text-accent font-bold underline decoration-dotted">Modifier</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="mt-10 flex justify-center">
            <?= $pager->links('default', 'tailwind') ?>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>

