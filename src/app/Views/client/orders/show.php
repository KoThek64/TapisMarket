<?= $this->extend('layouts/client_section') ?>

<?php
// Status badges logic
$statusLabels = [
    'PENDING'   => 'En attente',
    'PAID'      => 'Payée',
    'PREPARING' => 'En préparation',
    'SHIPPED'   => 'Expédiée',
    'DELIVERED' => 'Livrée',
    'CANCELLED' => 'Annulée'
];
$status = $order->status ?? 'PENDING';
$statusLabel = $statusLabels[$status] ?? $status;
$statusColor = match($status) {
    'PAID', 'DELIVERED', 'COMPLETED' => 'bg-green-100 text-green-700 border-green-200',
    'PENDING', 'PREPARING' => 'bg-amber-100 text-amber-700 border-amber-200',
    'SHIPPED' => 'bg-blue-100 text-blue-700 border-blue-200',
    'CANCELLED' => 'bg-red-100 text-red-700 border-red-200',
    default => 'bg-gray-100 text-gray-700 border-gray-200'
};
?>

<?= $this->section('header_content') ?>
    <div class="flex items-center gap-2">
        <a href="<?= site_url('client/orders') ?>" class="text-sm text-muted hover:text-primary transition flex items-center gap-1 font-bold">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg> 
            Retour
        </a>
    </div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Order Items -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-border overflow-hidden">
                <div class="px-6 py-4 border-b border-border bg-gray-50/50 flex justify-between items-center">
                    <h3 class="font-bold text-lg text-primary">Articles du panier</h3>
                    <span class="text-xs font-bold text-muted uppercase">
                        <?php if(isset($pager)): ?>
                            Page <?= $pager->getCurrentPage() ?> (<?= count($items) ?> affichés)
                        <?php else: ?>
                            <?= count($items) ?> Article<?= count($items) > 1 ? 's' : '' ?>
                        <?php endif; ?>
                    </span>
                </div>
                <div class="divide-y divide-border">
                    <?php if(empty($items)): ?>
                        <div class="p-8 text-center text-muted">Aucun article trouvé pour cette commande.</div>
                    <?php else: ?>
                        <?php foreach ($items as $item): ?>
                            <div class="p-6 flex flex-col sm:flex-row items-start sm:items-center gap-6 group hover:bg-gray-50/30 transition-colors">
                                <!-- Image -->
                                <div class="w-20 h-20 bg-gray-50 rounded-xl border border-border flex items-center justify-center shrink-0 overflow-hidden relative">
                                    <img src="<?= $item->getImage() ?>" alt="<?= esc($item->title) ?>" class="w-full h-full object-cover">
                                </div>
                                
                                <div class="flex-1">
                                    <h4 class="font-bold text-primary text-lg mb-1 leading-tight"><?= esc($item->title ?? 'Produit #' . $item->product_id) ?></h4>
                                    <p class="text-xs text-muted">Ref: <?= esc($item->alias ?? 'N/A') ?></p>
                                    <div class="mt-2 text-sm">
                                        <span class="text-muted">Quantité :</span> <span class="font-bold text-primary"><?= $item->quantity ?></span>
                                    </div>
                                </div>
                                
                                <div class="text-right min-w-[120px]">
                                    <p class="font-bold text-lg text-primary"><?= number_format($item->unit_price * $item->quantity, 2) ?> €</p>
                                    <p class="text-xs text-muted"><?= number_format($item->unit_price, 2) ?> € / unité</p>
                                </div>
                                
                                <?php if(in_array($order->status, ['DELIVERED', 'COMPLETED'])): ?>
                                    <div class="ml-2">
                                        <a href="<?= site_url('client/reviews/' . $item->product_id . '/edit/') ?>" class="p-2.5 text-accent hover:bg-accent/10 hover:text-accent-hover rounded-xl transition flex flex-col items-center gap-1 group/btn" title="Donner mon avis">
                                            <svg class="w-6 h-6 group-hover/btn:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <?php if (isset($pager) && $pager->getPageCount() > 1): ?>
                    <div class="px-6 py-4 border-t border-border bg-gray-50/50">
                        <?= $pager->links() ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Delivery Info Block -->
            <div class="bg-white rounded-2xl shadow-sm border border-border p-6 flex flex-col sm:flex-row gap-6">
                <div class="flex-1">
                    <h3 class="font-bold text-sm uppercase text-muted tracking-wider mb-4 border-b border-border pb-2">Adresse de livraison</h3>
                    <div class="text-sm text-gray-600 leading-relaxed">
                        <p class="font-bold text-primary text-base mb-1"><?= esc($user->firstname . ' ' . $user->lastname) ?></p>
                        <?php if(!empty($order->delivery_street)): ?>
                            <p><?= esc($order->delivery_street) ?></p>
                            <p><?= esc($order->delivery_postal_code) ?> <?= esc($order->delivery_city) ?></p>
                            <p class="uppercase text-xs font-bold text-muted mt-1"><?= esc($order->delivery_country) ?></p>
                        <?php else: ?>
                            <p class="text-muted italic">Adresse non renseignée</p>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="hidden sm:block w-px bg-border"></div>
                <div class="flex-1">
                    <h3 class="font-bold text-sm uppercase text-muted tracking-wider mb-4 border-b border-border pb-2">Statut de livraison</h3>
                     <div class="flex items-center gap-3">
                        <div class="p-3 <?= $statusColor ?> bg-opacity-10 rounded-xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                        </div>
                        <div>
                             <p class="font-bold text-primary"><?= esc($statusLabel) ?></p>
                             <p class="text-xs text-muted"><?= esc($order->delivery_method ?? 'Expédition standard') ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Summary Sidebar -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-border p-6 sticky top-8">
                <h3 class="font-bold text-lg text-primary mb-6">Récapitulatif</h3>
                
                <div class="space-y-4 text-sm">
                    <div class="flex justify-between items-center">
                        <span class="text-muted">Sous-total</span>
                        <span class="font-bold text-primary"><?= number_format($order->total_ttc - ($order->shipping_fees ?? 0), 2) ?> €</span> 
                    </div>
                     <div class="flex justify-between items-center">
                        <span class="text-muted">Frais de livraison</span>
                        <?php if(($order->shipping_fees ?? 0) > 0): ?>
                            <span class="font-bold text-primary"><?= number_format($order->shipping_fees, 2) ?> €</span>
                        <?php else: ?>
                            <span class="font-bold text-green-600">Offerts</span>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="mt-6 pt-6 border-t border-border border-dashed">
                    <div class="flex justify-between items-end">
                        <span class="font-bold text-lg text-primary">Total Payé</span>
                        <div class="text-right">
                             <span class="block font-serif font-bold text-2xl text-accent"><?= number_format($order->total_ttc, 2) ?> €</span>
                             <span class="text-xs text-muted">TVA incluse</span>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6 pt-6 border-t border-border flex justify-center">
                    <button onclick="window.print()" class="flex items-center justify-center gap-2 px-6 py-3 bg-gray-50 text-primary font-bold rounded-xl text-sm hover:bg-gray-100 transition border border-border w-full">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        Imprimer le récapitulatif
                    </button>
                </div>
            </div>
        </div>

    </div>

<?= $this->endSection() ?>
