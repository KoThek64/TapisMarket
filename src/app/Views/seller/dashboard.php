<?= $this->extend('layouts/seller_section') ?>

<?= $this->section('header_content') ?>
    <a href="<?= site_url('/') ?>" class="group flex items-center gap-2 px-5 py-2.5 bg-white border border-border rounded-full hover:border-accent hover:text-accent transition-all text-sm font-bold shadow-sm">
        <span>Aller sur le site</span>
        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
    </a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
            
            <!-- Revenue -->
            <div class="bg-gradient-to-br from-primary to-gray-800 text-white p-6 rounded-2xl shadow-xl relative overflow-hidden group">
                <div class="relative z-10">
                    <div class="flex items-center gap-3 mb-4 text-gray-300">
                        <div class="p-2 bg-white/10 rounded-lg"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
                        <span class="text-xs font-bold uppercase tracking-wider opacity-80">Revenus</span>
                    </div>
                     <h3 class="font-serif text-3xl font-bold tracking-tight"><?= number_format($totalRevenue, 2) ?> €</h3>
                    <p class="text-xs text-green-300 mt-2 font-medium flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                        Chiffre d'affaires
                    </p>
                </div>
                 <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-accent/20 rounded-full blur-2xl group-hover:bg-accent/30 transition-all duration-500"></div>
            </div>

            <!-- Total Orders -->
           <div class="bg-white p-6 rounded-2xl shadow-sm border border-border relative overflow-hidden group hover:shadow-md transition-all">
                <div class="flex justify-between items-start">
                    <div>
                        <div class="flex items-center gap-3 mb-4 text-muted">
                             <div class="p-2 bg-blue-50 text-blue-600 rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                             </div>
                            <span class="text-xs font-bold uppercase tracking-wider">Commandes</span>
                        </div>
                        <h3 class="font-serif text-3xl font-bold text-primary group-hover:text-accent transition-colors"><?= $totalOrders ?></h3>
                    </div>
                </div>
            </div>

            <!-- Average Rating -->
             <div class="bg-white p-6 rounded-2xl shadow-sm border border-border relative overflow-hidden group hover:shadow-md transition-all">
                <div class="flex justify-between items-start">
                    <div>
                        <div class="flex items-center gap-3 mb-4 text-muted">
                             <div class="p-2 bg-amber-50 text-amber-600 rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                             </div>
                            <span class="text-xs font-bold uppercase tracking-wider">Note Moyenne</span>
                        </div>
                        <h3 class="font-serif text-3xl font-bold text-primary group-hover:text-amber-500 transition-colors"><?= number_format($averageRating, 1) ?> <span class="text-lg text-muted">/5</span></h3>
                    </div>
                </div>
            </div>

            <!-- Pending Products -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-border relative overflow-hidden group hover:shadow-md transition-all">
                <div class="flex justify-between items-start">
                    <div>
                        <div class="flex items-center gap-3 mb-4 text-muted">
                             <div class="p-2 bg-purple-50 text-purple-600 rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                             </div>
                            <span class="text-xs font-bold uppercase tracking-wider">En Attente</span>
                        </div>
                        <h3 class="font-serif text-3xl font-bold text-primary group-hover:text-purple-600 transition-colors"><?= $pendingProducts ?></h3>
                        <?php if($pendingProducts > 0): ?>
                            <a href="<?= base_url('seller/products') ?>" class="text-xs text-accent mt-2 block hover:underline">Voir les produits</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>

        <!-- Recent Activity Section -->
        <div class="grid xl:grid-cols-2 gap-8">
            
            <!-- Recent Sales (Styled like Admin Orders) -->
            <div class="bg-white rounded-2xl shadow-sm border border-border overflow-hidden flex flex-col h-full">
                <div class="px-8 py-6 border-b border-border flex justify-between items-center bg-gray-50/30">
                    <h2 class="font-serif font-bold text-xl text-primary">Dernières ventes</h2>
                    <a href="<?= base_url('seller/orders') ?>" class="text-xs font-bold text-accent hover:text-primary uppercase tracking-wide transition font-sans">Tout voir →</a>
                </div>
                
                <div class="flex-1 overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-white text-xs uppercase text-muted font-bold border-b border-border">
                            <tr>
                                <th class="px-8 py-4">Référence</th>
                                <th class="px-8 py-4">Produit</th>
                                <th class="px-8 py-4 text-right">Montant</th>
                                <th class="px-8 py-4 text-right">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border">
                            <?php if(empty($recentSales)): ?>
                                <tr>
                                    <td colspan="4" class="px-8 py-8 text-center text-muted text-sm">Aucune vente récente.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach($recentSales as $sale): ?>
                                <tr class="hover:bg-cream transition-colors group">
                                    <td class="px-8 py-4">
                                        <div class="font-mono text-sm font-bold text-primary group-hover:text-accent transition-colors">
                                            #<?= esc($sale->reference) ?>
                                        </div>
                                    </td>
                                    <td class="px-8 py-4">
                                        <div class="text-sm font-medium text-gray-700">
                                            <?= esc($sale->title) ?>
                                            <span class="text-xs text-muted block">Qté: <?= $sale->quantity ?></span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-4 text-right">
                                        <span class="font-bold text-primary"><?= number_format($sale->unit_price * $sale->quantity, 2) ?> €</span>
                                    </td>
                                    <td class="px-8 py-4 text-right text-sm text-muted font-mono">
                                        <?= date('d/m', strtotime($sale->order_date)) ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Quick Actions (Styled container) -->
            <div class="bg-white rounded-2xl shadow-sm border border-border overflow-hidden flex flex-col h-full">
                <div class="px-8 py-6 border-b border-border flex justify-between items-center bg-gray-50/30">
                    <h2 class="font-serif font-bold text-xl text-primary">Actions rapides</h2>
                </div>
                <div class="p-8">
                     <div class="grid grid-cols-2 gap-4">
                        <a href="<?= base_url('seller/products/new') ?>" class="bg-cream p-6 rounded-xl border border-border hover:border-accent hover:shadow-md transition-all flex flex-col items-center justify-center gap-3 group h-32">
                            <div class="w-12 h-12 bg-accent/10 rounded-full flex items-center justify-center text-accent group-hover:bg-accent group-hover:text-white transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            </div>
                            <span class="font-bold text-sm text-primary group-hover:text-accent transition-colors">Ajouter un produit</span>
                        </a>
                        <a href="<?= base_url('seller/products') ?>" class="bg-cream p-6 rounded-xl border border-border hover:border-blue-500 hover:shadow-md transition-all flex flex-col items-center justify-center gap-3 group h-32">
                            <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                            </div>
                            <span class="font-bold text-sm text-primary group-hover:text-blue-600 transition-colors">Gérer le stock</span>
                        </a>
                    </div>
                </div>
            </div>
            
        </div>

    </div>

<?= $this->endSection() ?>
