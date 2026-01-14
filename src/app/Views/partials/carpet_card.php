<article class="group bg-white border border-transparent rounded-2xl overflow-hidden transition-all duration-300 flex flex-col relative hover:-translate-y-2 hover:shadow-xl">
    <a href="<?= base_url('product/' . $product->alias) ?>" class="relative block pt-[130%] overflow-hidden bg-gray-100 rounded-t-2xl">
        <?php
            $imageName = $product->image ?? $product->file_name ?? 'default.jpg';
            
            if (strpos($imageName, 'http') === 0) {
                $imgSrc = $imageName;
            } else {
                $productId = $product->id ?? $product->id_product ?? 0;
                $imgSrc = base_url('uploads/products/' . $productId . '/' . $imageName);
            }
        ?>
        <img src="<?= esc($imgSrc) ?>" 
             alt="<?= esc($product->title) ?>" 
             class="absolute top-0 left-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
             onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1600166898405-da9535204843?q=80&w=400';">
    </a>
    
    <div class="p-6 flex-1 flex flex-col">
        <div>
            <h3 class="text-xl mb-1.5 font-serif font-bold text-gray-900 leading-tight">
                <a href="<?= base_url('product/' . $product->alias) ?>">
                    <?= esc($product->title) ?>
                </a>
            </h3>
            <p class="text-sm text-slate-500 mb-4 line-clamp-2"><?= esc($product->short_description) ?></p>
        </div>
        
        <div class="mt-auto flex justify-between items-center pt-5 border-t border-slate-100">
            <span class="text-2xl font-bold text-gray-900 font-sans">
                <?= method_exists($product, 'getFormattedPrice') ? $product->getFormattedPrice() : number_format($product->price, 2) . ' €' ?>
            </span>
            
            <div class="cart-action">
                <?= csrf_field() ?>
                <input type="hidden" name="product_id" value="<?= $product->id ?? $product->id_product ?>">
                <input type="hidden" name="quantity" value="1">
                
                <?php 
                    $role = function_exists('user_role') ? user_role() : null;
                    $isAdminOrSeller = ($role === \App\Enums\UserRole::ADMIN || $role === \App\Enums\UserRole::SELLER);
                ?>

                <?php if ($isAdminOrSeller): ?>
                    <button type="button" disabled class="px-5 py-2.5 text-sm inline-flex items-center justify-center gap-2 bg-gray-100 text-gray-400 font-bold rounded cursor-not-allowed border border-gray-200" title="Les vendeurs et administrateurs ne peuvent pas acheter">
                        Acheter
                    </button>
                <?php else: ?>
                    <?= view('partials/black_button', [
                        'tag' => 'button',
                        'type' => 'button',
                        'label' => 'Acheter', 
                        'padding' => 'px-5 py-2.5', 
                        'customClass' => 'text-sm',
                        'onclick' => 'submitAddToCart(this)'
                    ]) ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</article>

<?php if (! isset($GLOBALS['cart_script_included'])): $GLOBALS['cart_script_included'] = true; ?>
<script>
function submitAddToCart(btn) {
    const container = btn.closest('.cart-action');
    const csrfName = '<?= csrf_token() ?>';
    const csrfInput = container.querySelector(`input[name="${csrfName}"]`);
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '<?= base_url('cart/add') ?>';
    form.style.display = 'none';
    
    if(csrfInput) {
        const iCsrf = document.createElement('input');
        iCsrf.name = csrfName;
        iCsrf.value = csrfInput.value;
        form.appendChild(iCsrf);
    }
    const iId = document.createElement('input');
    iId.name = 'product_id';
    iId.value = container.querySelector('input[name="product_id"]').value;
    form.appendChild(iId);
    const iQty = document.createElement('input');
    iQty.name = 'quantity';
    iQty.value = container.querySelector('input[name="quantity"]').value;
    form.appendChild(iQty);
    
    document.body.appendChild(form);
    form.submit();
}
</script>
<?php endif; ?>