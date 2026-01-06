<?= $this->extend('admin/layout/base') ?>

<?= $this->section('header') ?>
    <?php
    $headerAction = '
        <div class="text-right border-r border-border pr-6 hidden sm:block">
            <p class="text-[10px] uppercase font-bold text-muted tracking-widest">Revenu Total</p>
            <p class="font-serif text-2xl font-bold text-accent">'. number_format($globalAmount, 2, '.', ',') .' €</p>
        </div>
        <a href="'. site_url('admin') .'" class="text-xs font-bold text-muted hover:text-primary transition uppercase border border-border px-4 py-2 rounded-full bg-white shadow-sm flex items-center gap-2">
            <span>← Retour</span>
        </a>
    ';
    ?>

    <?= view('admin/partials/header', [
        'title'    => 'Historique des Commandes',
        'subtitle' => 'Vue d\'ensemble des transactions',
        'action'   => $headerAction
    ]) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

    <div class="bg-white rounded-2xl shadow-sm border border-border overflow-hidden flex flex-col">
        
        <div class="px-8 py-6 border-b border-border bg-gray-50/30 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-4">
                <h2 class="font-serif font-bold text-xl text-primary">Transactions</h2>
                <span class="bg-primary text-white text-[10px] px-3 py-1 rounded-full uppercase font-bold tracking-tighter shadow-md">
                    Total : <?= $pager->getTotal('orders') ?>
                </span>
            </div>

            <form method="get" action="<?= site_url('admin/orders') ?>">
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-muted group-hover:text-accent transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    </div>
                    
                    <select name="status" onchange="this.form.submit()" 
                            class="appearance-none pl-10 pr-10 py-2.5 bg-white border border-border text-primary text-sm font-bold rounded-xl shadow-sm hover:border-accent/50 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-accent/20 focus:border-accent transition-all cursor-pointer w-64">
                        <option value="">Tous les statuts</option>
                        <?php foreach($statusList as $key => $label): ?>
                            <option value="<?= $key ?>" <?= ($currentStatus === $key) ? 'selected' : '' ?>>
                                <?= $label ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-muted group-hover:text-primary transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>
            </form>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-white text-[10px] uppercase text-muted font-bold border-b border-border">
                    <tr>
                        <th class="px-8 py-4">Référence</th>
                        <th class="px-8 py-4">Client</th>
                        <th class="px-8 py-4">Statut</th>
                        <th class="px-8 py-4 text-right">Montant</th>
                        <th class="px-8 py-4 text-right">Date</th>
                        <th class="px-8 py-4 text-center">Détails</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border text-sm">
                    <?php foreach($orders as $order): ?>
                    <tr class="hover:bg-cream transition-colors group">
                        <td class="px-8 py-5">
                            <span class="font-mono text-xs font-bold text-accent px-2 py-1 bg-orange-50 border border-orange-100 rounded">#<?= esc($order->reference) ?></span>
                        </td>
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-gray-100 flex items-center justify-center font-bold text-xs text-gray-500 border border-gray-200">
                                    <?= strtoupper(substr($order->lastname, 0, 1)) ?>
                                </div>
                                <span class="text-sm font-bold text-primary italic font-serif"><?= esc($order->firstname) ?> <?= esc($order->lastname) ?></span>
                            </div>
                        </td>
                        
                        <td class="px-8 py-5">
                            <?php 
                                $color = 'bg-gray-100 text-gray-600 border-gray-200';
                                switch($order->status) {
                                    case ORDER_PAID: case ORDER_DELIVERED: $color = 'bg-green-100 text-green-700 border-green-200'; break;
                                    case ORDER_PREPARING: case ORDER_SHIPPED: $color = 'bg-blue-100 text-blue-700 border-blue-200'; break;
                                    case ORDER_PENDING: $color = 'bg-orange-100 text-orange-700 border-orange-200'; break;
                                    case ORDER_CANCELLED: $color = 'bg-red-100 text-red-700 border-red-200'; break;
                                }
                                $statusText = $statusList[$order->status] ?? $order->status;
                            ?>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide border <?= $color ?>">
                                <?= esc($statusText) ?>
                            </span>
                        </td>

                        <td class="px-8 py-5 text-right font-serif font-bold text-xl text-primary tracking-tighter">
                            <?= $order->getFormattedPrice() ?> 
                        </td>
                        
                        <td class="px-8 py-5 text-right">
                            <span class="text-[10px] font-bold bg-gray-50 px-3 py-1.5 rounded border border-border text-muted uppercase tracking-tighter">
                                <?= (empty($order->order_date) || $order->order_date === '-') ? '-' : date('d/m/Y H:i', strtotime($order->order_date)) ?>
                            </span>
                        </td>

                        <td class="px-8 py-5 text-center">
                            <a href="<?= site_url('admin/orders/detail/' . $order->id) ?>" 
                               class="text-[10px] font-bold uppercase tracking-widest bg-white border border-border px-3 py-1.5 rounded hover:bg-primary hover:text-white hover:border-primary transition shadow-sm inline-flex items-center gap-1">
                               <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                               Détails
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="p-6 border-t border-border flex justify-center bg-gray-50/30">
            <?= $pager->links('orders', 'tailwind') ?>
        </div>
    </div>

<?= $this->endSection() ?>