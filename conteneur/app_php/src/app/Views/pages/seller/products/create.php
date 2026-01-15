<?= $this->extend('layouts/seller_section') ?>

<?= $this->section('header_content') ?>
<a href="<?= base_url('seller/products') ?>"
    class="px-4 py-2 rounded-xl bg-white/50 backdrop-blur-sm border border-gray-200 text-gray-700 hover:bg-white transition flex items-center gap-2 shadow-sm font-bold text-sm">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
    </svg>
    <span>Retour au catalogue</span>
</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="max-w-4xl mx-auto space-y-8">

    <form action="<?= base_url('seller/products') ?>" method="POST" class="space-y-8">
        <?= csrf_field() ?>

        <div class="grid lg:grid-cols-3 gap-8">

            <!-- Main Info -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-border p-6 md:p-8 space-y-6">
                    <h3 class="font-bold text-lg text-primary border-b border-border pb-2">Informations Principales</h3>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nom du produit <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="title" value="<?= old('title') ?>"
                            class="w-full rounded-lg border-border focus:ring-accent focus:border-accent text-sm py-2.5 px-3 bg-gray-50/50"
                            required placeholder="Ex: Chaise Vintage Scandinave">
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Prix (€) <span
                                    class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="number" step="0.01" name="price" value="<?= old('price') ?>"
                                    class="w-full rounded-lg border-border focus:ring-accent focus:border-accent text-sm py-2.5 pl-3 pr-8 bg-gray-50/50"
                                    required placeholder="0.00">
                                <div
                                    class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-500">
                                    €</div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Stock initial <span
                                    class="text-red-500">*</span></label>
                            <input type="number" name="stock_available" value="<?= old('stock_available', 1) ?>"
                                class="w-full rounded-lg border-border focus:ring-accent focus:border-accent text-sm py-2.5 px-3 bg-gray-50/50"
                                required min="1">
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Dimensions <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="dimensions" value="<?= old('dimensions') ?>"
                                class="w-full rounded-lg border-border focus:ring-accent focus:border-accent text-sm py-2.5 px-3 bg-gray-50/50"
                                required placeholder="Ex: 20x30x10 cm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Matériaux</label>
                            <input type="text" name="material" value="<?= old('material') ?>"
                                class="w-full rounded-lg border-border focus:ring-accent focus:border-accent text-sm py-2.5 px-3 bg-gray-50/50"
                                placeholder="Ex: Bois, Métal...">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Catégorie <span
                                class="text-red-500">*</span></label>
                        <select name="category_id"
                            class="w-full rounded-lg border-border focus:ring-accent focus:border-accent text-sm py-2.5 px-3 bg-gray-50/50"
                            required>
                            <option value="">-- Sélectionner une catégorie --</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat->id ?>" <?= (old('category_id') == $cat->id) ? 'selected' : '' ?>>
                                    <?= esc($cat->name) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-border p-6 md:p-8 space-y-6">
                    <h3 class="font-bold text-lg text-primary border-b border-border pb-2">Description</h3>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description courte</label>
                        <textarea name="short_description" rows="2"
                            class="w-full rounded-lg border-border focus:ring-accent focus:border-accent text-sm py-2 px-3 bg-gray-50/50"
                            placeholder="Une phrase d'accroche pour les listes..."><?= old('short_description') ?></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description complète</label>
                        <textarea name="long_description" rows="5"
                            class="w-full rounded-lg border-border focus:ring-accent focus:border-accent text-sm py-2 px-3 bg-gray-50/50"
                            placeholder="Tous les détails de votre produit..."><?= old('long_description') ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-border p-6 space-y-4">
                    <h3 class="font-bold text-lg text-primary">Publication</h3>
                    <p class="text-sm text-muted">Le produit sera créé avec le statut <span
                            class="font-bold text-amber-600">En attente</span> de validation administrateur.</p>

                    <hr class="border-border">

                    <div class="flex flex-col gap-3">
                        <a href="<?= base_url('seller/products') ?>"
                            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg text-center text-sm font-medium hover:bg-gray-50 transition-colors">
                            Annuler
                        </a>
                        <button type="submit"
                            class="px-4 py-2 bg-primary text-white rounded-lg text-sm font-medium hover:bg-gray-800 transition-colors shadow-lg shadow-primary/20">
                            Continuer vers les Photos &rarr;
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>

<?= $this->endSection() ?>

