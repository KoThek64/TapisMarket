<?= $this->extend('layouts/admin_section') ?>

<?= $this->section('header_content') ?>
<a href="<?= site_url('/') ?>"
    class="text-xs font-bold text-muted hover:text-primary transition uppercase tracking-wide mr-4">Retour au site</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">

    <div
        class="bg-gradient-to-br from-primary to-gray-800 text-white p-6 rounded-2xl shadow-xl relative overflow-hidden group">
        <div class="relative z-10">
            <div class="flex items-center gap-3 mb-4 text-gray-300">
                <div class="p-2 bg-white/10 rounded-lg"><svg class="w-5 h-5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg></div>
                <span class="text-xs font-bold uppercase tracking-wider opacity-80">Revenus</span>
            </div>
            <h3 class="font-serif text-3xl font-bold tracking-tight"><?= number_format($totalSales, 2, '.', ',') ?> €
            </h3>
            <p class="text-xs text-green-300 mt-2 font-medium flex items-center gap-1">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
                Total validé
            </p>
        </div>
        <div
            class="absolute -right-6 -bottom-6 w-32 h-32 bg-accent/20 rounded-full blur-2xl group-hover:bg-accent/30 transition-all duration-500">
        </div>
    </div>

    <?php $totalMod = $pendingSellersCount + $pendingProductsCount; ?>
    <div
        class="bg-white p-6 rounded-2xl shadow-sm border border-border relative overflow-hidden group hover:shadow-md transition-all">
        <div class="flex justify-between items-start">
            <div>
                <div class="flex items-center gap-3 mb-4 text-muted">
                    <div class="p-2 bg-orange-50 text-orange-600 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                            </path>
                        </svg>
                    </div>
                    <span class="text-xs font-bold uppercase tracking-wider">À Modérer</span>
                </div>
                <h3 class="font-serif text-3xl font-bold text-primary group-hover:text-accent transition-colors">
                    <?= $totalMod ?>
                </h3>
            </div>

            <?php if ($totalMod > 0): ?>
                <span class="flex h-3 w-3 relative">
                    <span
                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-orange-500"></span>
                </span>
            <?php endif; ?>
        </div>

        <div class="mt-4 space-y-1">
            <div class="flex justify-between text-sm text-muted">
                <span>Vendeurs</span>
                <span class="font-bold <?= ($pendingSellersCount > 0) ? 'text-accent' : 'text-primary' ?>">
                    <?= $pendingSellersCount ?>
                </span>
            </div>
            <div class="flex justify-between text-sm text-muted">
                <span>Produits</span>
                <span class="font-bold <?= ($pendingProductsCount > 0) ? 'text-accent' : 'text-primary' ?>">
                    <?= $pendingProductsCount ?>
                </span>
            </div>
        </div>

        <?php if ($totalMod > 0): ?>
            <a href="<?= $moderationLink ?>" class="absolute inset-0 z-10" title="Access moderation"></a>
        <?php endif; ?>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-border hover:shadow-md transition-all">
        <div class="flex items-center gap-3 mb-4 text-muted">
            <div class="p-2 bg-blue-50 text-blue-600 rounded-lg"><svg class="w-5 h-5" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg></div>
            <span class="text-xs font-bold uppercase tracking-wider">Commandes</span>
        </div>
        <h3 class="font-serif text-3xl font-bold text-primary"><?= $ordersCount ?></h3>
        <p class="text-sm text-muted mt-2">Commandes terminées</p>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-border hover:shadow-md transition-all">
        <div class="flex items-center gap-3 mb-4 text-muted">
            <div class="p-2 bg-purple-50 text-purple-600 rounded-lg"><svg class="w-5 h-5" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                    </path>
                </svg></div>
            <span class="text-xs font-bold uppercase tracking-wider">Utilisateurs</span>
        </div>
        <h3 class="font-serif text-3xl font-bold text-primary"><?= $usersCount ?></h3>
        <p class="text-sm text-muted mt-2">Membres inscrits</p>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-2 gap-8">

    <div class="bg-white rounded-2xl shadow-sm border border-border overflow-hidden flex flex-col h-full">
        <div class="px-8 py-6 border-b border-border flex justify-between items-center bg-gray-50/30">
            <h2 class="font-serif font-bold text-xl text-primary">Dernières Commandes</h2>
            <a href="<?= site_url('admin/orders') ?>"
                class="text-xs font-bold text-accent hover:text-primary uppercase tracking-wide transition font-sans">Tout
                voir →</a>
        </div>
        <div class="flex-1 overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-white text-xs uppercase text-muted font-bold border-b border-border">
                    <tr>
                        <th class="px-8 py-4">Référence</th>
                        <th class="px-8 py-4">Client</th>
                        <th class="px-8 py-4 text-right">Montant</th>
                        <th class="px-8 py-4 text-right">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    <?php foreach ($latestOrders as $cmd): ?>
                        <tr class="hover:bg-cream transition-colors group">
                            <td class="px-8 py-4">
                                <div
                                    class="font-mono text-sm font-bold text-primary group-hover:text-accent transition-colors">
                                    <?= esc($cmd->reference) ?>
                                </div>
                            </td>
                            <td class="px-8 py-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-xs font-bold text-gray-500">
                                        <?= strtoupper(substr($cmd->firstname, 0, 1)) ?>
                                    </div>
                                    <div class="text-sm font-medium text-gray-700">
                                        <?= esc($cmd->firstname) ?> <span
                                            class="uppercase text-xs text-muted"><?= esc($cmd->lastname) ?></span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-4 text-right">
                                <span class="font-bold text-primary"><?= number_format($cmd->total_ttc, 2) ?> €</span>
                            </td>
                            <td class="px-8 py-4 text-right text-sm text-muted">
                                <?= (!empty($cmd->order_date) && $cmd->order_date !== '-') ? date('d/m', strtotime($cmd->order_date)) : '-' ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-border overflow-hidden flex flex-col h-full">
        <div class="px-8 py-6 border-b border-border flex justify-between items-center bg-gray-50/30">
            <h2 class="font-serif font-bold text-xl text-primary">Nouveaux Membres</h2>
            <a href="<?= site_url('admin/users') ?>"
                class="text-xs font-bold text-accent hover:text-primary uppercase tracking-wide transition">Gérer</a>
        </div>
        <div class="flex-1 overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-white text-xs uppercase text-muted font-bold border-b border-border">
                    <tr>
                        <th class="px-8 py-4">Utilisateur</th>
                        <th class="px-8 py-4">Rôle</th>
                        <th class="px-8 py-4 text-right">Inscription</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    <?php foreach ($latestUsers as $user): ?>
                        <tr class="hover:bg-cream transition-colors">
                            <td class="px-8 py-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-9 h-9 rounded-xl bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center text-sm font-bold text-gray-600 shadow-sm">
                                        <?= strtoupper(substr($user->email, 0, 1)) ?>
                                    </div>
                                    <div class="text-sm font-medium text-primary">
                                        <?= esc($user->email) ?>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-4">
                                <?php if ($user->role === 'SELLER'): ?>
                                    <div class="flex items-center gap-3">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-purple-50 text-purple-700 border border-purple-100">
                                            Vendeur
                                        </span>

                                        <?php if (isset($user->seller_status)): ?>
                                            <?php if ($user->seller_status === 'VALIDATED'): ?>
                                                <div class="relative group cursor-default">
                                                    <span class="h-3 w-3 block rounded-full bg-green-500 shadow-sm border border-green-200"></span>
                                                    <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover:block px-2 py-1 bg-gray-800 text-white text-xs rounded whitespace-nowrap">Compte validé</span>
                                                </div>

                                            <?php elseif ($user->seller_status === 'PENDING_VALIDATION'): ?>
                                                <div class="relative group cursor-default">
                                                    <span class="relative flex h-3 w-3">
                                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span>
                                                        <span class="relative inline-flex rounded-full h-3 w-3 bg-orange-500 border border-orange-200"></span>
                                                    </span>
                                                    <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover:block px-2 py-1 bg-gray-800 text-white text-xs rounded whitespace-nowrap">En attente</span>
                                                </div>

                                            <?php elseif ($user->seller_status === 'REFUSED'): ?>
                                                <div class="relative group cursor-default">
                                                    <span class="h-3 w-3 block rounded-full bg-red-500 shadow-sm border border-red-200"></span>
                                                    <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover:block px-2 py-1 bg-gray-800 text-white text-xs rounded whitespace-nowrap">Candidature refusée</span>
                                                </div>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                <?php else: ?>
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gray-50 text-gray-600 border border-gray-200">
                                        Client
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-8 py-4 text-right text-sm text-muted font-mono">
                                <?= (!empty($user->created_at) && $user->created_at !== '-') ? date('d/m/Y', strtotime($user->created_at)) : '-' ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<?= $this->endSection() ?>