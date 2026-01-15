<?= $this->extend('layouts/client_section') ?>

<?= $this->section('header_content') ?>
<div class="flex items-center gap-2">
    <a href="<?= site_url('client/orders') ?>"
        class="text-sm text-muted hover:text-primary transition flex items-center gap-1 font-bold">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
        Retour
    </a>
</div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="max-w-2xl mx-auto">

    <?php if (session()->has('errors')): ?>
        <div class="bg-red-50 border border-red-200 text-red-600 p-4 rounded-xl text-sm mb-6">
            <ul class="list-disc list-inside">
                <?php foreach (session('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-2xl shadow-sm border border-border overflow-hidden">

        <!-- Product Recap -->
        <div class="p-6 border-b border-border bg-gray-50/50 flex items-center gap-4">
            <div
                class="w-16 h-16 bg-white rounded-lg border border-border flex items-center justify-center overflow-hidden shrink-0">
                <?php if (!empty($product->image)): ?>
                    <img src="<?= base_url('uploads/products/' . $product->image) ?>" class="w-full h-full object-cover">
                <?php else: ?>
                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                <?php endif; ?>
            </div>
            <div>
                <h3 class="font-bold text-primary text-lg leading-tight"><?= esc($product->title) ?></h3>
                <p class="text-sm text-muted mt-1"><?= esc($product->short_description ?? 'Produit #' . $product->id) ?>
                </p>
            </div>
        </div>

        <form action="<?= site_url('client/reviews/update') ?>" method="post" class="p-8 space-y-8">
            <?= csrf_field() ?>
            <input type="hidden" name="product_id" value="<?= $product->id ?>">

            <!-- Rating System -->
            <div class="flex flex-col items-center">
                <label class="block text-sm font-bold text-gray-700 mb-4">Note globale <span
                        class="text-red-500">*</span></label>
                <div class="flex flex-row-reverse justify-center gap-1 group">
                    <?php
                    $currentRating = old('rating') ?? ($existingReview->rating ?? 0);
                    for ($i = 5; $i >= 1; $i--): ?>
                        <input type="radio" name="rating" id="star<?= $i ?>" value="<?= $i ?>" class="peer sr-only" required
                            <?= ($currentRating == $i) ? 'checked' : '' ?>>
                        <label for="star<?= $i ?>"
                            class="cursor-pointer text-gray-200 peer-checked:text-accent peer-hover:text-accent hover:text-accent transition-colors duration-150">
                            <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        </label>
                    <?php endfor; ?>
                </div>
                <style>
                    .group:hover label {
                        @apply text-gray-200;
                    }

                    label:hover~label {
                        color: #f59e0b;
                    }

                    input:checked~label {
                        color: #f59e0b;
                    }
                </style>
                <p class="text-sm text-muted mt-2">Sélectionnez le nombre d'étoiles.</p>
            </div>

            <!-- Comment -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Votre commentaire <span
                        class="text-red-500">*</span></label>
                <textarea name="comment" rows="6"
                    class="w-full rounded-xl border border-border bg-gray-50 focus:bg-white focus:ring-accent focus:border-accent p-4 transition-all"
                    placeholder="Qu'avez-vous pensé de ce produit ? (Qualité, livraison, conformité...)" required
                    minlength="5"><?= esc(old('comment') ?? ($existingReview->comment ?? '')) ?></textarea>
                <p class="text-xs text-muted mt-1 text-right">Minimum 5 caractères</p>
            </div>

            <div class="pt-4">
                <button type="submit"
                    class="w-full py-3.5 bg-primary text-white font-bold rounded-xl hover:bg-gray-800 transition shadow-lg shadow-primary/20 flex items-center justify-center gap-2">
                    <span><?= isset($existingReview) ? 'Mettre à jour mon avis' : 'Publier mon avis' ?></span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                </button>
            </div>

        </form>
    </div>
</div>

<?= $this->endSection() ?>

