<?= $this->extend("layouts/" . ($layout ?? "default")) ?>

<?= $this->section("content") ?>

<section class="min-h-screen flex items-center justify-center bg-body p-4">
    <div class="w-full max-auto max-w-md bg-white p-8 rounded-custom shadow-md-custom border border-border-custom">
        
        <div class="text-center mb-8">
            <h1 class="font-serif text-4xl text-primary mb-2">Bon retour</h1>
            <p class="font-sans text-muted">Connectez-vous à votre espace personnel</p>
        </div>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 text-sm rounded-r-md">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('/auth/login') ?>" method="POST" class="space-y-6">
            <?= csrf_field() ?>

            <div>
                <label for="email" class="block text-sm font-medium text-main mb-1 italic">Adresse email</label>
                <input type="email" name="email" id="email" required 
                    class="w-full px-4 py-3 rounded-xl border border-border-custom focus:ring-2 focus:ring-accent focus:border-accent outline-none transition-all font-sans"
                    placeholder="exemple@mail.com">
            </div>

            <div>
                <div class="flex justify-between mb-1">
                    <label for="password" class="text-sm font-medium text-main italic">Mot de passe</label>
                </div>
                <input type="password" name="password" id="password" required 
                    class="w-full px-4 py-3 rounded-xl border border-border-custom focus:ring-2 focus:ring-accent focus:border-accent outline-none transition-all font-sans"
                    placeholder="••••••••">
            </div>

            <div>
                <label for="role" class="block text-sm font-medium text-main mb-1 italic">Vous êtes :</label>
                <select name="role" id="role" 
                    class="w-full px-4 py-3 rounded-xl border border-border-custom bg-white focus:ring-2 focus:ring-accent outline-none transition-all font-sans cursor-pointer">
                    <option value="client">Client particulier</option>
                    <option value="vendeur">Vendeur professionnel</option>
                    <option value="admin">Administrateur</option>
                </select>
            </div>

            <button type="submit" 
                class="w-full bg-primary hover:bg-primary-hover text-white font-sans font-semibold py-4 rounded-xl shadow-lg transition-all transform hover:-translate-y-0.5 active:scale-95">
                Se connecter
            </button>
        </form>

        <div class="mt-8 pt-6 border-t border-border-custom text-center">
            <p class="text-sm text-muted">
                Pas encore de compte ? 
                <a href="/auth/register" class="text-accent font-semibold hover:underline">Créer un compte</a>
            </p>
        </div>
    </div>
</section>

<?= $this->endSection() ?>