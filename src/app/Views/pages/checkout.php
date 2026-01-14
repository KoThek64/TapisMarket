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

                    <!-- Addresses Section -->
                    <div class="bg-white rounded-2xl border border-gray-100 p-8 mb-8 ring-1 ring-gray-100">
                        <div class="flex justify-between items-center mb-8 border-b-2 border-gray-100 pb-4">
                             <h3 class="font-serif text-xl mb-6 flex items-center gap-2">
                            <span class="text-red-500 text-base">📍</span> Mes adresses
                        </h3>
                            <button type="button" onclick="resetNewAddress()" class="text-xs font-bold uppercase text-accent hover:text-accent-hover tracking-wide border border-accent/20 px-3 py-1.5 rounded-lg hover:bg-accent/5 transition">
                                + Nouvelle adresse
                            </button>
                        </div>

                        <?php if (empty($addresses)): ?>
                            <p class="text-muted text-center py-4">Aucune adresse enregistrée.</p>
                        <?php else: ?>
                            <div class="grid md:grid-cols-2 gap-4">
                                <?php foreach ($addresses as $addr): ?>
                                    <label class="cursor-pointer p-4 rounded-xl border border-border hover:border-accent transition group relative has-[:checked]:border-accent has-[:checked]:bg-accent/5">
                                        <input type="radio" name="selected_address" value="<?= $addr->id ?>" 
                                            class="hidden"
                                            <?= old('selected_address') == $addr->id ? 'checked' : '' ?>
                                            data-address="<?= esc($addr->number . ' ' . $addr->street) ?>"
                                            data-zip="<?= esc($addr->postal_code) ?>"
                                            data-city="<?= esc($addr->city) ?>"
                                            onclick="fillAddress(this)">
                                        
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <div class="font-bold text-primary mb-1"><?= esc(trim(($addr->number ?? '') . ' ' . $addr->street)) ?></div>
                                                <div class="text-sm text-gray-600 space-y-0.5">
                                                    <p><?= esc($addr->postal_code) ?> <?= esc($addr->city) ?></p>
                                                    <p><?= esc($addr->country) ?></p>
                                                </div>
                                            </div>
                                            <div class="hidden peer-checked:block text-accent">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                        </div>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div id="delivery-address-form" class="bg-white border border-gray-100 rounded-xl p-8 mb-8 shadow-[0_2px_10px_rgba(0,0,0,0.02)]">
                        <h3 class="font-serif text-xl mb-6 flex items-center gap-2">
                            <span class="text-red-500 text-base">📍</span> Adresse de livraison
                        </h3>

                        <div class="mb-5">
                            <label class="block text-xs font-bold text-gray-900 mb-2 ml-1">Adresse complète</label>
                            <input type="text" id="input_address" name="address" class="w-full bg-input border border-gray-200 rounded-lg px-4 py-3.5 text-sm focus:outline-none focus:ring-1 focus:ring-gray-900 transition placeholder-gray-400" placeholder="123 Rue de la Paix" required value="<?= old('address') ?>">
                        </div>

                        <div class="grid grid-cols-3 gap-5 mb-5">
                            <div class="col-span-1">
                                <label class="block text-xs font-bold text-gray-900 mb-2 ml-1">Code Postal</label>
                                <input type="text" id="input_zip" inputmode="numeric" pattern="[0-9]*" name="zip" class="w-full bg-input border border-gray-200 rounded-lg px-4 py-3.5 text-sm focus:outline-none focus:ring-1 focus:ring-gray-900 transition placeholder-gray-400" placeholder="75000" required value="<?= old('zip') ?>">
                            </div>
                            <div class="col-span-2">
                                <label class="block text-xs font-bold text-gray-900 mb-2 ml-1">Ville</label>
                                <input type="text" id="input_city" name="city" class="w-full bg-input border border-gray-200 rounded-lg px-4 py-3.5 text-sm focus:outline-none focus:ring-1 focus:ring-gray-900 transition placeholder-gray-400" placeholder="Paris" required value="<?= old('city') ?>">
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="save_address" id="save_address" value="1" class="rounded border-gray-300 text-accent focus:ring-accent" <?= old('save_address') ? 'checked' : '' ?>>
                            <label for="save_address" class="text-sm text-gray-700">Enregistrer cette adresse pour mes prochaines commandes</label>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-100 rounded-xl p-8 mb-8 shadow-[0_2px_10px_rgba(0,0,0,0.02)]">
                        <h3 class="font-serif text-xl mb-6 flex items-center gap-2">
                            <span class="text-yellow-500 text-base">💳</span> Paiement
                        </h3>

                        <div class="mb-5">
                            <label class="block text-xs font-bold text-gray-900 mb-2 ml-1">Numéro de carte (16 chiffres)</label>
                            <input type="text" name="card_number" inputmode="numeric" pattern="[0-9\s]{16}" class="w-full bg-input border border-gray-200 rounded-lg px-4 py-3.5 text-sm focus:outline-none focus:ring-1 focus:ring-gray-900 transition placeholder-gray-400" placeholder="0000 0000 0000 0000" maxlength="16" required value="<?= old('card_number') ?>">
                        </div>

                        <div class="grid grid-cols-2 gap-5">
                            <div>
                                <label class="block text-xs font-bold text-gray-900 mb-2 ml-1">Expiration (MM/YY)</label>
                                <input type="text" name="card_expiry" inputmode="numeric" pattern="\d{2}/\d{2}" class="w-full bg-input border border-gray-200 rounded-lg px-4 py-3.5 text-sm focus:outline-none focus:ring-1 focus:ring-gray-900 transition placeholder-MM/YY" placeholder="MM/YY" maxlength="5" required value="<?= old('card_expiry') ?>">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-900 mb-2 ml-1">CVC (3 chiffres)</label>
                                <input type="text" name="card_cvc" inputmode="numeric" pattern="[0-9]{3}" class="w-full bg-input border border-gray-200 rounded-lg px-4 py-3.5 text-sm focus:outline-none focus:ring-1 focus:ring-gray-900 transition placeholder-gray-400" placeholder="123" maxlength="3" required value="<?= old('card_cvc') ?>">
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


<script>
    /**
     * Gestion dynamique du formulaire d'adresse (Généré par GitHub Copilot)
     * Permet de basculer entre la sélection d'une adresse existante et la saisie d'une nouvelle.
     */
    function fillAddress(element) {
        document.getElementById('input_address').value = element.getAttribute('data-address');
        document.getElementById('input_zip').value = element.getAttribute('data-zip');
        document.getElementById('input_city').value = element.getAttribute('data-city');
        
        // Décocher "enregistrer l'adresse" car elle est déjà sauvegardée
        document.getElementById('save_address').checked = false;

        // Masquer le formulaire pour une meilleure expérience utilisateur
        document.getElementById('delivery-address-form').classList.add('hidden');
    }

    function resetNewAddress() {
        // Afficher le formulaire
        const form = document.getElementById('delivery-address-form');
        form.classList.remove('hidden');
        
        // Effacer les champs
        document.getElementById('input_address').value = '';
        document.getElementById('input_zip').value = '';
        document.getElementById('input_city').value = '';
        
        // Décocher tous les boutons radio
        const radios = document.querySelectorAll('input[name="selected_address"]');
        radios.forEach(radio => radio.checked = false);

        // Réinitialiser la case à cocher
        document.getElementById('save_address').checked = false;

        // Focus et défilement fluide vers le champ adresse
        form.scrollIntoView({ behavior: 'smooth', block: 'center' });
        setTimeout(() => document.getElementById('input_address').focus(), 500);
    }

    document.addEventListener('DOMContentLoaded', function() {
        const radios = document.querySelectorAll('input[name="selected_address"]');
        const checkedRadio = document.querySelector('input[name="selected_address"]:checked');
        const addressInput = document.getElementById('input_address');

        if (radios.length > 0) {
            if (checkedRadio) {
                // Si une adresse est déjà sélectionnée (ex: retour formulaire), l'utiliser
                fillAddress(checkedRadio);
            } else if (!addressInput.value) {
                // Si chargement initial et adresses existantes, sélectionner la première par défaut
                radios[0].checked = true;
                fillAddress(radios[0]);
            }
            // Si addressInput a une valeur mais aucun radio coché, on est en mode "Nouvelle Adresse"
            // Donc on laisse le formulaire visible (état par défaut)
        }
    });
</script>

<?= $this->endSection() ?>