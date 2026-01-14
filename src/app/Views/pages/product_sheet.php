<?= $this->extend("layouts/" . ($layout ?? "default")) ?>

<?= $this->section("content") ?>

    <div class="max-w-[1600px] mx-auto px-[5%] py-8">

        <!-- Breadcrumb -->
        <nav class="text-sm text-muted mb-8">
            <a href="<?= base_url('/') ?>" class="hover:text-accent">Accueil</a> / 
            <a href="<?= base_url('catalog') ?>" class="hover:text-accent">Catalogue</a> / 
            <span class="text-primary font-bold"><?= esc($product->title) ?></span>
        </nav>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 mb-20">
            <!-- Gallery -->
            <div class="space-y-4">
                <div class="aspect-[4/5] bg-gray-100 rounded-xl overflow-hidden relative group">
                     <?php 
                        $firstPhotoUrl = 'https://images.unsplash.com/photo-1600166898405-da9535204843?q=80&w=400';
                        if(!empty($photos) && isset($photos[0])) {
                             $firstPhotoUrl = base_url('uploads/products/' . $photos[0]->product_id . '/' . $photos[0]->file_name);
                        }
                     ?>
                     <img id="mainImage" src="<?= $firstPhotoUrl ?>" alt="<?= esc($product->title) ?>" 
                          class="w-full h-full object-cover transition-all duration-300 transform group-hover:scale-105"
                          onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1600166898405-da9535204843?q=80&w=400';">
                </div>
                
                <div class="grid grid-cols-4 gap-4">
                    <?php if(!empty($photos)): ?>
                        <?php foreach($photos as $index => $photo): ?>
                            <?php 
                                $photoUrl = base_url('uploads/products/' . $photo->product_id . '/' . $photo->file_name);
                                $activeClass = ($index === 0) ? 'border-accent opacity-100' : 'border-transparent opacity-70';
                            ?>
                            <div class="thumbnail aspect-square rounded-lg overflow-hidden cursor-pointer hover:opacity-100 border hover:border-accent transition-all <?= $activeClass ?>"
                                 onclick="updateMainImage('<?= $photoUrl ?>', this)">
                                <img src="<?= $photoUrl ?>" class="w-full h-full object-cover" alt="Vue produit <?= $index + 1 ?>"
                                     onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1600166898405-da9535204843?q=80&w=400';">
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <script>
                function updateMainImage(src, thumb) {
                    const main = document.getElementById('mainImage');
                    
                    // Simple fade effect
                    main.style.opacity = '0.8';
                    
                    setTimeout(() => {
                        main.src = src;
                        main.style.opacity = '1';
                    }, 100);
                    
                    // Update active state
                    document.querySelectorAll('.thumbnail').forEach(el => {
                        el.classList.remove('border-accent', 'opacity-100');
                        el.classList.add('opacity-70', 'border-transparent');
                    });
                    
                    thumb.classList.remove('opacity-70', 'border-transparent');
                    thumb.classList.add('border-accent', 'opacity-100');
                }
                </script>
            </div>

            <!-- Product Info -->
            <div>
                <span class="text-accent font-bold tracking-widest text-xs uppercase mb-2 block"><?= esc($product->category_name ?? 'Collection') ?></span>
                <h1 class="font-serif text-4xl md:text-5xl font-bold text-primary mb-4"><?= esc($product->title) ?></h1>
                
                <div class="flex items-center gap-4 mb-6">
                    <span class="text-3xl font-bold text-primary"><?= $product->getFormattedPrice() ?></span>
                    <?php if($product->stock_available > 0): ?>
                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold uppercase">En stock</span>
                    <?php else: ?>
                        <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-bold uppercase">Rupture</span>
                    <?php endif; ?>
                </div>

                <div class="prose prose-sm text-muted mb-8">
                    <p><?= nl2br(esc((string)$product->short_description)) ?></p>
                </div>

                <!-- Add to Cart -->
                <form action="<?= base_url('cart/add') ?>" method="post" class="flex gap-4 mb-8 border-b border-border-light pb-8">
                    <?= csrf_field() ?>
                    <input type="hidden" name="product_id" value="<?= esc($product->id) ?>">
                    
                    <div class="w-24 border border-border rounded flex items-center justify-between px-3">
                         <button type="button" onclick="el=document.getElementById('qty'); v=parseInt(el.value); if(v>1) el.value=v-1" class="text-muted hover:text-primary focus:outline-none">-</button>
                         <input type="number" id="qty" name="quantity" value="1" min="1" max="<?= esc($product->stock_available) ?>" 
                                class="w-8 text-center font-bold border-none focus:ring-0 p-0 appearance-none bg-transparent [-moz-appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" readonly>
                         <button type="button" onclick="el=document.getElementById('qty'); v=parseInt(el.value); m=<?= (int)$product->stock_available ?>; if(v<m) el.value=v+1" class="text-muted hover:text-primary focus:outline-none">+</button>
                    </div>
                    
                    <?php 
                        use \App\Enums\UserRole;
                        $role = function_exists('user_role') ? user_role() : null;
                        $isAdminOrSeller = ($role === UserRole::ADMIN || $role === UserRole::SELLER);
                    ?>

                    <?php if ($isAdminOrSeller): ?>
                        <button type="button" disabled class="flex-1 bg-gray-200 text-gray-400 py-3.5 rounded font-bold uppercase tracking-widest cursor-not-allowed border border-gray-200" title="Les vendeurs et administrateurs ne peuvent pas acheter">
                            Action non autorisée
                        </button>
                    <?php elseif ($product->stock_available > 0): ?>
                        <button type="submit" class="flex-1 bg-primary text-white py-3.5 rounded font-bold uppercase tracking-widest hover:bg-accent transition-colors">
                            Ajouter au panier
                        </button>
                    <?php else: ?>
                        <button type="button" disabled class="flex-1 bg-gray-200 text-gray-400 py-3.5 rounded font-bold uppercase tracking-widest cursor-not-allowed">
                            Rupture de Stock
                        </button>
                    <?php endif; ?>
                </form>

                <!-- Meta -->
                <div class="space-y-4 text-sm">
                    <div class="flex border-b border-dashed border-gray-200 pb-2">
                        <span class="w-32 text-muted">Vendeur</span>
                        <span class="font-medium"><?= esc($product->shop_name ?? 'TapisMarket') ?></span>
                    </div>
                    <div class="flex border-b border-dashed border-gray-200 pb-2">
                        <span class="w-32 text-muted">Matière</span>
                        <span class="font-medium"><?= esc($product->material) ?></span>
                    </div>
                    <div class="flex border-b border-dashed border-gray-200 pb-2">
                        <span class="w-32 text-muted">Dimensions</span>
                        <span class="font-medium"><?= esc($product->dimensions) ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Details Tabs -->
        <div class="mb-20">
            <div class="border-b border-border-light flex gap-8 mb-8">
                <button class="pb-4 border-b-2 border-primary font-bold text-primary">Description</button>
            </div>
            <div class="max-w-3xl prose text-muted">
                <h3 class="font-serif text-xl font-bold text-primary mb-4">À propos de ce tapis</h3>
                <p><?= nl2br(esc((string)($product->long_description ?? $product->short_description))) ?></p>
            </div>
        </div>

        <!-- Similar Products -->
        <?php if(!empty($similarProducts)): ?>
        <section>
            <h2 class="font-serif text-3xl font-bold text-primary mb-8">Vous aimerez aussi</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                 <?php foreach($similarProducts as $simProduct): ?>
                    <?= view('partials/carpet_card', ['product' => $simProduct]) ?>
                 <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

    </div>
<?= $this->endSection() ?>
