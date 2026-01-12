<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<div class="max-w-[1200px] mx-auto px-6">
            
            <?php if (!empty($items)): ?>
                
                <div class="mb-10">
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="bg-red-50 text-red-600 px-4 py-3 rounded-lg mb-6 border border-red-100 font-medium text-sm flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>
                    <h2 class="font-serif text-3xl md:text-4xl text-gray-900">Votre Panier (<?= count($items) ?>)</h2>
                </div>

                <div class="lg:grid lg:grid-cols-12 lg:gap-16 items-start">
                    
                    <div class="lg:col-span-7">
                        <div class="space-y-0">
                            <?php foreach ($items as $item): ?>
                                <div class="flex gap-6 py-8 border-b border-gray-100 bg-gray-50/50 p-6 rounded-xl mb-4">
                                    <div class="flex-shrink-0 w-24 h-24 sm:w-32 sm:h-32 bg-gray-200 rounded-lg overflow-hidden">
                                        <img src="<?= $item->getProductImage() ?>" alt="<?= esc($item->getProductName()) ?>" class="w-full h-full object-cover mix-blend-multiply">
                                    </div>

                                    <div class="flex-1 flex flex-col justify-center">
                                        <div class="text-xs text-gray-500 uppercase tracking-wide mb-1">Classic</div>
                                        <h3 class="font-serif text-xl text-gray-900 mb-1">
                                            <a href="<?= $item->getProductLink() ?>" class="hover:underline decoration-1 underline-offset-4">
                                                <?= esc($item->getProductName()) ?>
                                            </a>
                                        </h3>
                                        
                                        <div class="flex items-center gap-2 mt-1">
                                            <span class="text-sm text-gray-500 font-mono"><?= $item->product_id ?></span>
                                            <a href="<?= base_url('cart/remove/' . $item->product_id) ?>" class="text-sm text-red-500 hover:text-red-700 font-medium ml-2">Supprimer</a>
                                        </div>
                                    </div>

                                    <div class="text-right flex flex-col justify-between items-end">
                                        <div class="text-lg font-bold text-gray-900 font-serif tracking-wide"><?= $item->getFormattedUnitPrice() ?></div>
                                        
                                        <form action="<?= base_url('cart/update') ?>" method="post">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="product_id" value="<?= $item->product_id ?>">
                                            <select name="quantity" onchange="this.form.submit()" class="block w-16 rounded border border-gray-300 bg-white py-1.5 px-2 text-sm focus:border-gray-500 focus:ring-0 cursor-pointer shadow-sm">
                                                <?php 
                                                $maxQty = ($item->stock_available < 10) ? $item->stock_available : 10;
                                                $maxQty = max(1, $maxQty);
                                                for ($i = 1; $i <= $maxQty; $i++): 
                                                ?>
                                                    <option value="<?= $i ?>" <?= ($item->quantity == $i) ? 'selected' : '' ?>><?= $i ?></option>
                                                <?php endfor; ?>
                                            </select>
                                        </form>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="mt-10 lg:mt-0 lg:col-span-5">
                        <div class="bg-white rounded-2xl p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 sticky top-28">
                            <h3 class="font-serif text-2xl text-gray-900 mb-8">Récapitulatif</h3>
                            
                            <div class="flex justify-between text-gray-600 mb-4 font-medium">
                                <span>Sous-total</span>
                                <span><?= $cart->getFormattedTotal() ?></span>
                            </div>
                            
                            <div class="flex justify-between text-gray-600 mb-6 font-medium">
                                <span>Livraison</span>
                                <span class="text-green-600">Gratuite</span>
                            </div>

                            <div class="border-t border-gray-100 my-6"></div>

                            <div class="flex justify-between items-center mb-8">
                                <span class="text-xl font-bold text-gray-900 font-serif">Total</span>
                                <span class="text-2xl font-bold text-gray-900 font-serif"><?= $cart->getFormattedTotal() ?></span>
                            </div>

                            <a href="<?= base_url('checkout') ?>" class="block w-full text-center bg-[#111827] text-white py-4 rounded-full font-bold text-sm hover:bg-gray-800 transition shadow-lg transform active:scale-[0.99]">
                                Passer la commande
                            </a>
                            
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
                    <a href="<?= base_url('catalog') ?>" class="bg-[#111827] text-white px-8 py-3 rounded-full font-bold text-sm hover:bg-gray-800 transition shadow-md">
                        Parcourir le catalogue
                    </a>
                </div>
            <?php endif; ?>
        </div>


<?= $this->endSection() ?>