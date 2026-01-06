<?= $this->extend('admin/layout/base') ?>

<?= $this->section('header') ?>
    <?= view('admin/partials/header', [
        'title'    => 'Modération des Produits',
        'subtitle' => 'Validation et Conformité du catalogue',
        'action'   => '<a href="'. site_url('/') .'" class="text-xs font-bold text-muted hover:text-primary transition uppercase tracking-wide mr-4">Retour au site</a>'
    ]) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

    <?php if (!empty($pendingProducts) || $pager->getTotal('pending') > 0): ?>
    <div class="bg-white rounded-2xl shadow-md border-2 border-orange-100 overflow-hidden relative mb-8">
        <div class="bg-orange-50/50 px-8 py-4 border-b border-orange-100 flex items-center gap-3">
            <div class="relative flex h-3 w-3">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3 w-3 bg-orange-500"></span>
            </div>
            <h3 class="font-serif font-bold text-lg text-orange-800">En attente (<?= $pager->getTotal('pending') ?>)</h3>
        </div>
        
        <div class="p-8 grid gap-6">
            <?php foreach ($pendingProducts as $prod): ?>
            <div class="flex flex-col xl:flex-row items-start gap-6 p-4 border border-border rounded-xl hover:shadow-lg transition-all bg-white group">
                
                <div class="w-24 h-24 flex-shrink-0 bg-gray-100 rounded-lg overflow-hidden border border-border relative group-hover:border-accent transition-colors cursor-pointer"
                     onclick='openModal(<?= json_encode($prod, JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'>
                    <?php if(!empty($prod->image)): ?>
                        <img src="<?= base_url('uploads/products/' . $prod->image) ?>" class="w-full h-full object-cover">
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center text-gray-300"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></div>
                    <?php endif; ?>
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-all flex items-center justify-center">
                        <svg class="w-6 h-6 text-white opacity-0 group-hover:opacity-100 transition-opacity drop-shadow-md" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    </div>
                </div>

                <div class="flex-1 w-full pt-1">
                    <div class="flex justify-between items-start">
                        <h4 class="font-serif text-xl font-bold text-primary group-hover:text-accent transition-colors cursor-pointer" 
                            onclick='openModal(<?= json_encode($prod, JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'>
                            <?= esc($prod->title) ?>
                        </h4>
                        <button onclick='openModal(<?= json_encode($prod, JSON_HEX_APOS | JSON_HEX_QUOT) ?>)' class="text-gray-400 hover:text-accent p-1" title="View details">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        </button>
                    </div>
                    <div class="flex flex-wrap gap-4 mt-2 text-sm text-muted">
                        <span class="flex items-center gap-1"> <strong class="text-primary"><?= esc($prod->shop_name) ?></strong></span>
                        <span class="flex items-center gap-1"> <strong class="text-accent"><?= number_format($prod->price, 2) ?> €</strong></span>
                        <span class="flex items-center gap-1"> <?= date('d/m/Y', strtotime($prod->created_at)) ?></span>
                    </div>
                    <p class="text-sm text-gray-500 mt-2 line-clamp-1 italic"><?= esc($prod->short_description ?? '...') ?></p>
                </div>

                <div class="flex flex-col sm:flex-row items-center gap-2 w-full xl:w-auto mt-2 xl:mt-0">
                    <a href="<?= site_url('admin/products/approve/' . $prod->id) ?>" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-bold shadow-md hover:shadow-lg transition w-full sm:w-auto text-center text-sm">Valider</a>
                    <form action="<?= site_url('admin/products/reject/' . $prod->id) ?>" method="post" class="flex gap-2 w-full sm:w-auto">
                        <?= csrf_field() ?>
                        <input type="text" name="reason" class="flex-1 px-3 py-2 border border-border rounded-lg text-sm focus:ring-2 focus:ring-red-200 outline-none" placeholder="Raison...">
                        <button type="submit" class="bg-white text-red-600 border border-red-200 hover:bg-red-50 px-4 py-2 rounded-lg font-bold transition text-sm">Refuser</button>
                    </form>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="px-6 py-4 border-t border-orange-100 flex justify-center bg-orange-50/30">
            <?= $pager->links('pending', 'tailwind') ?>
        </div>
    </div>
    <?php endif; ?>

    <div class="bg-white rounded-2xl shadow-sm border border-border overflow-hidden flex flex-col">
        <div class="px-8 py-6 border-b border-border bg-gray-50/30 flex justify-between items-center">
            <h2 class="font-serif font-bold text-xl text-primary">Catalogue Global</h2>
            <span class="bg-gray-100 text-muted text-[10px] px-3 py-1 rounded-full uppercase font-bold tracking-tighter border border-border">
                Total : <?= $pager->getTotal('catalog') ?? 0 ?>
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-white text-[10px] uppercase text-muted font-bold border-b border-border tracking-widest">
                    <tr>
                        <th class="px-8 py-5">Image</th>
                        <th class="px-8 py-5">Produit</th>
                        <th class="px-8 py-5">Vendeur</th>
                        <th class="px-8 py-5">Statut</th>
                        <th class="px-8 py-5 text-right">Prix</th>
                        <th class="px-8 py-5 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border text-sm">
                    <?php foreach ($allProducts as $prod): ?>
                    <tr class="hover:bg-cream transition-colors">
                        <td class="px-8 py-4">
                            <div class="w-10 h-10 rounded-lg bg-gray-100 overflow-hidden border border-border cursor-pointer" 
                                 onclick='openModal(<?= json_encode($prod, JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'>
                                <?php if(!empty($prod->image)): ?>
                                    <img src="<?= base_url('uploads/products/' . $prod->image) ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <div class="w-full h-full flex items-center justify-center text-gray-300"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></div>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td class="px-8 py-4">
                            <div class="font-bold text-primary cursor-pointer hover:text-accent transition-colors"
                                 onclick='openModal(<?= json_encode($prod, JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'>
                                <?= esc($prod->title) ?>
                            </div>
                            <div class="text-xs text-muted mt-0.5"><?= esc($prod->category_name) ?></div>
                        </td>
                        <td class="px-8 py-4">
                            <span class="text-xs font-bold text-muted bg-gray-100 px-2 py-1 rounded border border-gray-200">
                                <?= esc($prod->shop_name) ?>
                            </span>
                        </td>
                        <td class="px-8 py-4">
                            <?php if($prod->status === 'PUBLISHED'): ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-green-100 text-green-800 border border-green-200 uppercase tracking-wide">
                                    Publié
                                </span>
                            <?php elseif($prod->status === 'REJECTED'): ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-red-100 text-red-800 border border-red-200 uppercase tracking-wide">
                                    Rejeté
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-orange-100 text-orange-800 border border-orange-200 uppercase tracking-wide">
                                    En attente
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-8 py-4 text-right font-mono font-bold text-primary">
                            <?= number_format($prod->price, 2) ?> €
                        </td>
                        <td class="px-8 py-4 text-center">
                            <a href="javascript:void(0)" 
                               onclick="openDeleteModal('<?= site_url('admin/products/delete/' . $prod->id) ?>')"
                               class="text-red-400 hover:text-red-600 font-bold transition p-2 hover:bg-red-50 rounded-lg inline-block">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="p-6 border-t border-border flex justify-center bg-gray-50/30">
            <?= $pager->links('catalog', 'tailwind') ?>
        </div>
    </div>

<?= $this->endSection() ?>

<?= $this->section('modals') ?>
    <div id="productModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closeModal()"></div>

        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center">
                
                <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all w-full max-w-2xl border border-white/20">
                    
                    <div class="bg-gray-50 px-6 py-4 border-b border-border flex justify-between items-center">
                        <h3 class="font-serif text-xl font-bold text-primary">Product Details</h3>
                        <button onclick="closeModal()" class="text-gray-400 hover:text-red-500 transition rounded-full p-1 hover:bg-red-50">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <div class="px-6 py-6">
                        <div class="flex flex-col md:flex-row gap-8">
                            
                            <div class="w-full md:w-1/2">
                                <div class="aspect-square bg-gray-100 rounded-xl overflow-hidden border border-border flex items-center justify-center shadow-inner relative group">
                                    <img id="modal-img" src="" alt="Product" class="w-full h-full object-cover hidden transition-transform duration-500 group-hover:scale-105">
                                    <div id="modal-no-img" class="flex flex-col items-center gap-2 text-gray-400 hidden">
                                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <span class="text-xs uppercase tracking-widest font-bold">No Image</span>
                                    </div>
                                </div>
                            </div>

                            <div class="w-full md:w-1/2 flex flex-col justify-between">
                                <div class="space-y-5">
                                    <div>
                                        <p class="text-[10px] font-bold text-muted uppercase tracking-widest mb-1">Product</p>
                                        <h4 id="modal-titre" class="font-serif text-2xl font-bold text-primary leading-tight"></h4>
                                    </div>

                                    <div class="flex items-end justify-between border-b border-border pb-4">
                                        <div>
                                            <p class="text-[10px] font-bold text-muted uppercase tracking-widest mb-1">Price</p>
                                            <p id="modal-prix" class="text-3xl font-bold text-accent font-serif tracking-tighter"></p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-[10px] font-bold text-muted uppercase tracking-widest mb-1">Stock</p>
                                            <p id="modal-stock" class="text-lg font-bold text-primary font-mono"></p>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-[10px] font-bold text-muted uppercase tracking-widest mb-1">Seller</p>
                                            <p id="modal-vendeur" class="text-sm font-bold text-gray-700"></p>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-bold text-muted uppercase tracking-widest mb-1">Category</p>
                                            <p id="modal-categorie" class="text-sm font-bold text-gray-700"></p>
                                        </div>
                                    </div>

                                    <div>
                                        <p class="text-[10px] font-bold text-muted uppercase tracking-widest mb-2">Description</p>
                                        <div class="bg-gray-50 p-3 rounded-lg border border-border">
                                            <p id="modal-desc" class="text-xs text-gray-600 leading-relaxed italic max-h-32 overflow-y-auto custom-scrollbar"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 px-6 py-4 border-t border-border flex justify-end">
                        <button onclick="closeModal()" class="text-xs font-bold uppercase tracking-widest text-muted hover:text-primary transition px-4 py-2">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?= view('admin/partials/delete_modal') ?>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const modal = document.getElementById('productModal');
    const modalImg = document.getElementById('modal-img');
    const modalNoImg = document.getElementById('modal-no-img');

    function openModal(prod) {
        document.getElementById('modal-titre').textContent = prod.title || prod.titre;
        
        const prixRaw = prod.price || prod.prix;
        document.getElementById('modal-prix').textContent = new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR' }).format(prixRaw);
        
        document.getElementById('modal-stock').textContent = prod.stock || prod.stock_available || prod.stock_disponible;
        document.getElementById('modal-vendeur').textContent = prod.shop_name || prod.nom_boutique;
        document.getElementById('modal-categorie').textContent = prod.category_name || prod.nom_categorie || 'Uncategorized';
        
        const desc = prod.description || prod.long_description || prod.short_description || prod.description_courte || 'No description available.';
        document.getElementById('modal-desc').textContent = desc;

        // Image Handling
        const imageFile = prod.image; 
        if (imageFile) {
            modalImg.src = '<?= base_url('uploads/products/') ?>' + '/' + imageFile;
            modalImg.classList.remove('hidden');
            modalNoImg.classList.add('hidden');
        } else {
            modalImg.classList.add('hidden');
            modalNoImg.classList.remove('hidden');
        }

        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeModal() {
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }
    
    // Close with Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === "Escape") closeModal();
    });
</script>
<?= $this->endSection() ?>

<?= $this->section('modals') ?>
    <?= view('admin/partials/delete_modal') ?>
<?= $this->endSection() ?>