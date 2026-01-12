<div id="productModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closeModal()"></div>

    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center">
            
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all w-full max-w-2xl border border-white/20">
                
                <div class="bg-gray-50 px-6 py-4 border-b border-border flex justify-between items-center">
                    <h3 class="font-serif text-xl font-bold text-primary">Détails du produit</h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-red-500 transition rounded-full p-1 hover:bg-red-50">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="px-6 py-6">
                    <div class="flex flex-col md:flex-row gap-8">
                        
                        <div class="w-full md:w-1/2 flex flex-col gap-4">
                            <div class="aspect-square bg-gray-100 rounded-xl overflow-hidden border border-border flex items-center justify-center shadow-inner relative group">
                                <img id="modal-main-img" src="" alt="Product" class="w-full h-full object-contain hidden transition-transform duration-500 group-hover:scale-105">
                                <div id="modal-no-img" class="flex flex-col items-center gap-2 text-gray-400 hidden">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <span class="text-xs uppercase tracking-widest font-bold">Aucune image</span>
                                </div>
                            </div>
                            <div id="modal-thumbnails" class="flex gap-2 justify-center mt-4">
                            </div>
                        </div>

                        <div class="w-full md:w-1/2 flex flex-col justify-between">
                            <div class="space-y-5">
                                <div>
                                    <p class="text-[10px] font-bold text-muted uppercase tracking-widest mb-1">Produit</p>
                                    <h4 id="modal-titre" class="font-serif text-2xl font-bold text-primary leading-tight"></h4>
                                </div>

                                <div class="flex items-end justify-between border-b border-border pb-4">
                                    <div>
                                        <p class="text-[10px] font-bold text-muted uppercase tracking-widest mb-1">Prix</p>
                                        <p id="modal-prix" class="text-3xl font-bold text-accent font-serif tracking-tighter"></p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-[10px] font-bold text-muted uppercase tracking-widest mb-1">Stock</p>
                                        <p id="modal-stock" class="text-lg font-bold text-primary font-mono"></p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-[10px] font-bold text-muted uppercase tracking-widest mb-1">Vendeur</p>
                                        <p id="modal-vendeur" class="text-sm font-bold text-gray-700"></p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold text-muted uppercase tracking-widest mb-1">Catégorie</p>
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
                    <button onclick="closeModal()" class="text-xs font-bold uppercase tracking-widest text-muted hover:text-primary transition px-4 py-2">Fermer</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const modal = document.getElementById('productModal');
    const modalMainImg = document.getElementById('modal-main-img'); 
    const modalNoImg = document.getElementById('modal-no-img');
    const modalThumbnails = document.getElementById('modal-thumbnails'); 

    function openModal(prod) {
        console.log('Opening modal for product:', prod);
        document.getElementById('modal-titre').textContent = prod.title || prod.titre;
        
        const prixRaw = prod.price || prod.prix;
        document.getElementById('modal-prix').textContent = new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR' }).format(prixRaw);
        
        document.getElementById('modal-stock').textContent = prod.stock || prod.stock_available || prod.stock_disponible;
        document.getElementById('modal-vendeur').textContent = prod.shop_name || prod.nom_boutique;
        document.getElementById('modal-categorie').textContent = prod.category_name || prod.nom_categorie || 'Uncategorized';
        
        const desc = prod.description || prod.long_description || prod.short_description || prod.description_courte || 'No description available.';
        document.getElementById('modal-desc').textContent = desc;

        // Image Handling
        modalThumbnails.innerHTML = '';
        modalMainImg.classList.add('hidden');
        modalNoImg.classList.add('hidden');

        // Extract images
        let images = [];
        if (prod.images && typeof prod.images === 'string') {
            images = prod.images.split(',')
                .map(i => i.trim())
                .filter(i => i !== '' && i !== 'default.jpg')
                .slice(0, 5); 
        } else if (prod.image && prod.image !== 'default.jpg') {
            images = [prod.image];
        }

        // Fix path: remove trailing slash from base_url if present, then add single slash
        const baseUrl = '<?= rtrim(base_url('uploads/products/'), '/') ?>/';
        
        // Helper to resolve image URL
        const getImageUrl = (img) => {
            if (img.startsWith('http') || img.startsWith('//') || img.startsWith('data:')) {
                return img;
            }
            return baseUrl + prod.id + '/' + img;
        };

        if (images.length > 0 && prod.id) {
            const mainImgSrc = getImageUrl(images[0]);
            console.log('Resolved Main Image:', mainImgSrc);

            modalMainImg.src = mainImgSrc;
            modalMainImg.classList.remove('hidden');
            modalNoImg.classList.add('hidden');

            // Generate Thumbnails
            if (images.length > 1) {
                images.forEach((img, index) => {
                    const thumbSrc = getImageUrl(img);
                    const thumbDiv = document.createElement('div');
                    const isActive = index === 0 ? 'border-primary ring-2 ring-primary/20 scale-105' : 'border-transparent opacity-60 hover:opacity-100 hover:scale-105';
                    thumbDiv.className = `w-14 h-14 rounded-lg border-2 ${isActive} overflow-hidden cursor-pointer flex-shrink-0 transition-all duration-200 bg-white shadow-sm`;
                    thumbDiv.innerHTML = `<img src="${thumbSrc}" class="w-full h-full object-cover">`;
                    
                    thumbDiv.onclick = () => {
                        modalMainImg.style.opacity = '0';
                        setTimeout(() => {
                            modalMainImg.src = thumbSrc;
                            modalMainImg.style.opacity = '1';
                        }, 150);
                        
                        Array.from(modalThumbnails.children).forEach(child => {
                            child.className = 'w-14 h-14 rounded-lg border-2 border-transparent opacity-60 hover:opacity-100 hover:scale-105 overflow-hidden cursor-pointer flex-shrink-0 transition-all duration-200 bg-white shadow-sm';
                        });
                        thumbDiv.className = 'w-14 h-14 rounded-lg border-2 border-primary ring-2 ring-primary/20 scale-105 overflow-hidden cursor-pointer flex-shrink-0 transition-all duration-200 bg-white shadow-sm';
                    };
                    
                    modalThumbnails.appendChild(thumbDiv);
                });
            }
        } else {
            console.warn('No valid image found', prod);
            modalMainImg.classList.add('hidden'); 
            modalMainImg.src = '';
            modalNoImg.classList.remove('hidden');
        }

        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeModal() {
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }
    
    document.addEventListener('keydown', function(event) {
        if (event.key === "Escape") closeModal();
    });
</script>
