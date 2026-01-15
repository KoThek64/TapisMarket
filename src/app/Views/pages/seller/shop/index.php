<?= $this->extend('layouts/seller_section') ?>

<?= $this->section('header_content') ?>
<a href="<?= site_url('seller/shop/edit') ?>"
    class="px-4 py-2 rounded-xl bg-accent text-white hover:bg-accent-hover transition flex items-center gap-2 shadow-sm font-bold text-sm">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
    </svg>
    <span>Modifier</span>
</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="max-w-4xl mx-auto space-y-8">

    <div class="bg-white rounded-2xl shadow-sm border border-border p-6 space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold">Informations</h2>
            <?php if (isset($shop->status)):
                $status = $shop->status;
                $label = null;
                $cls = '';
                if (defined('SELLER_PENDING') && $status === SELLER_PENDING) {
                    $label = 'En attente';
                    $cls = 'bg-yellow-100 text-yellow-800';
                }
                if (defined('SELLER_VALIDATED') && $status === SELLER_VALIDATED) {
                    $label = 'Validé';
                    $cls = 'bg-green-100 text-green-800';
                }
                if (defined('SELLER_REFUSED') && $status === SELLER_REFUSED) {
                    $label = 'Refusé';
                    $cls = 'bg-red-100 text-red-800';
                }
                if (defined('SELLER_SUSPENDED') && $status === SELLER_SUSPENDED) {
                    $label = 'Suspendu';
                    $cls = 'bg-orange-100 text-orange-800';
                }
                if ($label): ?>
                    <div class="text-right">
                        <span class="px-3 py-1 rounded-full text-xs font-bold <?= $cls ?>"><?= esc($label) ?></span>
                        <?php if ($status === SELLER_REFUSED && !empty($shop->refusal_reason)): ?>
                            <p class="text-xs text-red-600 mt-1 font-semibold max-w-[200px]">
                                Motif : <?= esc($shop->refusal_reason) ?>
                            </p>
                        <?php endif; ?>
                    </div>
                <?php endif; endif; ?>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-sm text-muted">Nom du magasin</p>
                <p class="text-lg font-semibold"><?= esc($shop->shop_name ?? '') ?></p>
            </div>
            <div>
                <p class="text-sm text-muted">SIRET</p>
                <p class="text-lg font-mono">
                    <?= esc(method_exists($shop, 'getFormattedSiret') ? $shop->getFormattedSiret() : ($shop->siret ?? '')) ?>
                </p>
            </div>
            <div class="md:col-span-2">
                <p class="text-sm text-muted">Description</p>
                <p class="text-sm whitespace-pre-line"><?= esc($shop->shop_description ?? '') ?></p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-border p-6">
        <h3 class="text-lg font-bold mb-3">Coordonnées</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-sm text-muted">Email</p>
                <p class="text-sm"><?= esc($shop->email ?? '') ?></p>
            </div>
            <div>
                <p class="text-sm text-muted">Inscrit le</p>
                <p class="text-sm"><?= esc($shop->created_at ?? '') ?></p>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

