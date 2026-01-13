<?= $this->extend('layouts/seller_section') ?>

<?= $this->section('header_content') ?>
    <div class="flex items-center gap-4">
        <div class="hidden md:flex items-center gap-4 bg-white/50 backdrop-blur-sm px-4 py-2 rounded-xl shadow-sm border border-border">
            <div class="text-right border-r border-border pr-4 mr-4">
                <span class="block text-xs font-bold text-muted uppercase">Total Produits</span>
                <span class="block text-xl font-bold text-primary"><?= ($stats['total'] ?? 0) ?></span>
            </div>
            <div class="text-right">
                <span class="block text-xs font-bold text-muted uppercase">Stock Faible</span>
                <span class="block text-xl font-serif font-bold text-accent"><?= ($stats['lowStock'] ?? 0) ?></span>
            </div>
        </div>

        <?php if (!empty($isSellerValidated)): ?>
            <a href="<?= base_url('seller/products/new') ?>" class="group flex items-center gap-2 px-5 py-2.5 bg-accent text-white rounded-xl hover:bg-accent-hover transition-all text-sm font-bold shadow-sm shadow-accent/20">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span class="hidden sm:inline">Nouveau Produit</span>
            </a>
        <?php else: ?>
            <button disabled title="Votre compte doit être validé pour ajouter des produits" class="group flex items-center gap-2 px-5 py-2.5 bg-gray-200 text-gray-400 rounded-xl cursor-not-allowed text-sm font-bold">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span class="hidden sm:inline">En attente de validation</span>
            </button>
        <?php endif; ?>
    </div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

    <!-- Barre de recherche et filtres -->
    <form action="<?= current_url() ?>" method="get" class="mb-8">
        <div class="flex flex-col md:flex-row gap-4 items-center">
            
            <?= $this->include('partials/search_bar', [
                'name' => 'search',
                'value' => $search ?? '',
                'placeholder' => 'Rechercher par titre, description...'
            ]) ?>

            <?php
            // Options 'en dur' pour éviter tout problème de constante non détectée
            $statusFilters = [
                'PENDING_VALIDATION' => 'En attente',
                'APPROVED' => 'Validé',
                'REFUSED' => 'Refusé',
            ];
            ?>

            <!-- Filtre Statut (Intégré directement pour affichage garanti) -->
            <div class="w-full md:w-56 relative border-l md:border-l-2 border-transparent md:border-gray-100 md:pl-4">
                <select name="status"
                        class="w-full pl-4 pr-10 py-2.5 bg-white border border-border rounded-xl focus:ring-2 focus:ring-accent/50 focus:border-accent appearance-none cursor-pointer shadow-sm font-medium text-gray-700"
                        onchange="this.form.submit()"
                >
                    <option value="">Tous les statuts</option>
                    <?php foreach ($statusFilters as $val => $label): ?>
                        <option value="<?= esc($val) ?>" <?= ($status ?? '') === $val ? 'selected' : '' ?>>
                            <?= esc($label) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-muted">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </div>

            <!-- Bouton Réinitialiser -->
            <?php if (!empty($search) || !empty($status)): ?>
                <a href="<?= current_url() ?>" class="w-full md:w-auto text-center px-4 py-2.5 border border-border text-muted font-bold rounded-xl hover:bg-gray-50 transition-colors">
                    Réinitialiser
                </a>
            <?php endif; ?>
        </div>
    </form>

    <!-- Products Grid -->
    <?php if (empty($myProducts)): ?>
        <div class="bg-white rounded-2xl shadow-sm border border-border p-16 text-center">
            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6 text-muted">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
            </div>
            <h3 class="text-xl font-bold text-primary mb-2 font-serif">Votre catalogue est vide</h3>
            <p class="text-muted mb-8 max-w-md mx-auto">Commencez dès maintenant à vendre vos produits en les ajoutant à votre catalogue.</p>
            <a href="<?= base_url('seller/products/new') ?>" class="inline-flex items-center gap-2 text-accent hover:text-accent-hover font-bold hover:underline">
                <span>Créer mon premier produit</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
            </a>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <?php foreach($myProducts as $p): ?>
                <?php
                    // Robust checking for status
                    $isPending = ($p->product_status === STATUS_PENDING || $p->product_status == 1 || $p->product_status === 'PENDING_VALIDATION');
                    $isApproved = ($p->product_status === STATUS_APPROVED || $p->product_status == 2 || $p->product_status === 'APPROVED');
                    // Anything else is considered refused/draft
                ?>
                <div class="group bg-white rounded-2xl shadow-sm border border-border overflow-hidden hover:shadow-lg hover:border-accent/30 transition-all duration-300 flex flex-col h-full relative">
                    
                    <!-- Image Area -->
                    <div class="aspect-[4/3] bg-gray-100 relative overflow-hidden group-hover:opacity-90 transition-opacity">
                        <!-- Link to Edit -->
                        <a href="<?= base_url('seller/products/' . $p->id . '/edit') ?>" class="absolute inset-0 z-10" aria-label="Modifier <?= esc($p->title) ?>"></a>
                        
                        <!-- Placeholder or Image -->
                        <?php 
                            // Use the Entity trait helper to resolve the correct URL (handles full URLs and 'default.jpg')
                            $finalImageUrl = $p->getImageUrl($p->image);
                        ?>
                        <img src="<?= $finalImageUrl ?>" alt="<?= esc($p->title) ?>" class="w-full h-full object-cover">
                        
                        <!-- Status Badge Overlay -->
                        <div class="absolute top-3 right-3 z-20 pointer-events-none">
                             <?php if($isPending): ?>
                                <span class="bg-amber-100/95 backdrop-blur-md text-amber-800 text-xs font-bold px-3 py-1.5 rounded-full shadow-sm flex items-center gap-1.5 border border-amber-200/50">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> En attente
                                </span>
                            <?php elseif($isApproved): ?>
                                <span class="bg-green-100/95 backdrop-blur-md text-green-800 text-xs font-bold px-3 py-1.5 rounded-full shadow-sm flex items-center gap-1.5 border border-green-200/50">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Validé
                                </span>
                            <?php else: ?>
                                <span class="bg-red-100/95 backdrop-blur-md text-red-800 text-xs font-bold px-3 py-1.5 rounded-full shadow-sm flex items-center gap-1.5 border border-red-200/50">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg> Refusé
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- content -->
                    <div class="p-5 flex-1 flex flex-col">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="font-bold text-lg text-primary group-hover:text-accent transition-colors line-clamp-1" title="<?= esc($p->title) ?>">
                                <?= esc($p->title) ?>
                            </h3>
                        </div>

                        <p class="text-2xl font-serif font-bold text-primary mb-2"><?= number_format($p->price, 2) ?> €</p>
                        
                        <div class="mt-auto space-y-3">
                            <div class="flex items-center justify-between text-sm py-3 border-t border-dashed border-border text-muted">
                                <span>Stock disponible</span>
                                <span class="font-mono font-bold <?= ($p->stock_available < 5) ? 'text-red-500' : 'text-primary' ?>">
                                    <?= $p->stock_available ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Actions -->
                    <div class="bg-gray-50 border-t border-border px-5 py-3 flex items-center justify-between z-20">
                         <a href="<?= base_url('seller/products/' . $p->id . '/edit') ?>" class="text-xs font-bold text-muted hover:text-accent uppercase tracking-wide transition-colors flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            Modifier
                        </a>
                        
                        <form action="<?= base_url('seller/products/' . $p->id) ?>" method="post" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ? Cette action est irréversible.');" class="inline">
                            <?= csrf_field() ?>
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors p-1.5 hover:bg-red-50 rounded-full" title="Supprimer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <div class="mt-8 flex justify-center">
            <?= $pager->links('default', 'tailwind') ?>
        </div>
    <?php endif; ?>

<?= $this->endSection() ?>