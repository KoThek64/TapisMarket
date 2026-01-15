<?= $this->extend('layouts/client_section') ?>

<?= $this->section('header_content') ?>
<a href="<?= site_url('/') ?>"
    class="px-4 py-2 border border-border rounded-xl hover:bg-white transition text-sm font-bold flex items-center gap-2 bg-gray-50/50 text-gray-700">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
    </svg>
    Retour à la boutique
</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
    <!-- Total Orders -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-border flex items-center gap-4">
        <div class="p-4 bg-blue-50 text-blue-600 rounded-xl">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
            </svg>
        </div>
        <div>
            <span class="block text-xs font-bold uppercase tracking-wider text-muted">Mes Commandes</span>
            <span class="block text-3xl font-serif font-bold text-primary"><?= $totalOrders ?? 0 ?></span>
        </div>
    </div>

    <!-- Profile info -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-border flex items-center gap-4">
        <div class="p-4 bg-purple-50 text-purple-600 rounded-xl">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
        </div>
        <div>
            <span class="block text-xs font-bold uppercase tracking-wider text-muted">Client fidèle</span>
            <span class="block text-sm font-medium text-primary mt-1">Depuis le
                <?= date('d/m/Y', strtotime($user->created_at ?? 'now')) ?></span>
        </div>
    </div>

    <!-- Alerts (Rejected Reviews) -->
    <?php if (!empty($rejectedReviews)): ?>
        <div class="bg-red-50 p-6 rounded-2xl shadow-sm border border-red-100 flex items-center gap-4">
            <div class="p-4 bg-white text-red-500 rounded-xl shadow-sm">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                    </path>
                </svg>
            </div>
            <div>
                <span class="block text-xs font-bold uppercase tracking-wider text-red-800">Attention</span>
                <span class="block text-sm font-bold text-red-700 mt-1"><?= count($rejectedReviews) ?> avis refusé(s)</span>
                <a href="<?= site_url('client/reviews') ?>"
                    class="text-xs underline text-red-600 hover:text-red-800 mt-1 block">Voir les détails</a>
            </div>
        </div>
    <?php else: ?>
        <!-- Published Reviews -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-border flex items-center gap-4">
            <div class="p-4 bg-amber-50 text-amber-600 rounded-xl">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.784.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                </svg>
            </div>
            <div>
                <span class="block text-xs font-bold uppercase tracking-wider text-muted">Mes Contribution</span>
                <span class="block text-3xl font-serif font-bold text-primary"><?= $publishedReviewsCount ?? 0 ?> <span
                        class="text-sm font-sans font-normal text-muted">avis publié(s)</span></span>
            </div>
        </div>
    <?php endif; ?>
</div>


<!-- Recent Orders -->
<div class="bg-white rounded-2xl shadow-sm border border-border overflow-hidden">
    <div class="px-6 py-5 border-b border-border flex justify-between items-center bg-gray-50/50">
        <h3 class="font-bold text-lg text-primary">Commandes Récentes</h3>
        <a href="<?= site_url('client/orders') ?>"
            class="text-sm font-bold text-accent hover:text-accent-hover flex items-center gap-1">
            Voir tout
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3">
                </path>
            </svg>
        </a>
    </div>

    <?php if (!empty($recentOrders)): ?>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-white text-xs uppercase text-muted font-bold border-b border-border">
                    <tr>
                        <th class="px-6 py-4">Référence</th>
                        <th class="px-6 py-4">Date</th>
                        <th class="px-6 py-4">Total</th>
                        <th class="px-6 py-4">Statut</th>
                        <th class="px-6 py-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border text-sm">
                    <?php
                    $statusLabels = [
                        'PENDING' => 'En attente',
                        'PAID' => 'Payée',
                        'PREPARING' => 'En préparation',
                        'SHIPPED' => 'Expédiée',
                        'DELIVERED' => 'Livrée',
                        'CANCELLED' => 'Annulée'
                    ];

                    foreach ($recentOrders as $order):
                        $statusLabel = $statusLabels[$order->status] ?? $order->status;
                        $statusColor = match ($order->status) {
                            'PAID', 'DELIVERED' => 'bg-green-100 text-green-700 border-green-200',
                            'PENDING', 'PREPARING' => 'bg-amber-100 text-amber-700 border-amber-200',
                            'SHIPPED' => 'bg-blue-100 text-blue-700 border-blue-200',
                            'CANCELLED' => 'bg-red-100 text-red-700 border-red-200',
                            default => 'bg-gray-100 text-gray-700 border-gray-200'
                        };
                        ?>
                        <tr class="hover:bg-gray-50 transition group">
                            <td class="px-6 py-4 font-mono font-bold text-primary">#<?= esc($order->reference ?? $order->id) ?>
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                <?= date('d/m/Y', strtotime($order->order_date)) ?>
                            </td>
                            <td class="px-6 py-4 font-bold text-primary"><?= number_format($order->total_ttc, 2) ?> €</td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold border <?= $statusColor ?>">
                                    <?= esc($statusLabel) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="<?= site_url('client/orders/' . $order->id) ?>"
                                    class="w-8 h-8 rounded-full border border-border inline-flex items-center justify-center text-muted hover:bg-primary hover:text-white transition group-hover:border-primary">
                                    →
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="p-12 text-center text-muted">
            <p>Aucune commande récente.</p>
            <a href="<?= site_url('catalog') ?>"
                class="inline-block mt-4 px-6 py-2 bg-primary text-white rounded-xl hover:bg-gray-800 transition">Commencer
                mes achats</a>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>

