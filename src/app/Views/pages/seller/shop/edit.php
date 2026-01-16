<?= $this->extend('layouts/seller_section') ?>

<?= $this->section('header_content') ?>
<a href="<?= site_url('seller/shop') ?>"
    class="px-4 py-2 rounded-xl bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 transition flex items-center gap-2 shadow-sm font-bold text-sm">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
    </svg>
    <span>Retour</span>
</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="max-w-4xl mx-auto">

    <div class="bg-white rounded-2xl shadow-sm border border-border p-6">
        <form action="<?= site_url('seller/shop/update') ?>" method="post" class="space-y-6">
            <?= csrf_field() ?>

            <!-- Informations générales -->
            <div>
                <h3 class="text-lg font-bold mb-4">Informations du magasin</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="shop_name" class="block text-sm font-medium text-gray-700 mb-1">Nom du
                            magasin</label>
                        <input type="text" name="shop_name" id="shop_name"
                            value="<?= old('shop_name', $shop->shop_name ?? '') ?>"
                            class="w-full rounded-xl border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent/20 transition">
                    </div>

                    <div>
                        <label for="siret" class="block text-sm font-medium text-gray-700 mb-1">Numéro SIRET (Non modifiable)</label>
                        <input type="text" name="siret" id="siret" value="<?= old('siret', $shop->siret ?? '') ?>"
                            class="w-full rounded-xl border-gray-300 shadow-sm bg-gray-100 text-gray-500 cursor-not-allowed font-mono" readonly>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div>
                <label for="shop_description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="shop_description" id="shop_description" rows="4"
                    class="w-full rounded-xl border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent/20 transition"><?= old('shop_description', $shop->shop_description ?? '') ?></textarea>
                <p class="text-xs text-muted mt-1">Présentez votre boutique à vos clients.</p>
            </div>

            <!-- Coordonnées -->
            <div>
                <h3 class="text-lg font-bold mb-4">Coordonnées</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email de contact</label>
                        <input type="email" name="email" id="email" value="<?= old('email', $shop->email ?? '') ?>"
                            class="w-full rounded-xl border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent/20 transition">
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="pt-6 border-t border-gray-100 flex justify-end gap-3">
                <a href="<?= site_url('seller/shop') ?>"
                    class="px-5 py-2.5 rounded-xl border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 transition font-medium">
                    Annuler
                </a>
                <button type="submit"
                    class="px-5 py-2.5 rounded-xl bg-accent text-white hover:bg-accent-hover transition font-bold shadow-sm flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Enregistrer</span>
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

