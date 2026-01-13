<?= $this->extend('layouts/admin_section') ?>

<?= $this->section('header_content') ?>
<div class="flex items-center gap-4">
    <a href="<?= site_url('admin/orders') ?>" class="w-8 h-8 rounded-full border border-border flex items-center justify-center text-muted hover:bg-primary hover:text-white transition">
        ←
    </a>
    <div>
        <h1 class="font-serif text-2xl font-bold text-primary">Commande <span class="text-accent italic">#<?= esc($order->reference) ?></span></h1>
        <p class="text-muted text-xs font-bold uppercase tracking-widest">
            Du <?= date('d/m/Y à H:i', strtotime($order->order_date)) ?>
        </p>
    </div>
</div>

<div class="flex items-center gap-4">
    <?php 
        $colorClass = match($order->status) {
            ORDER_PAID, ORDER_DELIVERED => 'bg-green-100 text-green-800 border-green-200',
            ORDER_PREPARING, ORDER_SHIPPED => 'bg-blue-100 text-blue-800 border-blue-200',
            ORDER_CANCELLED => 'bg-red-100 text-red-800 border-red-200',
            default => 'bg-orange-100 text-orange-800 border-orange-200'
        };
        
        // Simple translation for badge display
        $statusLabels = [
            'PENDING_VALIDATION' => 'En attente',
            'PAID' => 'Payée',
            'PREPARING' => 'En préparation',
            'SHIPPED' => 'Expédiée', 
            'DELIVERED' => 'Livrée',
            'CANCELLED' => 'Annulée'
        ];
        $displayStatus = $statusLabels[$order->status] ?? $order->status;
    ?>
    <span class="px-4 py-2 rounded-xl text-sm font-bold border <?= $colorClass ?>">
        <?= esc($displayStatus) ?>
    </span>
</div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="flex-1 overflow-y-auto p-8 lg:p-12">
    <div class="max-w-5xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-border">
                <h2 class="text-xs font-bold uppercase text-muted mb-6 tracking-widest border-b border-border pb-2">Informations Client</h2>
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center text-xl">
                        👤
                    </div>
                    <div>
                        <p class="font-serif text-xl font-bold text-primary"><?= esc($order->firstname) ?> <?= esc($order->lastname) ?></p>
                        <p class="text-sm text-muted mt-1"><?= esc($order->email) ?></p>
                        <p class="text-sm text-muted">Tel : <?= esc($order->phone ?? 'Non renseigné') ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-border">
                <h2 class="text-xs font-bold uppercase text-muted mb-6 tracking-widest border-b border-border pb-2">Livraison</h2>
                <div class="flex gap-4">
                    <div class="w-12 h-12 rounded-full bg-orange-50 text-accent flex items-center justify-center text-xl">
                        📍
                    </div>
                    <div>
                        <p class="font-bold text-primary mb-1">Adresse de livraison</p>
                        <p class="text-sm text-muted leading-relaxed">
                            <?= esc($order->delivery_street) ?><br>
                            <?= esc($order->delivery_postal_code) ?> <?= esc($order->delivery_city) ?><br>
                            <span class="font-semibold text-primary"><?= esc($order->delivery_country) ?></span>
                        </p>
                        <div class="mt-4 inline-flex items-center gap-2 px-3 py-1 bg-gray-50 rounded-lg border border-border text-xs font-bold text-muted uppercase">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Mode : <?= esc($order->delivery_method) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-primary text-white p-8 rounded-2xl shadow-xl sticky top-8">
                
                <h2 class="text-xs font-bold uppercase opacity-50 mb-8 tracking-widest border-b border-white/10 pb-4">
                    Récapitulatif financier
                </h2>
                
                <div class="space-y-4 mb-8">
                    <div class="flex justify-between items-center text-sm">
                        <span class="opacity-70 font-medium">Sous-total</span>
                        <span class="font-bold"><?= number_format($order->total_ttc - $order->shipping_fees, 2, ',', ' ') ?> €</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="opacity-70 font-medium">Frais de port</span>
                        <span class="text-green-400 font-bold">+ <?= number_format($order->shipping_fees, 2, ',', ' ') ?> €</span>
                    </div>
                </div>

                <div class="h-px bg-gradient-to-r from-transparent via-white/20 to-transparent my-6"></div>

                <div class="flex flex-col gap-1">
                    <div class="flex justify-between items-end">
                        <span class="font-bold text-lg text-white">Total (TTC)</span>
                        <span class="font-serif text-4xl font-bold text-accent tracking-tight">
                            <?= number_format($order->total_ttc, 2, ',', ' ') ?> <span class="text-2xl">€</span>
                        </span>
                    </div>
                    <p class="text-[10px] text-right opacity-40 italic">TVA incluse selon les taux en vigueur</p>
                </div>
                
            </div>
        </div>

    </div>
</div>

<?= $this->endSection() ?>