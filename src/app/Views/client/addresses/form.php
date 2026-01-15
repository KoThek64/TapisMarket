<?= $this->extend('layouts/client_section') ?>

<?= $this->section('header_content') ?>
<div class="flex items-center gap-2">
    <a href="<?= site_url('client/profile') ?>"
        class="text-sm text-muted hover:text-primary transition flex items-center gap-1 font-bold">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
        Retour profil
    </a>
    <div class="h-6 w-px bg-border mx-2"></div>
    <a href="<?= site_url('/') ?>"
        class="px-4 py-2 border border-border rounded-xl hover:bg-white transition text-sm font-bold flex items-center gap-2 bg-gray-50/50 text-gray-700">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
            </path>
        </svg>
        Retour à la boutique
    </a>
</div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="max-w-2xl mx-auto">
    <div
        class="bg-white rounded-2xl shadow-lg shadow-gray-200/50 border border-gray-100 overflow-hidden ring-1 ring-gray-100">

        <?php if (session()->getFlashdata('errors')): ?>
            <div class="bg-red-50 border-b border-red-100 p-4">
                <ul class="text-sm text-red-600 list-disc list-inside">
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach ?>
                </ul>
            </div>
        <?php endif; ?>

        <form
            action="<?= isset($address) ? site_url('client/addresses/' . $address->id) : site_url('client/addresses') ?>"
            method="post" class="p-8 space-y-6">
            <?= csrf_field() ?>

            <?php if (isset($address)): ?>
                <input type="hidden" name="_method" value="PUT">
            <?php endif; ?>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-gray-700">Numéro</label>
                    <input type="text" name="number" value="<?= old('number', $address->number ?? '') ?>"
                        class="w-full rounded-xl border-gray-200 bg-gray-50/50 focus:bg-white focus:border-accent focus:ring-accent transition-all duration-300 p-3 shadow-sm"
                        placeholder="ex: 12 bis">
                </div>
                <div class="md:col-span-2 space-y-2">
                    <label class="block text-sm font-bold text-gray-700">Rue / Voie <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="street" value="<?= old('street', $address->street ?? '') ?>"
                        class="w-full rounded-xl border-gray-200 bg-gray-50/50 focus:bg-white focus:border-accent focus:ring-accent transition-all duration-300 p-3 shadow-sm"
                        required>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-gray-700">Code Postal <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="postal_code" value="<?= old('postal_code', $address->postal_code ?? '') ?>"
                        class="w-full rounded-xl border-gray-200 bg-gray-50/50 focus:bg-white focus:border-accent focus:ring-accent transition-all duration-300 p-3 shadow-sm"
                        required>
                </div>
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-gray-700">Ville <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="city" value="<?= old('city', $address->city ?? '') ?>"
                        class="w-full rounded-xl border-gray-200 bg-gray-50/50 focus:bg-white focus:border-accent focus:ring-accent transition-all duration-300 p-3 shadow-sm"
                        required>
                </div>
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-bold text-gray-700">Pays <span class="text-red-500">*</span></label>
                <input type="text" name="country" value="<?= old('country', $address->country ?? 'France') ?>"
                    class="w-full rounded-xl border-gray-200 bg-gray-50/50 focus:bg-white focus:border-accent focus:ring-accent transition-all duration-300 p-3 shadow-sm"
                    required>
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-bold text-gray-700">Téléphone de contact</label>
                <input type="tel" name="contact_phone"
                    value="<?= old('contact_phone', $address->contact_phone ?? '') ?>"
                    class="w-full rounded-xl border-gray-200 bg-gray-50/50 focus:bg-white focus:border-accent focus:ring-accent transition-all duration-300 p-3 shadow-sm">
            </div>

            <div class="pt-6 border-t border-gray-100">
                <button type="submit"
                    class="w-full py-3 bg-primary text-white font-bold rounded-xl hover:bg-gray-800 transition shadow-lg shadow-gray-200 transform hover:-translate-y-0.5">
                    <?= isset($address) ? 'Mettre à jour l\'adresse' : 'Ajouter cette adresse' ?>
                </button>
            </div>

        </form>
    </div>
</div>

<?= $this->endSection() ?>

