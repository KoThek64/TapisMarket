<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>


<main class="py-8 pb-20">
    <div class="max-w-[1200px] mx-auto px-6">

        <h2 class="font-serif text-3xl mb-8 text-gray-900">Validation de la commande</h2>

        <div class="lg:grid lg:grid-cols-12 lg:gap-16 items-start">

            <div class="lg:col-span-8">
                <?php if (isset($validation)): ?>
                    <div class="bg-red-50 border border-red-100 text-red-600 px-4 py-3 rounded-lg mb-6 text-sm font-medium">
                        <?= $validation->listErrors() ?>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('checkout/process') ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="bg-white border border-gray-100 rounded-xl p-8 mb-8 shadow-[0_2px_10px_rgba(0,0,0,0.02)]">
                        <h3 class="font-serif text-xl mb-6 flex items-center gap-2">
                            <span class="text-red-500 text-base">📍</span> Adresse de livraison
                        </h3>

                        <div class="mb-5">
                            <label class="block text-xs font-bold text-gray-900 mb-2 ml-1">Adresse complète</label>
                            <input type="text" name="address" class="w-full bg-input border border-gray-200 rounded-lg px-4 py-3.5 text-sm focus:outline-none focus:ring-1 focus:ring-gray-900 transition placeholder-gray-400" placeholder="123 Rue de la Paix" required value="<?= old('address') ?>">
                        </div>

                        <div class="grid grid-cols-3 gap-5">
                            <div class="col-span-1">
                                <label class="block text-xs font-bold text-gray-900 mb-2 ml-1">Code Postal</label>
                                <input type="text" inputmode="numeric" pattern="[0-9]*" name="zip" class="w-full bg-input border border-gray-200 rounded-lg px-4 py-3.5 text-sm focus:outline-none focus:ring-1 focus:ring-gray-900 transition placeholder-gray-400" placeholder="75000" required value="<?= old('zip') ?>">
                            </div>
                            <div class="col-span-2">
                                <label class="block text-xs font-bold text-gray-900 mb-2 ml-1">Ville</label>
                                <input type="text" name="city" class="w-full bg-input border border-gray-200 rounded-lg px-4 py-3.5 text-sm focus:outline-none focus:ring-1 focus:ring-gray-900 transition placeholder-gray-400" placeholder="Paris" required value="<?= old('city') ?>">
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-100 rounded-xl p-8 mb-8 shadow-[0_2px_10px_rgba(0,0,0,0.02)]">
                        <h3 class="font-serif text-xl mb-6 flex items-center gap-2">
                            <span class="text-yellow-500 text-base">💳</span> Paiement
                        </h3>

                        <div class="mb-5">
                            <label class="block text-xs font-bold text-gray-900 mb-2 ml-1">Numéro de carte (16 chiffres)</label>
                            <input type="text" name="card_number" inputmode="numeric" pattern="[0-9\s]{16,19}" class="w-full bg-input border border-gray-200 rounded-lg px-4 py-3.5 text-sm focus:outline-none focus:ring-1 focus:ring-gray-900 transition placeholder-gray-400" placeholder="0000 0000 0000 0000" required value="<?= old('card_number') ?>">
                        </div>

                        <div class="grid grid-cols-2 gap-5">
                            <div>
                                <label class="block text-xs font-bold text-gray-900 mb-2 ml-1">Expiration (MM/YY)</label>
                                <input type="text" name="card_expiry" inputmode="numeric" pattern="\d{2}/\d{2}" class="w-full bg-input border border-gray-200 rounded-lg px-4 py-3.5 text-sm focus:outline-none focus:ring-1 focus:ring-gray-900 transition placeholder-MM/YY" placeholder="MM/YY" maxlength="5" required value="<?= old('card_expiry') ?>">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-900 mb-2 ml-1">CVC (3 chiffres)</label>
                                <input type="text" name="card_cvc" inputmode="numeric" pattern="[0-9]{3,4}" class="w-full bg-input border border-gray-200 rounded-lg px-4 py-3.5 text-sm focus:outline-none focus:ring-1 focus:ring-gray-900 transition placeholder-gray-400" placeholder="123" maxlength="4" required value="<?= old('card_cvc') ?>">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-primary text-white py-4 rounded-full font-bold text-base hover:bg-gray-800 transition shadow-lg transform active:scale-[0.99]">
                        Confirmer le paiement (<?= $cart->getFormattedTotal() ?>)
                    </button>
                </form>
            </div>

            <div class="lg:col-span-4 mt-8 lg:mt-0">
                <div class="bg-gray-50 rounded-xl p-8 border border-gray-100 sticky top-28">
                    <h3 class="font-serif text-xl text-gray-900 mb-6">Résumé</h3>

                    <div class="space-y-4 mb-6">
                        <?php foreach ($items as $item): ?>
                            <div class="flex justify-between items-start text-sm text-gray-600">
                                <span class="pr-4 leading-relaxed"><span class="font-semibold text-gray-900"><?= $item->quantity ?>x</span> <?= esc($item->getProductName()) ?></span>
                                <span class="whitespace-nowrap font-medium text-gray-900"><?= $item->getFormattedSubtotal() ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="border-t border-gray-200 my-6"></div>

                    <div class="flex justify-between items-center">
                        <span class="font-bold text-gray-900">Total à payer</span>
                        <span class="font-bold text-xl text-gray-900"><?= $cart->getFormattedTotal() ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?= $this->endSection() ?>