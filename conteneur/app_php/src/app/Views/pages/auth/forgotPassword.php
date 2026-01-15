<?= $this->extend("layouts/" . ($layout ?? "default")) ?>

<?= $this->section("content") ?>

<section class="min-h-screen flex items-center justify-center bg-body p-4">
    <div
        class="w-full max-w-md bg-white p-8 rounded-custom shadow-md-custom border border-border-custom transform -translate-y-8">
        <div class="text-center mb-6">
            <h1 class="font-serif text-2xl text-primary mb-2">Réinitialiser le mot de passe</h1>
            <p class="font-sans text-muted text-sm">Entrez votre adresse email pour recevoir le lien de réinitialisation
            </p>
        </div>

        <?php if (isset($success) && $success): ?>
            <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 text-sm rounded-r-md">
                Email bien envoyé. Vérifiez votre boîte de réception.
            </div>
        <?php endif; ?>

        <form action="<?= base_url('/auth/forgot') ?>" method="POST" class="space-y-4">
            <?= csrf_field() ?>
            <div>
                <label for="email" class="block text-sm font-medium text-main mb-1 italic">Adresse email</label>
                <input type="email" name="email" id="email" required
                    class="w-full px-4 py-3 rounded-xl border border-border-custom focus:ring-2 focus:ring-accent focus:border-accent outline-none transition-all font-sans"
                    placeholder="votre@mail.com">
            </div>

            <div class="flex gap-3">
                <button type="submit"
                    class="flex-1 bg-accent text-white font-semibold py-3 rounded-xl text-center flex items-center justify-center">Envoyer
                    le lien</button>
                <a href="<?= base_url('/auth/login') ?>"
                    class="flex-1 border border-border-custom py-3 rounded-xl text-center flex items-center justify-center">Annuler</a>
            </div>
        </form>

    </div>
</section>

<?= $this->endSection() ?>

