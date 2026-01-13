<?= $this->extend('layouts/admin_section') ?>

<?= $this->section('header_content') ?>
<a href="<?= site_url('/') ?>"
    class="text-xs font-bold text-muted hover:text-primary transition uppercase tracking-wide mr-4">Retour au site</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php if (!empty($pendingSellers)): ?>
    <div class="bg-white rounded-2xl shadow-md border-2 border-orange-100 overflow-hidden relative mb-8">
        <div class="bg-orange-50/50 px-8 py-4 border-b border-orange-100 flex items-center gap-3">
            <div class="relative flex h-3 w-3">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3 w-3 bg-orange-500"></span>
            </div>
            <h3 class="font-serif font-bold text-lg text-orange-800">Candidatures Vendeur (<?= $pendingSellersCount ?>)</h3>
        </div>

        <div class="p-8 grid gap-6">
            <?php foreach ($pendingSellers as $seller): ?>
                <div class="flex flex-col xl:flex-row items-start gap-6 p-4 border border-border rounded-xl hover:shadow-lg transition-all bg-white group">
                    <div class="w-24 h-24 flex-shrink-0 bg-purple-50 rounded-lg overflow-hidden border border-purple-100 flex items-center justify-center text-purple-300 group-hover:text-purple-500 group-hover:border-purple-300 transition-all">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>

                    <div class="flex-1 w-full pt-1">
                        <h4 class="font-serif text-xl font-bold text-primary group-hover:text-accent transition-colors"><?= esc($seller->shop_name) ?></h4>
                        <div class="flex flex-wrap gap-4 mt-2 text-sm text-muted">
                            <span class="flex items-center gap-1 bg-gray-50 px-2 py-1 rounded border border-gray-100">
                                <span class="text-xs font-bold uppercase tracking-wider">SIRET :</span>
                                <span class="font-mono text-primary"><?= esc($seller->siret) ?></span>
                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                <?= esc($seller->email) ?>
                            </span>
                            <span class="flex items-center gap-1 text-xs">Inscrit le <?= date('d/m/Y', strtotime($seller->created_at)) ?></span>
                        </div>
                        <p class="text-sm text-gray-500 mt-3 italic border-l-2 border-gray-200 pl-3">"Candidature en attente de validation manuelle."</p>
                    </div>

                    <div class="flex flex-col sm:flex-row items-center gap-2 w-full xl:w-auto mt-2 xl:mt-0">
                        <a href="<?= site_url('admin/users/approve/' . $seller->user_id) ?>" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-bold shadow-md hover:shadow-lg transition w-full sm:w-auto text-center text-sm">Approve</a>
                        <form action="<?= site_url('admin/users/reject/' . $seller->user_id) ?>" method="post" class="flex gap-2 w-full sm:w-auto">
                            <?= csrf_field() ?>
                            <input type="text" name="reason" class="flex-1 px-3 py-2 border border-border rounded-lg text-sm focus:ring-2 focus:ring-red-200 outline-none min-w-[150px]" placeholder="Reason (optional)...">
                            <button type="submit" class="bg-white text-red-600 border border-red-200 hover:bg-red-50 px-4 py-2 rounded-lg font-bold transition text-sm">Refuser</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="p-6 border-t border-border flex justify-center bg-gray-50/30">
            <?= $pagerSellers->links('vendors', 'tailwind') ?>
        </div>
    </div>
<?php endif; ?>

<div class="bg-white rounded-2xl shadow-sm border border-border overflow-hidden flex flex-col">
    <div class="px-8 py-6 border-b border-border bg-gray-50/30 flex flex-col sm:flex-row justify-between items-center gap-4">
        <h2 class="font-serif font-bold text-xl text-primary">Annuaire Complet</h2>
        <form method="get" action="<?= site_url('admin/users') ?>">
            <select name="role" onchange="this.form.submit()" class="pl-4 pr-10 py-2.5 bg-white border border-border text-primary text-sm font-bold rounded-xl shadow-sm focus:ring-2 focus:ring-accent/20 focus:border-accent outline-none cursor-pointer">
                <option value="">Tous les rôles</option>
                <option value="CUSTOMER" <?= ($currentRole === 'CUSTOMER') ? 'selected' : '' ?>>Clients</option>
                <option value="SELLER" <?= ($currentRole === 'SELLER') ? 'selected' : '' ?>>Vendeurs</option>
            </select>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-white text-[10px] uppercase text-muted font-bold border-b border-border tracking-widest">
                <tr>
                    <th class="px-8 py-5">Identité</th>
                    <th class="px-8 py-5">Email</th>
                    <th class="px-8 py-5">Rôle</th>
                    <th class="px-8 py-5">Date d'inscription</th>
                    <th class="px-8 py-5 text-center">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border text-sm">
                <?php foreach ($allUsers as $user): ?>
                    <tr class="hover:bg-cream transition-colors">
                        <td class="px-8 py-5 font-bold text-primary"><?= esc($user->lastname) ?> <?= esc($user->firstname) ?></td>
                        <td class="px-8 py-5 text-muted"><?= esc($user->email) ?></td>
                        <td class="px-8 py-5">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide <?= $user->role === 'SELLER' ? 'bg-purple-50 text-purple-700 border border-purple-100' : 'bg-gray-100 text-gray-600 border border-gray-200' ?>">
                                <?= $user->role === 'SELLER' ? 'Vendeur' : 'Client' ?>
                            </span>
                        </td>
                        <td class="px-8 py-5 text-muted font-mono text-xs"><?= substr($user->created_at, 0, 10) ?></td>
                        <td class="px-8 py-5 text-center">
                            <a href="javascript:void(0)"
                                onclick="openDeleteModal('<?= site_url('admin/users/' . $user->id) ?>')"
                                class="text-red-400 hover:text-red-600 font-bold transition p-2 hover:bg-red-50 rounded-lg inline-block">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="p-6 border-t border-border flex justify-center bg-gray-50/30">
        <?= $pagerUsers->links('users', 'tailwind') ?>
    </div>
</div>

<?= view('partials/delete_modal') ?>

<?= $this->endSection() ?>