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

<div class="space-y-8">

    <div class="bg-white rounded-2xl shadow-sm border border-border overflow-hidden">
        <?php if (empty($orders)): ?>
            <div class="p-16 text-center">
                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center text-3xl mx-auto mb-4">📦
                </div>
                <h3 class="font-serif text-xl font-bold text-primary mb-2">Aucune commande</h3>
                <p class="text-muted mb-6">Vous n'avez pas encore passé de commande sur notre boutique.</p>
                <a href="<?= base_url('/catalog') ?>"
                    class="inline-block px-6 py-3 bg-primary text-white rounded-xl font-medium hover:bg-accent transition">
                    Découvrir la boutique
                </a>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 border-b border-border">
                        <tr>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-muted">Référence</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-muted">Date</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-muted">Total</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-muted">Statut</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-muted text-right">Détails
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        <?php foreach ($orders as $order): ?>
                            <tr class="hover:bg-cream/50 transition group">
                                <td class="px-6 py-4">
                                    <span class="font-bold text-primary font-serif">#<?= esc($order->reference) ?></span>
                                </td>
                                <td class="px-6 py-4 text-sm text-muted">
                                    <?= date('d/m/Y', strtotime($order->order_date)) ?>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-bold text-accent"><?= number_format($order->total_ttc, 2, ',', ' ') ?>
                                        €</span>
                                </td>
                                <td class="px-6 py-4">
                                    <?php
                                    $statusStyles = [
                                        ORDER_PAID => 'bg-green-100 text-green-800 border-green-200',
                                        ORDER_SHIPPED => 'bg-blue-100 text-blue-800 border-blue-200',
                                        ORDER_DELIVERED => 'bg-gray-100 text-gray-800 border-gray-200',
                                        ORDER_CANCELLED => 'bg-red-100 text-red-800 border-red-200',
                                    ];
                                    // Fallback default
                                    $css = $statusStyles[$order->status] ?? 'bg-orange-100 text-orange-800 border-orange-200';

                                    // Traduction rapide (idéalement via un helper)
                                    $labels = [
                                        ORDER_PAID => 'Payée',
                                        ORDER_SHIPPED => 'Expédiée',
                                        ORDER_DELIVERED => 'Livrée',
                                        ORDER_CANCELLED => 'Annulée'
                                    ];
                                    $label = $labels[$order->status] ?? 'En cours';
                                    ?>
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border <?= $css ?>">
                                        <?= $label ?>
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

            <div class="p-6 border-t border-border bg-gray-50/50 flex justify-center">
                <?= $pager->links('default', 'tailwind') ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>