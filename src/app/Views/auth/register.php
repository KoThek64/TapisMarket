<?= $this->extend("layouts/" . ($layout ?? "default")) ?>

<?= $this->section("content") ?>

<section class="min-h-screen flex items-center justify-center bg-body p-4">
    <div class="w-full max-auto max-w-md bg-white p-8 rounded-custom shadow-md-custom border border-border-custom my-8">

        <div class="text-center mb-8">
            <h1 class="font-serif text-4xl text-primary mb-2">Créer un compte</h1>
            <p class="font-sans text-muted">Rejoignez-nous et découvrez nos collections.</p>
        </div>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 text-sm rounded-r-md">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('auth/register') ?>" method="post" class="space-y-5">
            <?= csrf_field() ?>

            <!-- Prénom & Nom -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="firstname" class="block text-sm font-medium text-primary mb-1 italic">Prénom</label>
                    <input type="text" name="firstname" id="firstname" class="w-full px-4 py-3 rounded-xl border border-border-custom focus:ring-2 focus:ring-accent focus:border-accent outline-none transition-all font-sans" placeholder="Jean" value="<?= old('firstname') ?>" required>
                </div>
                <div>
                    <label for="lastname" class="block text-sm font-medium text-primary mb-1 italic">Nom</label>
                    <input type="text" name="lastname" id="lastname" class="w-full px-4 py-3 rounded-xl border border-border-custom focus:ring-2 focus:ring-accent focus:border-accent outline-none transition-all font-sans" placeholder="Dupont" value="<?= old('lastname') ?>" required>
                </div>
            </div>

            <!-- Role Selection -->
            <div>
                <label class="block text-sm font-medium text-primary mb-1 italic">Vous êtes ?</label>
                <div class="flex gap-4 p-1 bg-gray-50 rounded-xl border border-border-custom">
                    <label class="flex items-center gap-2 cursor-pointer flex-1 justify-center py-2 rounded-lg hover:bg-white transition-all shadow-sm">
                        <input type="radio" name="role" value="client" id="role_client" class="accent-accent w-4 h-4" <?php if (old('role') !== 'vendeur') echo 'checked'; ?>>
                        <span class="font-sans text-sm font-medium">Client</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer flex-1 justify-center py-2 rounded-lg hover:bg-white transition-all shadow-sm">
                        <input type="radio" name="role" value="vendeur" id="role_vendeur" class="accent-accent w-4 h-4" <?php if (old('role') === 'vendeur') echo 'checked'; ?>>
                        <span class="font-sans text-sm font-medium">Vendeur</span>
                    </label>
                </div>
            </div>

            <!-- Seller Fields -->
            <div id="seller-fields" class="space-y-4 border-l-4 border-accent pl-4 py-2 <?php if (old('role') !== 'vendeur') echo 'hidden'; ?>">
                <h3 class="font-serif text-lg text-accent font-semibold">Informations Vendeur</h3>
                <div>
                    <label for="shop_name" class="block text-sm font-medium text-primary mb-1 italic">Nom de la boutique</label>
                    <input type="text" name="shop_name" id="shop_name" class="w-full px-4 py-3 rounded-xl border border-border-custom focus:ring-2 focus:ring-accent focus:border-accent outline-none transition-all font-sans bg-white" placeholder="Ma Boutique de Tapis" value="<?= old('shop_name') ?>">
                </div>
                <div>
                    <label for="siret" class="block text-sm font-medium text-primary mb-1 italic">Numéro SIRET</label>
                    <input type="text" name="siret" id="siret" class="w-full px-4 py-3 rounded-xl border border-border-custom focus:ring-2 focus:ring-accent focus:border-accent outline-none transition-all font-sans bg-white" placeholder="12345678900012" value="<?= old('siret') ?>" pattern="[0-9]{14}" minlength="14" maxlength="14" title="Le numéro SIRET doit comporter exactement 14 chiffres.">
                </div>
                <div>
                    <label for="shop_description" class="block text-sm font-medium text-primary mb-1 italic">Description</label>
                    <textarea name="shop_description" id="shop_description" class="w-full px-4 py-3 rounded-xl border border-border-custom focus:ring-2 focus:ring-accent focus:border-accent outline-none transition-all font-sans bg-white" placeholder="Spécialiste des tapis d'orient..." rows="3"><?= old('shop_description') ?></textarea>
                </div>
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-primary mb-1 italic">Adresse email</label>
                <input type="email" name="email" id="email" class="w-full px-4 py-3 rounded-xl border border-border-custom focus:ring-2 focus:ring-accent focus:border-accent outline-none transition-all font-sans" placeholder="exemple@mail.com" value="<?= old('email') ?>" required>
            </div>

            <div>
                <label for="phone" class="block text-sm font-medium text-primary mb-1 italic">Numéron de téléphone</label>
                <input type="text" name="phone" id="phone" class="w-full px-4 py-3 rounded-xl border border-border-custom focus:ring-2 focus:ring-accent focus:border-accent outline-none transition-all font-sans" placeholder="0123456789" value="<?= old('phone') ?>" required>
            </div>

            <!-- Mot de passe -->
            <div>
                <label for="password" class="block text-sm font-medium text-primary mb-1 italic">Mot de passe</label>
                <input type="password" name="password" id="password" class="w-full px-4 py-3 rounded-xl border border-border-custom focus:ring-2 focus:ring-accent focus:border-accent outline-none transition-all font-sans" placeholder="••••••••" required minlength="8" title="Le mot de passe doit comporter au moins 8 caractères.">
            </div>

            <!-- Confirmation Mot de passe -->
            <div>
                <label for="password_confirm" class="block text-sm font-medium text-primary mb-1 italic">Confirmer le mot de passe</label>
                <input type="password" name="password_confirm" id="password_confirm" class="w-full px-4 py-3 rounded-xl border border-border-custom focus:ring-2 focus:ring-accent focus:border-accent outline-none transition-all font-sans" placeholder="••••••••" required minlength="8" title="Le mot de passe doit comporter au moins 8 caractères.">
            </div>

            <button type="submit" class="w-full bg-primary hover:bg-primary-hover text-white font-sans font-semibold py-4 rounded-xl shadow-lg transition-all transform hover:-translate-y-0.5 active:scale-95">
                S'inscrire
            </button>
        </form>

        <div class="mt-8 pt-6 border-t border-border-custom text-center">
            <p class="text-sm text-muted">
                Vous avez déjà un compte ?
                <a href="<?= base_url('auth/login') ?>" class="text-accent font-semibold hover:underline">Se connecter</a>
            </p>
        </div>
    </div>
</section>

<script>
    // Fais avec l'ia Gemini pour gérer le choix client ou vendeur
    // je lui ai demandé d'ajouter des commentaires pour comprendre la logique
    document.addEventListener('DOMContentLoaded', function() {
        // Récupération des éléments du DOM
        const roleRadios = document.querySelectorAll('input[name="role"]');
        const sellerFields = document.getElementById('seller-fields');
        const sellerInputs = sellerFields.querySelectorAll('input, textarea');

        // Fonction pour basculer l'affichage des champs vendeur
        function toggleSellerFields() {
            // Vérifie si "vendeur" est sélectionné
            const isSeller = document.querySelector('input[name="role"]:checked').value === 'vendeur';

            if (isSeller) {
                // Affiche la section vendeur et rend les champs requis
                sellerFields.classList.remove('hidden');
                sellerInputs.forEach(input => input.setAttribute('required', 'required'));
            } else {
                // Cache la section vendeur et enlève le requis
                sellerFields.classList.add('hidden');
                sellerInputs.forEach(input => input.removeAttribute('required'));
            }
        }

        // Écoute les changements sur les boutons radio
        roleRadios.forEach(radio => {
            radio.addEventListener('change', toggleSellerFields);
        });

        // Applique l'état correct au chargement de la page
        toggleSellerFields();
    });
</script>

<?= $this->endSection() ?>