<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<div class="max-w-[1600px] mx-auto px-[5%] py-12">
            
            <?php if (!empty($items)): ?>
                
                <div class="mb-10">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <h2 class="font-serif text-3xl md:text-4xl text-gray-900">Votre Panier (<?= count($items) ?>)</h2>
                        
                        <a href="<?= base_url('cart/clear') ?>" onclick="return confirm('Êtes-vous sûr de vouloir vider tout votre panier ?')" class="text-xs font-bold text-red-500 hover:text-red-700 uppercase tracking-widest flex items-center gap-1.5 transition-colors group py-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 transition-transform group-hover:scale-110">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                            </svg>
                            Tout supprimer
                        </a>
                    </div>
                </div>

                <div class="lg:grid lg:grid-cols-12 lg:gap-16 items-start">
                    
                    <div class="lg:col-span-8">
                        <div class="space-y-6">
                            <?php foreach ($items as $item): ?>
                                <article class="group relative flex gap-6 p-6 bg-white border border-gray-100 rounded-2xl transition-all duration-300 hover:shadow-lg hover:border-accent/20">
                                    <!-- Image -->
                                    <div class="flex-shrink-0 w-32 h-32 bg-gray-100 rounded-xl overflow-hidden relative">
                                        <img src="<?= $item->getProductImage() ?>" alt="<?= esc($item->getProductName()) ?>" class="w-full h-full object-cover mix-blend-multiply transition-transform duration-500 group-hover:scale-105">
                                    </div>

                                    <!-- Content -->
                                    <div class="flex-1 flex flex-col min-w-0">
                                        <div class="mb-auto">
                                            <!-- Category -->
                                            <span class="text-[10px] font-bold tracking-widest text-accent uppercase mb-1 block">
                                                <?= esc($item->category_name ?? 'Collection') ?>
                                            </span>
                                            
                                            <!-- Title -->
                                            <h3 class="font-serif text-xl font-bold text-primary truncate mb-2">
                                                <a href="<?= $item->getProductLink() ?>" class="hover:text-accent transition-colors">
                                                    <?= esc($item->getProductName()) ?>
                                                </a>
                                            </h3>

                                            <!-- Short Description -->
                                            <p class="text-sm text-muted line-clamp-2 leading-relaxed hidden sm:block">
                                                <?= esc($item->short_description ?? '') ?>
                                            </p>
                                        </div>
                                        
                                        <!-- Footer Actions -->
                                        <div class="flex items-center gap-4 mt-3">
                                            <a href="<?= base_url('cart/remove/' . $item->product_id) ?>" class="text-sm text-red-500 hover:text-red-700 font-medium hover:underline underline-offset-2 flex items-center gap-1 transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                  <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                </svg>
                                                Supprimer
                                            </a>
                                        </div>
                                    </div>

                                    <!-- Price & Quantity -->
                                    <div class="flex flex-col items-end justify-between border-l border-gray-100 pl-6 min-w-[120px]">
                                        <div class="text-xl font-bold text-primary font-sans whitespace-nowrap">
                                            <?= $item->getFormattedUnitPrice() ?>
                                        </div>
                                        
                                        <form action="<?= base_url('cart/update') ?>" method="post">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="product_id" value="<?= $item->product_id ?>">
                                            <div class="relative">
                                                <select name="quantity" onchange="this.form.submit()" class="appearance-none w-20 bg-gray-50 border border-gray-200 text-gray-700 py-2 pl-3 pr-8 rounded-lg focus:outline-none focus:ring-2 focus:ring-accent/20 focus:border-accent text-sm font-bold cursor-pointer hover:bg-white transition-colors">
                                                    <?php 
                                                    $maxQty = ($item->stock_available < 10) ? $item->stock_available : 10;
                                                    $maxQty = max(1, $maxQty);
                                                    for ($i = 1; $i <= $maxQty; $i++): 
                                                    ?>
                                                        <option value="<?= $i ?>" <?= ($item->quantity == $i) ? 'selected' : '' ?>><?= $i ?></option>
                                                    <?php endfor; ?>
                                                </select>
                                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500">
                                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </article>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="mt-10 lg:mt-0 lg:col-span-4">
                        <div class="bg-white rounded-2xl p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 sticky top-28">
                            <h3 class="font-serif text-2xl text-gray-900 mb-8">Récapitulatif</h3>
                            
                            <!-- Detail des articles -->
                            <div class="space-y-3 mb-6">
                                <?php foreach ($items as $item): ?>
                                    <div class="flex justify-between text-sm">
                                        <div class="flex flex-col flex-1 pr-4">
                                            <span class="font-medium text-gray-800 line-clamp-1"><?= esc($item->getProductName()) ?></span>
                                            <span class="text-xs text-gray-500"><?= $item->quantity ?> x <?= $item->getFormattedUnitPrice() ?></span>
                                        </div>
                                        <span class="font-medium text-gray-700 whitespace-nowrap"><?= $item->getFormattedSubtotal() ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <div class="border-t border-gray-100 my-4"></div>

                            <div class="flex justify-between items-center mb-8">
                                <span class="text-xl font-bold text-gray-900 font-serif">Total</span>
                                <span class="text-2xl font-bold text-gray-900 font-serif"><?= $cart->getFormattedTotal() ?></span>
                            </div>

                            <?= view('partials/black_button', [
                                'url' => base_url('checkout'),
                                'label' => 'Passer la commande',
                                'customClass' => 'w-full shadow-lg text-sm uppercase tracking-widest',
                                'padding' => 'py-4'
                            ]) ?>
                            
                            <p class="text-center mt-5 text-xs text-gray-400 flex items-center justify-center gap-1.5 font-medium">
                                🔒 Paiement 100% sécurisé
                            </p>
                        </div>
                    </div>

                </div>

            <?php else: ?>
                <div class="flex flex-col justify-center items-center min-h-[50vh] text-center px-4">
                    <div class="bg-gray-50 p-6 rounded-full mb-6">
                        <img src="https://cdn-icons-png.flaticon.com/512/11329/11329060.png" alt="Panier vide" class="w-16 opacity-20">
                    </div>
                    <h2 class="font-serif text-3xl text-gray-900 mb-3">Votre panier est vide</h2>
                    <p class="text-gray-500 mb-8 max-w-md mx-auto leading-relaxed">Il semble que vous n'ayez pas encore trouvé votre bonheur. Explorez notre catalogue pour dénicher des merveilles.</p>
                    <?= view('partials/black_button', [
                        'url' => base_url('catalog'),
                        'label' => 'Parcourir le catalogue',
                        'customClass' => 'shadow-md text-sm uppercase tracking-widest',
                        'padding' => 'px-8 py-3'
                    ]) ?>
                </div>
            <?php endif; ?>
        </div>


<?= $this->endSection() ?>