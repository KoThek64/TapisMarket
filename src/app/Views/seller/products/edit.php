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
    <div class="space-y-8">

        <!-- Toolbar -->
        <div class="flex flex-col md:flex-row justify-between md:items-center gap-4 border-b border-border pb-6">
            <div>
                <p class="text-sm text-muted">Modification du produit #<?= esc($product->id) ?></p>
            </div>
            <div class="flex items-center gap-3">
                <form action="<?= base_url('seller/products/' . $product->id) ?>" method="POST">
                    <?= csrf_field() ?>
                    <input type="hidden" name="_method" value="DELETE">
                    <button
                        class="px-4 py-2 text-sm font-medium text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-colors border border-red-100 flex items-center justify-center">
                        Supprimer
                    </button>
                </form>
                <button onclick="document.getElementById('productDataForm').submit()"
                    class="px-6 py-2 bg-primary text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-shadow shadow-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                        </path>
                    </svg>
                    Enregistrer
                </button>
            </div>
        </div>

        <form id="productDataForm" action="<?= base_url('seller/products/' . $product->id) ?>" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="_method" value="PUT">

            <div class="grid lg:grid-cols-3 gap-8">

                <!-- Left Column: Main Info -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Basic Info Card -->
                    <div class="bg-white rounded-2xl shadow-sm border border-border p-6 md:p-8 space-y-6">
                        <h3 class="font-bold text-lg text-primary border-b border-border pb-2">Informations Générales
                        </h3>

                        <!-- Title -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nom du produit</label>
                            <input type="text" name="title" value="<?= esc($product->title ?? $product->name) ?>"
                                class="w-full rounded-lg border-border focus:ring-accent focus:border-accent text-sm py-2.5 px-3 bg-gray-50/50"
                                required>
                            <p class="text-xs text-muted mt-1">Le nom tel qu'il apparaîtra sur la boutique.</p>
                        </div>

                        <div class="grid md:grid-cols-2 gap-6">
                            <!-- Price -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Prix (€)</label>
                                <div class="relative">
                                    <input type="number" step="0.01" name="price" value="<?= esc($product->price) ?>"
                                        class="w-full rounded-lg border-border focus:ring-accent focus:border-accent text-sm py-2.5 pl-3 pr-8 bg-gray-50/50"
                                        required>
                                    <div
                                        class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-500">
                                        €</div>
                                </div>
                            </div>
                            <!-- Stock -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Stock disponible</label>
                                <input type="number" name="stock_available"
                                    value="<?= esc($product->stock_available ?? 0) ?>"
                                    class="w-full rounded-lg border-border focus:ring-accent focus:border-accent text-sm py-2.5 px-3 bg-gray-50/50"
                                    required min="0">
                            </div>
                        </div>

                        <!-- Category -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Catégorie</label>
                            <select name="category_id"
                                class="w-full rounded-lg border-border focus:ring-accent focus:border-accent text-sm py-2.5 px-3 bg-gray-50/50">
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat->id ?>" <?= ($cat->id == $product->category_id) ? 'selected' : '' ?>>
                                        <?= esc($cat->name) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Description Card -->
                    <div class="bg-white rounded-2xl shadow-sm border border-border p-6 md:p-8 space-y-6">
                        <h3 class="font-bold text-lg text-primary border-b border-border pb-2">Description & Détails
                        </h3>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description courte
                                (Accroche)</label>
                            <textarea name="short_description" rows="2"
                                class="w-full rounded-lg border-border focus:ring-accent focus:border-accent text-sm py-2 px-3 bg-gray-50/50"><?= esc($product->short_description) ?></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description complète</label>
                            <textarea name="long_description" rows="6"
                                class="w-full rounded-lg border-border focus:ring-accent focus:border-accent text-sm py-2 px-3 bg-gray-50/50"><?= esc($product->long_description ?? $product->description) ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Specs & Media -->
                <div class="space-y-6">
                    <!-- Specs Card -->
                    <div class="bg-white rounded-2xl shadow-sm border border-border p-6 md:p-8 space-y-6">
                        <h3 class="font-bold text-lg text-primary border-b border-border pb-2">Caractéristiques</h3>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Dimensions</label>
                            <input type="text" name="dimensions" value="<?= esc($product->dimensions) ?>"
                                placeholder="Ex: 20x30x10 cm"
                                class="w-full rounded-lg border-border focus:ring-accent focus:border-accent text-sm py-2.5 px-3 bg-gray-50/50">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Matériaux</label>
                            <input type="text" name="material" value="<?= esc($product->material) ?>"
                                placeholder="Ex: Bois de chêne, Cuir"
                                class="w-full rounded-lg border-border focus:ring-accent focus:border-accent text-sm py-2.5 px-3 bg-gray-50/50">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">État (Admin)</label>
                            <?php
                            $statusLabel = 'Refusé';
                            if ($product->product_status == STATUS_PENDING)
                                $statusLabel = 'En attente';
                            elseif ($product->product_status == STATUS_APPROVED)
                                $statusLabel = 'Validé';
                            ?>
                            <div class="flex flex-col gap-2">
                                <input type="text" disabled value="<?= esc($statusLabel) ?>"
                                    class="w-full rounded-lg border border-gray-200 bg-gray-100 text-gray-500 text-sm py-2.5 px-3 cursor-not-allowed">

                                <?php if ($product->product_status == STATUS_REFUSED && !empty($product->refusal_reason)): ?>
                                    <div
                                        class="bg-red-50 border border-red-200 rounded-lg p-3 text-sm text-red-700 animate-pulse">
                                        <span class="font-bold block mb-1">⚠️ Motif du refus :</span>
                                        <?= esc($product->refusal_reason) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>

    <!-- Media Section (Integrated) -->
    <div class="border-t border-border pt-8">
        <!-- ID du produit pour JS -->
        <div id="product-config" data-product-id="<?= $product->id ?>"
            data-upload-url="<?= base_url("seller/products/{$product->id}/photos") ?>"></div>

        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-xl font-serif font-bold text-primary">Photos du produit</h3>
                <p class="text-sm text-muted">Vous devez avoir au moins une photo. (Max 5)</p>
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-8">
            <!-- Upload Zone -->
            <div class="bg-white rounded-2xl shadow-sm border border-border p-6 h-fit">
                <h4 class="font-bold text-primary mb-4">Ajouter des photos</h4>

                <form id="photoUploadForm"
                    class="relative border-2 border-dashed border-border rounded-xl p-8 hover:bg-gray-50 transition-colors text-center group cursor-pointer">
                    <input type="file" name="photos[]" multiple accept="image/*"
                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                        onchange="handleFileSelect(this)">

                    <div class="space-y-2 pointer-events-none">
                        <div class="mx-auto h-12 w-12 text-muted group-hover:text-accent transition-colors">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <p class="text-sm text-primary font-medium" id="fileLabel">Sélectionner ou glisser</p>
                        <p class="text-xs text-muted">PNG, JPG, WEBP</p>
                    </div>
                </form>

                <button type="button" onclick="uploadPhotos()" id="uploadBtn"
                    class="mt-4 w-full bg-primary text-white px-6 py-2.5 rounded-lg hover:bg-gray-800 transition-colors font-medium shadow-sm flex items-center justify-center gap-2">
                    <span>Envoyer</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </button>
            </div>

            <!-- Gallery Grid -->
            <div>
                <h3 class="text-lg font-semibold mb-4 mx-1 flex justify-between items-center">
                    Galerie actuelle
                    <span class="text-xs font-normal text-muted bg-gray-100 px-2 py-1 rounded"><?= count($photos) ?> /
                        5</span>
                </h3>

                <?php if (empty($photos)): ?>
                    <div
                        class="bg-gray-50 border border-dashed border-border rounded-xl p-12 text-center h-48 flex flex-col items-center justify-center text-muted">
                        <p>Aucune photo.</p>
                    </div>
                <?php else: ?>
                    <div class="grid grid-cols-2 gap-4">
                        <?php foreach ($photos as $index => $photo): ?>
                            <div
                                class="group relative bg-white rounded-xl shadow-sm border border-border overflow-hidden aspect-square hover:shadow-md transition-shadow">
                                <div
                                    class="absolute top-2 left-2 z-10 bg-black/70 backdrop-blur-sm text-white text-[10px] font-bold px-2 py-1 rounded-md">
                                    #<?= $index + 1 ?>
                                </div>
                                <img src="<?= base_url('uploads/products/' . $product->id . '/' . $photo->file_name) ?>"
                                    alt="Photo" class="w-full h-full object-cover">

                                <!-- Delete Action via JS -->
                                <div
                                    class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center p-4">
                                    <button onclick="deletePhoto(<?= $photo->id ?>)"
                                        class="bg-white text-danger hover:bg-red-50 px-3 py-2 rounded-lg text-xs font-bold shadow-sm flex items-center gap-2 transition-colors">
                                        Supr.
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div>

<!-- Scripts pour la gestion des photos -->
<script>
    // Configuration
    const PRODUCT_ID = document.getElementById('product-config').dataset.productId;
    const UPLOAD_URL = document.getElementById('product-config').dataset.uploadUrl;
    const CSRF_TOKEN = '<?= csrf_token() ?>';
    const CSRF_HASH = '<?= csrf_hash() ?>';

    function handleFileSelect(input) {
        const count = input.files.length;
        document.getElementById('fileLabel').innerText = count > 0 ?
            count + ' fichier(s) sélectionné(s)' :
            'Sélectionner ou glisser';
    }

    async function uploadPhotos() {
        const form = document.getElementById('photoUploadForm');
        const input = form.querySelector('input[type="file"]');
        const btn = document.getElementById('uploadBtn');

        if (input.files.length === 0) {
            alert('Veuillez sélectionner au moins une photo.');
            return;
        }

        const formData = new FormData();
        for (const file of input.files) {
            formData.append('photos[]', file);
        }
        // Add CSRF
        // Note: CI4 might require refreshing token after request if configured

        btn.disabled = true;
        btn.innerHTML = 'Envoi...';

        try {
            const response = await fetch(UPLOAD_URL, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': CSRF_HASH // Header method often easier
                },
                body: formData
            });

            if (response.ok) {
                window.location.reload(); // Reload to show new photos
            } else {
                const data = await response.json();
                alert('Erreur: ' + (data.errors ? data.errors.join(', ') : 'Upload échoué'));
            }
        } catch (e) {
            console.error(e);
            alert('Erreur réseau lors de l\'envoi.');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<span>Envoyer</span><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>';
        }
    }

    async function deletePhoto(photoId) {
        const url = `<?= base_url('seller/products') ?>/${PRODUCT_ID}/photos/${photoId}`;

        try {
            const response = await fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': CSRF_HASH
                }
            });

            if (response.ok) {
                window.location.reload();
            } else {
                const data = await response.json();
                alert('Erreur: ' + (data.error || 'Suppression échouée'));
            }
        } catch (e) {
            console.error(e);
            alert('Erreur réseau.');
        }
    }
</script>

<?= $this->endSection() ?>

<?= view('partials/delete_modal') ?>