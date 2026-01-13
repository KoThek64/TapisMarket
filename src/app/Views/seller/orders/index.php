<?= $this->extend('layouts/seller_section') ?>

<?= $this->section('header_content') ?>
    <div class="hidden md:flex items-center gap-4 bg-white/50 backdrop-blur-sm px-4 py-2 rounded-xl shadow-sm border border-border">
        <div class="text-right border-r border-gray-200 pr-4 mr-4">
            <span class="block text-xs font-bold text-gray-500 uppercase">Commandes Totales</span>
            <span class="block text-xl font-bold text-primary"><?= $stats['count'] ?? 0 ?></span>
        </div>
        <div class="text-right">
            <span class="block text-xs font-bold text-gray-500 uppercase">CA Global</span>
            <span class="block text-xl font-serif font-bold text-accent"><?= number_format($stats['turnover'] ?? 0, 2) ?> €</span>
        </div>
    </div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

    <div class="max-w-6xl mx-auto space-y-8">

        <!-- Filters Toolbar -->
        <?php 
            $currentStatus = $currentStatus ?? 'ALL';
            $tabs = [
                'ALL'                => 'Vue d\'ensemble',
                'PAID'               => 'À traiter',
                'PREPARING'          => 'En préparation',
                'SHIPPED'            => 'Expédiées',
                'DELIVERED'          => 'Livrées'
            ];
            $tabs['CANCELLED'] = 'Annulées';
        ?>
        <div class="flex flex-col sm:flex-row gap-4 justify-between items-end sm:items-center bg-white p-2 pr-4 rounded-2xl shadow-sm border border-border">
            <nav class="flex overflow-x-auto py-1 space-x-1 w-full sm:w-auto scrollbar-none">
                 <?php foreach($tabs as $status => $label): 
                    $isActive = ($currentStatus === $status);
                    
                    // Base styles
                    $common = "whitespace-nowrap px-4 py-2 rounded-xl text-sm font-bold transition-all duration-200 border flex items-center gap-2";
                    
                    if ($isActive) {
                        $style = "bg-primary text-white border-primary shadow-lg shadow-primary/30";
                    } else {
                        // Inactive styles
                        if ($status === 'PAID') {
                             $style = "bg-amber-50 text-amber-700 border-amber-200 hover:bg-amber-100 hover:border-amber-300";
                        } elseif ($status === 'ALL') {
                            $style = "bg-gray-50 text-muted border-transparent hover:bg-gray-100 hover:text-primary";
                        } else {
                            $style = "bg-white text-muted border-transparent hover:bg-gray-50 hover:text-primary border-border/50";
                        }
                    }
                 ?>
                    <a href="<?= base_url('seller/orders?status=' . $status) ?>" class="<?= $common ?> <?= $style ?>">
                       <?= $label ?>
                       <?php if($status === 'PAID' && !$isActive): ?>
                            <span class="flex h-2 w-2 relative">
                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                              <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                            </span>
                       <?php endif; ?>
                    </a>
                 <?php endforeach; ?>
            </nav>
            
            <?php if($currentStatus !== 'ALL'): ?>
                <a href="<?= base_url('seller/orders') ?>" class="text-sm text-muted hover:text-primary flex items-center gap-1 transition-colors px-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Effacer les filtres
                </a>
            <?php endif; ?>
        </div>
        
        <?php if (empty($orders)): ?>
            <div class="bg-white rounded-2xl shadow-sm border border-border p-12 text-center">
                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-muted">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-primary mb-2">Aucune commande</h3>
                <p class="text-muted">Vos produits n'ont pas encore trouvé preneur.</p>
            </div>
        <?php else: ?>
            <div class="space-y-6">
                <?php foreach($orders as $orderData): 
                    $info = $orderData['info'];
                    $items = $orderData['items'];
                    $orderTotal = 0;
                    foreach($items as $item) $orderTotal += $item->unit_price * $item->quantity;
                ?>
                    <div class="bg-white rounded-2xl shadow-sm border border-border overflow-hidden transition-all duration-200 hover:shadow-md">
                        <!-- Order Header -->
                        <div class="p-5 flex flex-col md:flex-row justify-between gap-4">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center border border-border text-primary font-bold shadow-sm shrink-0">
                                    #
                                </div>
                                <div class="space-y-1">
                                    <div class="flex items-center gap-3">
                                        <h3 class="font-bold text-primary text-lg">Commande <?= esc($info->reference) ?></h3>
                                        <?php
                                            $statusLabels = [
                                                'PENDING'   => 'En attente',
                                                'PAID'      => 'Payée',
                                                'PREPARING' => 'En préparation',
                                                'SHIPPED'   => 'Expédiée',
                                                'DELIVERED' => 'Livrée',
                                                'CANCELLED' => 'Annulée',
                                                'REFUNDED'  => 'Remboursée'
                                            ];
                                            $statusColor = match($info->status) {
                                                'PAID', 'DELIVERED' => 'bg-green-100 text-green-700',
                                                'PENDING', 'PREPARING' => 'bg-amber-100 text-amber-700',
                                                'SHIPPED' => 'bg-blue-100 text-blue-700',
                                                'CANCELLED', 'REFUNDED' => 'bg-red-100 text-red-700',
                                                default => 'bg-gray-100 text-gray-700'
                                            };
                                            $displayStatus = $statusLabels[$info->status] ?? $info->status;
                                        ?>
                                        <span class="<?= $statusColor ?> px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wide leading-none">
                                            <?= esc($displayStatus) ?>
                                        </span>
                                    </div>
                                    
                                    <p class="text-sm text-muted">
                                        <span class="inline-flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            <?= date('d/m/Y', strtotime($info->order_date)) ?> à <?= date('H:i', strtotime($info->order_date)) ?>
                                        </span>
                                        <span class="mx-2">•</span>
                                        <span class="inline-flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                            <?= esc($info->customer_firstname . ' ' . $info->customer_lastname) ?>
                                        </span>
                                    </p>
                                </div>
                            </div>
                            
                            <div class="flex flex-col md:items-end gap-2 md:text-right border-t md:border-t-0 border-dashed border-gray-200 pt-4 md:pt-0">
                                <div>
                                    <span class="block text-xs font-bold text-muted uppercase tracking-wider">Montant total</span>
                                    <span class="block text-xl font-bold text-primary"><?= number_format($orderTotal, 2) ?> €</span>
                                </div>
                                
                                <!-- Actions de changement de statut -->
                                <div class="flex flex-wrap gap-2 mt-1">
                                    <?php if ($info->status === 'PAID'): ?>
                                        <form action="<?= base_url('seller/orders/update-status/' . $info->order_id) ?>" method="POST">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="status" value="PREPARING">
                                            <button type="submit" class="inline-flex items-center gap-1 text-xs bg-amber-50 text-amber-700 hover:bg-amber-100 font-medium px-3 py-1.5 rounded-lg border border-amber-200 transition-colors w-full md:w-auto justify-center">
                                                <span>Préparer</span>
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path></svg>
                                            </button>
                                        </form>
                                    <?php endif; ?>

                                    <?php if ($info->status === 'PREPARING'): ?>
                                        <form action="<?= base_url('seller/orders/update-status/' . $info->order_id) ?>" method="POST">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="status" value="SHIPPED">
                                            <button type="submit" class="inline-flex items-center gap-1 text-xs bg-blue-50 text-blue-700 hover:bg-blue-100 font-medium px-3 py-1.5 rounded-lg border border-blue-200 transition-colors w-full md:w-auto justify-center">
                                                <span>Expédier</span>
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            </button>
                                        </form>
                                    <?php endif; ?>

                                    <?php if ($info->status === 'SHIPPED'): ?>
                                        <form action="<?= base_url('seller/orders/update-status/' . $info->order_id) ?>" method="POST">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="status" value="DELIVERED">
                                            <button type="submit" class="inline-flex items-center gap-1 text-xs bg-green-50 text-green-700 hover:bg-green-100 font-medium px-3 py-1.5 rounded-lg border border-green-200 transition-colors w-full md:w-auto justify-center">
                                                <span>Confirmer Livraison</span>
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Collapsible Details -->
                        <details class="group border-t border-border bg-gray-50/30">
                            <summary class="flex justify-between items-center p-4 cursor-pointer hover:bg-gray-50 transition-colors select-none">
                                <span class="font-bold text-sm text-muted uppercase tracking-wider flex items-center gap-2">
                                    <span><?= count($items) ?> Produit<?= count($items) > 1 ? 's' : '' ?></span>
                                    <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                                    <span class="font-normal normal-case text-xs">Voir le détail</span>
                                </span>
                                <span class="text-gray-400 group-open:text-accent group-open:rotate-180 transition-all duration-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </span>
                            </summary>
                            
                            <div class="px-4 pb-4">
                                <div class="bg-white rounded-xl border border-border overflow-hidden">
                                    <div class="overflow-x-auto">
                                        <table class="w-full text-sm text-left">
                                            <thead class="text-xs text-muted uppercase bg-gray-50/50 border-b border-border">
                                                <tr>
                                                    <th class="px-4 py-3 font-semibold">Produit</th>
                                                    <th class="px-4 py-3 font-semibold text-center w-24">Qté</th>
                                                    <th class="px-4 py-3 font-semibold text-right w-32">Prix Unit.</th>
                                                    <th class="px-4 py-3 font-semibold text-right w-32">Total</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-border">
                                                <?php foreach($items as $item): ?>
                                                    <tr class="hover:bg-gray-50/50 transition-colors">
                                                        <td class="px-4 py-3">
                                                            <div class="font-medium text-primary"><?= esc($item->title) ?></div>
                                                            <div class="text-xs text-muted">Ref: <?= esc($item->alias ?? 'N/A') ?></div>
                                                        </td>
                                                        <td class="px-4 py-3 text-center text-primary font-medium"><?= $item->quantity ?></td>
                                                        <td class="px-4 py-3 text-right text-muted"><?= number_format($item->unit_price, 2) ?> €</td>
                                                        <td class="px-4 py-3 text-right font-bold text-accent">
                                                            <?= number_format($item->unit_price * $item->quantity, 2) ?> €
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Delivery Info Row -->
                                    <div class="bg-cream px-4 py-3 text-sm text-muted border-t border-border flex flex-col sm:flex-row sm:items-center gap-2">
                                        <span class="font-bold flex items-center gap-2 text-primary">
                                            <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                            Adresse de livraison :
                                        </span>
                                        <span>
                                            <?= esc($info->delivery_city) ?> (<?= esc($info->delivery_postal_code) ?>)
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </details>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="mt-8 flex justify-center">
                <?= $pager->links('default', 'tailwind') ?>
            </div>
        <?php endif; ?>
    </div>

<?= $this->endSection() ?>
