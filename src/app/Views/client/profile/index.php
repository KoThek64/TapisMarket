<?= $this->extend('layouts/client_section') ?>

<?= $this->section('header_content') ?>
    <a href="<?= site_url('/') ?>" class="px-4 py-2 border border-border rounded-xl hover:bg-white transition text-sm font-bold flex items-center gap-2 bg-gray-50/50 text-gray-700">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Retour à la boutique
    </a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

    <div class="max-w-4xl mx-auto grid md:grid-cols-3 gap-8">
        
        <!-- Sidebar Navigation (Optional, skipped for now to keep simple) -->

        <!-- Main Form -->
        <div class="md:col-span-3 space-y-6">
            
            <?php if (session()->getFlashdata('message')): ?>
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl">
                    <?= session()->getFlashdata('message') ?>
                </div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('error')): ?>
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <div class="bg-white rounded-2xl shadow-lg shadow-gray-200/50 border border-gray-100 p-8 ring-1 ring-gray-100">
                <h3 class="font-serif font-bold text-2xl text-primary mb-8 border-b-2 border-gray-100 pb-4">Informations du compte</h3>
                
                <form action="<?= site_url('client/profile/update') ?>" method="post" class="space-y-6">
                    <?= csrf_field() ?>
                    
                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-gray-700">Prénom</label>
                            <input type="text" name="firstname" value="<?= esc($user->firstname) ?>" class="w-full rounded-xl border-gray-200 bg-gray-50/50 focus:bg-white focus:border-accent focus:ring-accent transition-all duration-300 p-3 shadow-sm" required>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-gray-700">Nom</label>
                            <input type="text" name="lastname" value="<?= esc($user->lastname) ?>" class="w-full rounded-xl border-gray-200 bg-gray-50/50 focus:bg-white focus:border-accent focus:ring-accent transition-all duration-300 p-3 shadow-sm" required>
                        </div>
                    </div>
                    
                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-gray-700">Numéro de téléphone</label>
                            <input type="tel" name="phone" value="<?= esc($user->phone ?? '') ?>" class="w-full rounded-xl border-gray-200 bg-gray-50/50 focus:bg-white focus:border-accent focus:ring-accent transition-all duration-300 p-3 shadow-sm" placeholder="06 12 34 56 78">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-gray-700">Date d'anniversaire</label>
                            <input type="date" name="birth_date" value="<?= esc($user->birth_date ? $user->birth_date->format('Y-m-d') : '') ?>" class="w-full rounded-xl border-gray-200 bg-gray-50/50 focus:bg-white focus:border-accent focus:ring-accent transition-all duration-300 p-3 shadow-sm">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-gray-700">Email</label>
                        <div class="relative">
                            <input type="email" name="email" value="<?= esc($user->email) ?>" class="w-full rounded-xl border-gray-200 bg-gray-100 text-gray-500 cursor-not-allowed p-3 shadow-sm" readonly title="Contactez le support pour changer l'email">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                        </div>
                         <p class="text-xs text-muted flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            L'email ne peut pas être modifié directement.
                        </p>
                    </div>

                    <div class="pt-6 border-t border-gray-100 flex justify-end">
                        <button type="submit" class="px-8 py-3 bg-primary text-white font-bold rounded-xl hover:bg-gray-800 transition shadow-lg shadow-gray-200 transform hover:-translate-y-0.5">
                            Enregistrer les modifications
                        </button>
                    </div>
                </form>
            </div>

            <!-- Addresses Section -->
            <div class="bg-white rounded-2xl shadow-lg shadow-gray-200/50 border border-gray-100 p-8 ring-1 ring-gray-100">
                <div class="flex justify-between items-center mb-8 border-b-2 border-gray-100 pb-4">
                    <h3 class="font-serif font-bold text-2xl text-primary">Mes Adresses</h3>
                    <a href="<?= site_url('client/addresses/new') ?>" class="text-xs font-bold uppercase text-accent hover:text-accent-hover tracking-wide border border-accent/20 px-3 py-1.5 rounded-lg hover:bg-accent/5 transition">
                        + Nouvelle adresse
                    </a>
                </div>

                <?php if (empty($addresses)): ?>
                    <p class="text-muted text-center py-4">Aucune adresse enregistrée.</p>
                <?php else: ?>
                    <div class="grid md:grid-cols-2 gap-4">
                        <?php foreach($addresses as $addr): ?>
                            <div class="p-4 rounded-xl border border-border hover:border-accent transition group relative">
                                <div class="font-bold text-primary mb-1"><?= esc(trim(($addr->number ?? '') . ' ' . $addr->street)) ?></div>
                                <div class="text-sm text-gray-600 space-y-0.5">
                                    <p><?= esc($addr->postal_code) ?> <?= esc($addr->city) ?></p>
                                    <p><?= esc($addr->country) ?></p>
                                    <?php if(!empty($addr->contact_phone)): ?><p class="text-xs text-muted mt-2">Tel: <?= esc($addr->contact_phone) ?></p><?php endif; ?>
                                </div>
                                <div class="mt-3 pt-3 border-t border-dashed border-border flex justify-end gap-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                     <a href="<?= site_url('client/addresses/' . $addr->id . '/edit') ?>" class="text-xs font-bold text-muted hover:text-accent">Modifier</a>
                                     <a href="<?= site_url('client/addresses/' . $addr->id . '/delete') ?>" class="text-xs font-bold text-red-400 hover:text-red-600" onclick="return confirm('Supprimer cette adresse ?')">Supprimer</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

<?= $this->endSection() ?>
