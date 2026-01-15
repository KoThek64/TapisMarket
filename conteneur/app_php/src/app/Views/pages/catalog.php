<?= $this->extend("layouts/default") ?>

<?= $this->section("content") ?>

<!-- Main Content Layout -->
<form action="<?= base_url('catalog') ?>" method="get"
    class="max-w-[1600px] mx-auto px-[5%] py-8 grid grid-cols-1 lg:grid-cols-[280px_1fr] gap-10">

    <!-- Sidebar Filtres -->
    <aside class="hidden lg:block sticky top-24 max-h-[calc(100vh-150px)] overflow-y-auto pr-2">
        <h2 class="text-xl font-serif font-bold mb-4">Filtrer par</h2>

        <input type="hidden" name="active_section" id="active_section_input" value="<?= esc($activeSection ?? '') ?>">

        <!-- Categories -->
        <div class="border-b border-border-light py-6">
            <?php $isCatOpen = ($activeSection == 'categories' || !empty($selectedCategories)); ?>
            <button type="button"
                class="w-full flex justify-between items-center mb-4 cursor-pointer focus:outline-none group"
                onclick="toggleFilter(this, 'categories')">
                <span class="font-semibold text-[15px]">Catégories</span>
                <span class="text-xs transition-transform duration-200"
                    style="transform: rotate(<?= $isCatOpen ? '0deg' : '-90deg' ?>);">▼</span>
            </button>
            <div class="flex flex-col gap-3 <?= $isCatOpen ? '' : 'hidden' ?>">
                <?php if (!empty($categories)): ?>
                    <?php foreach ($categories as $category): ?>
                        <label class="flex items-center gap-3 text-sm cursor-pointer hover:text-accent transition-colors">
                            <input type="checkbox" name="cat[]" value="<?= esc($category->name) ?>"
                                class="w-4 h-4 accent-primary" <?= in_array($category->name, $selectedCategories ?? []) ? 'checked' : '' ?> onchange="setActiveSection('categories'); this.form.submit()">
                            <?= esc($category->name) ?>
                        </label>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-sm text-text-muted">Aucune catégorie disponible</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Materials -->
        <div class="border-b border-border-light py-6">
            <?php $isMatOpen = ($activeSection == 'materials' || !empty($selectedMaterials)); ?>
            <button type="button"
                class="w-full flex justify-between items-center mb-4 cursor-pointer focus:outline-none group"
                onclick="toggleFilter(this, 'materials')">
                <span class="font-semibold text-[15px]">Matière</span>
                <span class="text-xs transition-transform duration-200"
                    style="transform: rotate(<?= $isMatOpen ? '0deg' : '-90deg' ?>);">▼</span>
            </button>
            <div class="flex flex-col gap-3 <?= $isMatOpen ? '' : 'hidden' ?>">
                <?php if (!empty($materials)): ?>
                    <?php foreach ($materials as $material): ?>
                        <label class="flex items-center gap-3 text-sm cursor-pointer hover:text-accent transition-colors">
                            <input type="checkbox" name="mat[]" value="<?= esc($material->name) ?>"
                                class="w-4 h-4 accent-primary" <?= in_array($material->name, $selectedMaterials ?? []) ? 'checked' : '' ?> onchange="setActiveSection('materials'); this.form.submit()">
                            <?= esc($material->name) ?>
                        </label>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-sm text-text-muted">Aucune matière disponible</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Dimensions (Width & Length) -->
        <div class="border-b border-border-light py-6">
            <?php
            $isDimOpen = ($activeSection == 'dimensions' ||
                (isset($selectedWidthMin) && $selectedWidthMin > ($dimMinBound ?? 0)) ||
                (isset($selectedWidthMax) && $selectedWidthMax < ($dimMaxBound ?? 500)) ||
                (isset($selectedLengthMin) && $selectedLengthMin > ($dimMinBound ?? 0)) ||
                (isset($selectedLengthMax) && $selectedLengthMax < ($dimMaxBound ?? 500)));
            ?>
            <button type="button"
                class="w-full flex justify-between items-center mb-4 cursor-pointer focus:outline-none group"
                onclick="toggleFilter(this, 'dimensions')">
                <span class="font-semibold text-[15px]">Dimensions (cm)</span>
                <span class="text-xs transition-transform duration-200"
                    style="transform: rotate(<?= $isDimOpen ? '0deg' : '-90deg' ?>);">▼</span>
            </button>
            <div class="px-1 <?= $isDimOpen ? '' : 'hidden' ?>">

                <!-- Width Slider -->
                <div class="mb-6">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-3">Largeur</label>
                    <div class="relative w-full h-10 mb-2">
                        <input type="range" name="width_min" id="width_min" min="<?= $dimMinBound ?? 0 ?>"
                            max="<?= $dimMaxBound ?? 500 ?>" step="1" value="<?= $selectedWidthMin ?? 0 ?>"
                            class="absolute pointer-events-none appearance-none z-20 h-2 w-full opacity-0 cursor-pointer"
                            oninput="updateWidthInputs()">
                        <input type="range" name="width_max" id="width_max" min="<?= $dimMinBound ?? 0 ?>"
                            max="<?= $dimMaxBound ?? 500 ?>" step="1" value="<?= $selectedWidthMax ?? 500 ?>"
                            class="absolute pointer-events-none appearance-none z-20 h-2 w-full opacity-0 cursor-pointer"
                            oninput="updateWidthInputs()">

                        <div class="relative z-10 h-2 w-full bg-gray-200 rounded-lg">
                            <div class="absolute z-10 top-0 bottom-0 bg-accent rounded-lg" id="width-slider-track"
                                style="left: 0%; right: 0%;"></div>
                            <div class="absolute z-20 w-4 h-4 bg-white border-2 border-accent rounded-full -mt-1 -ml-2 cursor-pointer pointer-events-auto shadow-md hover:scale-110 transition-transform"
                                id="width-thumb-min" onmousedown="startDrag(event, 'width', 'min')"></div>
                            <div class="absolute z-20 w-4 h-4 bg-white border-2 border-accent rounded-full -mt-1 -ml-2 cursor-pointer pointer-events-auto shadow-md hover:scale-110 transition-transform"
                                id="width-thumb-max" onmousedown="startDrag(event, 'width', 'max')"></div>
                        </div>
                    </div>
                    <div class="flex items-center justify-between gap-2 text-sm">
                        <input type="number" id="input_width_min" value="<?= $selectedWidthMin ?? 0 ?>"
                            min="<?= $dimMinBound ?? 0 ?>" max="<?= $dimMaxBound ?? 500 ?>"
                            class="w-20 px-1 py-1 border border-border-light rounded text-center focus:outline-none focus:border-accent text-xs"
                            onchange="updateWidthSlider(this.value, 'min')">
                        <span class="text-gray-400">-</span>
                        <input type="number" id="input_width_max" value="<?= $selectedWidthMax ?? 500 ?>"
                            min="<?= $dimMinBound ?? 0 ?>" max="<?= $dimMaxBound ?? 500 ?>"
                            class="w-20 px-1 py-1 border border-border-light rounded text-center focus:outline-none focus:border-accent text-xs"
                            onchange="updateWidthSlider(this.value, 'max')">
                    </div>
                </div>

                <!-- Length Slider -->
                <div class="mb-2">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-3">Longueur</label>
                    <div class="relative w-full h-10 mb-2">
                        <input type="range" name="length_min" id="length_min" min="<?= $dimMinBound ?? 0 ?>"
                            max="<?= $dimMaxBound ?? 500 ?>" step="1" value="<?= $selectedLengthMin ?? 0 ?>"
                            class="absolute pointer-events-none appearance-none z-20 h-2 w-full opacity-0 cursor-pointer"
                            oninput="updateLengthInputs()">
                        <input type="range" name="length_max" id="length_max" min="<?= $dimMinBound ?? 0 ?>"
                            max="<?= $dimMaxBound ?? 500 ?>" step="1" value="<?= $selectedLengthMax ?? 500 ?>"
                            class="absolute pointer-events-none appearance-none z-20 h-2 w-full opacity-0 cursor-pointer"
                            oninput="updateLengthInputs()">

                        <div class="relative z-10 h-2 w-full bg-gray-200 rounded-lg">
                            <div class="absolute z-10 top-0 bottom-0 bg-accent rounded-lg" id="length-slider-track"
                                style="left: 0%; right: 0%;"></div>
                            <div class="absolute z-20 w-4 h-4 bg-white border-2 border-accent rounded-full -mt-1 -ml-2 cursor-pointer pointer-events-auto shadow-md hover:scale-110 transition-transform"
                                id="length-thumb-min" onmousedown="startDrag(event, 'length', 'min')"></div>
                            <div class="absolute z-20 w-4 h-4 bg-white border-2 border-accent rounded-full -mt-1 -ml-2 cursor-pointer pointer-events-auto shadow-md hover:scale-110 transition-transform"
                                id="length-thumb-max" onmousedown="startDrag(event, 'length', 'max')"></div>
                        </div>
                    </div>
                    <div class="flex items-center justify-between gap-2 text-sm">
                        <input type="number" id="input_length_min" value="<?= $selectedLengthMin ?? 0 ?>"
                            min="<?= $dimMinBound ?? 0 ?>" max="<?= $dimMaxBound ?? 500 ?>"
                            class="w-20 px-1 py-1 border border-border-light rounded text-center focus:outline-none focus:border-accent text-xs"
                            onchange="updateLengthSlider(this.value, 'min')">
                        <span class="text-gray-400">-</span>
                        <input type="number" id="input_length_max" value="<?= $selectedLengthMax ?? 500 ?>"
                            min="<?= $dimMinBound ?? 0 ?>" max="<?= $dimMaxBound ?? 500 ?>"
                            class="w-20 px-1 py-1 border border-border-light rounded text-center focus:outline-none focus:border-accent text-xs"
                            onchange="updateLengthSlider(this.value, 'max')">
                    </div>
                </div>

                <?= view('partials/black_button', [
                    'tag' => 'button',
                    'type' => 'submit',
                    'label' => 'Appliquer',
                    'onclick' => "setActiveSection('dimensions')",
                    'padding' => 'py-2',
                    'customClass' => 'mt-4 w-full text-[10px] uppercase font-bold shadow-none hover:shadow-md'
                ]) ?>
            </div>
        </div>

        <!-- Sellers -->
        <div class="border-b border-border-light py-6">
            <?php $isSellerOpen = ($activeSection == 'sellers' || !empty($selectedSellers)); ?>
            <button type="button"
                class="w-full flex justify-between items-center mb-4 cursor-pointer focus:outline-none group"
                onclick="toggleFilter(this, 'sellers')">
                <span class="font-semibold text-[15px]">Vendeurs</span>
                <span class="text-xs transition-transform duration-200"
                    style="transform: rotate(<?= $isSellerOpen ? '0deg' : '-90deg' ?>);">▼</span>
            </button>
            <div class="flex flex-col gap-3 <?= $isSellerOpen ? '' : 'hidden' ?>">
                <?php if (!empty($sellers)): ?>
                    <?php foreach ($sellers as $seller): ?>
                        <label class="flex items-center gap-3 text-sm cursor-pointer hover:text-accent transition-colors">
                            <input type="checkbox" name="seller[]" value="<?= esc($seller->name) ?>"
                                class="w-4 h-4 accent-primary" <?= in_array($seller->name, $selectedSellers ?? []) ? 'checked' : '' ?> onchange="setActiveSection('sellers'); this.form.submit()"> <?= esc($seller->name) ?>
                        </label>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-sm text-text-muted">Aucun vendeur disponible</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Price -->
        <div class="py-6">
            <?php
            $isPriceOpen = ($activeSection == 'price' || (isset($selectedPriceMin) && $selectedPriceMin > 0) || (isset($selectedPriceMax) && $selectedPriceMax < 5000));
            ?>
            <button type="button"
                class="w-full flex justify-between items-center mb-4 cursor-pointer focus:outline-none group"
                onclick="toggleFilter(this, 'price')">
                <span class="font-semibold text-[15px]">Budget</span>
                <span class="text-xs transition-transform duration-200"
                    style="transform: rotate(<?= $isPriceOpen ? '0deg' : '-90deg' ?>);">▼</span>
            </button>
            <div class="px-1 <?= $isPriceOpen ? '' : 'hidden' ?>">
                <div class="relative w-full h-10 mb-4">
                    <input type="range" name="price_min" id="price_min" min="0" max="5000" step="1"
                        value="<?= $selectedPriceMin ?>"
                        class="absolute pointer-events-none appearance-none z-20 h-2 w-full opacity-0 cursor-pointer"
                        oninput="updatePriceInputs()">
                    <input type="range" name="price_max" id="price_max" min="0" max="5000" step="1"
                        value="<?= $selectedPriceMax ?>"
                        class="absolute pointer-events-none appearance-none z-20 h-2 w-full opacity-0 cursor-pointer"
                        oninput="updatePriceInputs()">

                    <div class="relative z-10 h-2 w-full bg-gray-200 rounded-lg">
                        <div class="absolute z-10 top-0 bottom-0 bg-accent rounded-lg" id="slider-track"
                            style="left: 0%; right: 0%;"></div>
                        <div class="absolute z-20 w-4 h-4 bg-white border-2 border-accent rounded-full -mt-1 -ml-2 cursor-pointer pointer-events-auto shadow-md hover:scale-110 transition-transform"
                            id="thumb-min" onmousedown="startDrag(event, 'price', 'min')"></div>
                        <div class="absolute z-20 w-4 h-4 bg-white border-2 border-accent rounded-full -mt-1 -ml-2 cursor-pointer pointer-events-auto shadow-md hover:scale-110 transition-transform"
                            id="thumb-max" onmousedown="startDrag(event, 'price', 'max')"></div>
                    </div>
                </div>

                <div class="flex items-center justify-between gap-2 text-sm">
                    <div class="relative">
                        <span class="absolute left-2 top-1/2 -translate-y-1/2 text-gray-400">€</span>
                        <input type="number" id="input_price_min" value="<?= $selectedPriceMin ?>" min="0" max="5000"
                            class="w-24 pl-6 pr-2 py-1 border border-border-light rounded text-center focus:outline-none focus:border-accent"
                            onchange="updatePriceSlider(this.value, 'min')">
                    </div>
                    <span class="text-gray-400">-</span>
                    <div class="relative">
                        <span class="absolute left-2 top-1/2 -translate-y-1/2 text-gray-400">€</span>
                        <input type="number" id="input_price_max" value="<?= $selectedPriceMax ?>" min="0" max="5000"
                            class="w-24 pl-6 pr-2 py-1 border border-border-light rounded text-center focus:outline-none focus:border-accent"
                            onchange="updatePriceSlider(this.value, 'max')">
                    </div>
                </div>
                <?= view('partials/black_button', [
                    'tag' => 'button',
                    'type' => 'submit',
                    'label' => 'Appliquer',
                    'onclick' => "setActiveSection('price')",
                    'padding' => 'py-2',
                    'customClass' => 'mt-3 w-full text-[10px] uppercase font-bold shadow-none hover:shadow-md'
                ]) ?>
            </div>
        </div>

        <button type="button" onclick="window.location.href='<?= base_url('catalog') ?>'"
            class="w-full bg-gray-100 text-primary py-3 rounded text-xs font-bold uppercase tracking-widest hover:bg-border-light transition-colors">
            Réinitialiser
        </button>
    </aside>

    <!-- Product Listing Area -->
    <section>
        <!-- Search Bar -->
        <div class="mb-8">
            <div class="relative max-w-xl">
                <input type="text" name="search" value="<?= esc($searchTerm ?? '') ?>"
                    placeholder="Rechercher un modèle, une matière..."
                    class="w-full border border-border-light rounded-full pl-5 pr-12 py-3 focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-all shadow-sm">
                <button type="submit"
                    class="absolute right-4 top-1/2 -translate-y-1/2 text-muted hover:text-accent transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Results Top Header -->
        <div
            class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8 pb-4 border-b border-border-light">
            <div>
                <nav class="text-[11px] uppercase tracking-wider text-text-muted mb-2">
                    <a href="<?= base_url('/') ?>" class="hover:text-accent">Accueil</a> / <span
                        class="text-primary font-bold">Catalogue</span>
                </nav>
                <h1 class="font-serif text-3xl md:text-4xl text-primary font-bold">
                    Tous nos tapis
                    <span class="font-sans text-sm font-normal text-text-muted ml-2">
                        (<?= // Use pager to get total, or just count products if pager not available for total count easily. 
                            // CodeIgniter Pager doesn't easily give total result count unless we passed it.
                            // But we can just use count($products) for current page or nothing.
                            // I'll stick to what was likely intended or count of current page.
                            !empty($products) ? count($products) : 0
                            ?> modèles affichés)
                    </span>
                </h1>
            </div>
            <div class="flex items-center gap-3 text-sm">
                <span class="text-text-muted whitespace-nowrap">Trier par :</span>
                <select name="sort" onchange="this.form.submit()"
                    class="border border-border-light rounded px-3 py-2 bg-white focus:outline-none focus:border-accent cursor-pointer">
                    <option value="pertinence" <?= ($selectedSort ?? '') == 'pertinence' ? 'selected' : '' ?>>Pertinence
                    </option>
                    <option value="prix_asc" <?= ($selectedSort ?? '') == 'prix_asc' ? 'selected' : '' ?>>Prix croissant
                    </option>
                    <option value="prix_desc" <?= ($selectedSort ?? '') == 'prix_desc' ? 'selected' : '' ?>>Prix
                        décroissant</option>
                    <option value="nouveautes" <?= ($selectedSort ?? '') == 'nouveautes' ? 'selected' : '' ?>>Nouveautés
                    </option>
                </select>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-x-6 gap-y-10">

            <?php if (!empty($products) && count($products) > 0): ?>
                <?php foreach ($products as $product):
                    $id = $product->id ?? $product->id_product ?? null;
                    ?>

                    <?= view('partials/carpet_card', ['product' => $product]) ?>

                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-full py-20 text-center">
                    <p class="text-text-muted italic text-lg font-serif">Aucun tapis ne correspond à votre recherche
                        actuelle.</p>
                    <a href="<?= base_url('catalog') ?>"
                        class="inline-block mt-4 text-accent font-bold border-b border-accent pb-1">Voir tout le
                        catalogue</a>
                </div>
            <?php endif; ?>

        </div>

        <div class="p-6 border-t border-border flex justify-center bg-gray-50/30">
            <?= $pager->links('default', 'tailwind') ?>
        </div>

    </section>
</form>


<script>
    function toggleFilter(btn, sectionId) {
        const content = btn.nextElementSibling;
        const arrow = btn.querySelector('span:last-child');

        content.classList.toggle('hidden');

        if (content.classList.contains('hidden')) {
            arrow.style.transform = 'rotate(-90deg)';
        } else {
            arrow.style.transform = 'rotate(0deg)';
            setActiveSection(sectionId);
            // Initialize sliders if opening
            if (sectionId === 'price' && typeof updatePriceInputs === 'function') updatePriceInputs();
            if (sectionId === 'dimensions') {
                if (typeof updateWidthInputs === 'function') updateWidthInputs();
                if (typeof updateLengthInputs === 'function') updateLengthInputs();
            }
        }
    }

    function setActiveSection(id) {
        const input = document.getElementById('active_section_input');
        if (input) input.value = id;
    }

    /* --- Price Slider --- */
    const priceMinRange = document.getElementById('price_min');
    const priceMaxRange = document.getElementById('price_max');
    const priceMinInput = document.getElementById('input_price_min');
    const priceMaxInput = document.getElementById('input_price_max');
    const priceTrack = document.getElementById('slider-track');
    const priceThumbMin = document.getElementById('thumb-min');
    const priceThumbMax = document.getElementById('thumb-max');
    const priceMaxVal = 5000;

    function updatePriceInputs() {
        if (!priceMinRange) return;
        let min = parseInt(priceMinRange.value);
        let max = parseInt(priceMaxRange.value);

        if (min > max - 50) {
            const target = event ? event.target : null;
            if (target === priceMinRange) {
                priceMinRange.value = max - 50;
                min = max - 50;
            } else if (target === priceMaxRange) {
                priceMaxRange.value = min + 50;
                max = min + 50;
            }
        }

        const percentMin = (min / priceMaxVal) * 100;
        const percentMax = (max / priceMaxVal) * 100;

        if (priceTrack) {
            priceTrack.style.left = percentMin + "%";
            priceTrack.style.right = (100 - percentMax) + "%";
        }
        if (priceThumbMin) priceThumbMin.style.left = percentMin + "%";
        if (priceThumbMax) priceThumbMax.style.left = percentMax + "%";
        if (priceMinInput) priceMinInput.value = min;
        if (priceMaxInput) priceMaxInput.value = max;
    }

    function updatePriceSlider(val, type) {
        val = parseInt(val);
        if (val < 0) val = 0;
        if (val > priceMaxVal) val = priceMaxVal;

        if (type === 'min') {
            if (val > parseInt(priceMaxRange.value) - 50) val = parseInt(priceMaxRange.value) - 50;
            priceMinRange.value = val;
        } else {
            if (val < parseInt(priceMinRange.value) + 50) val = parseInt(priceMinRange.value) + 50;
            priceMaxRange.value = val;
        }
        updatePriceInputs();
    }

    /* --- Width Slider (Dimensions) --- */
    const widthMinRange = document.getElementById('width_min');
    const widthMaxRange = document.getElementById('width_max');
    const widthMinInput = document.getElementById('input_width_min');
    const widthMaxInput = document.getElementById('input_width_max');
    const widthTrack = document.getElementById('width-slider-track');
    const widthThumbMin = document.getElementById('width-thumb-min');
    const widthThumbMax = document.getElementById('width-thumb-max');

    const widthMaxLimit = widthMaxRange ? parseInt(widthMaxRange.getAttribute('max')) : 500;
    const widthMinLimit = widthMinRange ? parseInt(widthMinRange.getAttribute('min')) : 0;

    function updateWidthInputs() {
        if (!widthMinRange) return;
        let min = parseInt(widthMinRange.value);
        let max = parseInt(widthMaxRange.value);

        if (min > max - 10) {
            const target = event ? event.target : null;
            if (target === widthMinRange) {
                widthMinRange.value = max - 10;
                min = max - 10;
            } else if (target === widthMaxRange) {
                widthMaxRange.value = min + 10;
                max = min + 10;
            }
        }

        const range = widthMaxLimit - widthMinLimit;
        const percentMin = ((min - widthMinLimit) / range) * 100;
        const percentMax = ((max - widthMinLimit) / range) * 100;

        if (widthTrack) {
            widthTrack.style.left = percentMin + "%";
            widthTrack.style.right = (100 - percentMax) + "%";
        }
        if (widthThumbMin) widthThumbMin.style.left = percentMin + "%";
        if (widthThumbMax) widthThumbMax.style.left = percentMax + "%";
        if (widthMinInput) widthMinInput.value = min;
        if (widthMaxInput) widthMaxInput.value = max;
    }

    function updateWidthSlider(val, type) {
        val = parseInt(val);
        if (val < widthMinLimit) val = widthMinLimit;
        if (val > widthMaxLimit) val = widthMaxLimit;

        if (type === 'min') {
            if (val > parseInt(widthMaxRange.value) - 10) val = parseInt(widthMaxRange.value) - 10;
            widthMinRange.value = val;
        } else {
            if (val < parseInt(widthMinRange.value) + 10) val = parseInt(widthMinRange.value) + 10;
            widthMaxRange.value = val;
        }
        updateWidthInputs();
    }

    /* --- Length Slider (Dimensions) --- */
    const lengthMinRange = document.getElementById('length_min');
    const lengthMaxRange = document.getElementById('length_max');
    const lengthMinInput = document.getElementById('input_length_min');
    const lengthMaxInput = document.getElementById('input_length_max');
    const lengthTrack = document.getElementById('length-slider-track');
    const lengthThumbMin = document.getElementById('length-thumb-min');
    const lengthThumbMax = document.getElementById('length-thumb-max');

    const lengthMaxLimit = lengthMaxRange ? parseInt(lengthMaxRange.getAttribute('max')) : 500;
    const lengthMinLimit = lengthMinRange ? parseInt(lengthMinRange.getAttribute('min')) : 0;

    function updateLengthInputs() {
        if (!lengthMinRange) return;
        let min = parseInt(lengthMinRange.value);
        let max = parseInt(lengthMaxRange.value);

        if (min > max - 10) {
            const target = event ? event.target : null;
            if (target === lengthMinRange) {
                lengthMinRange.value = max - 10;
                min = max - 10;
            } else if (target === lengthMaxRange) {
                lengthMaxRange.value = min + 10;
                max = min + 10;
            }
        }

        const range = lengthMaxLimit - lengthMinLimit;
        const percentMin = ((min - lengthMinLimit) / range) * 100;
        const percentMax = ((max - lengthMinLimit) / range) * 100;

        if (lengthTrack) {
            lengthTrack.style.left = percentMin + "%";
            lengthTrack.style.right = (100 - percentMax) + "%";
        }
        if (lengthThumbMin) lengthThumbMin.style.left = percentMin + "%";
        if (lengthThumbMax) lengthThumbMax.style.left = percentMax + "%";
        if (lengthMinInput) lengthMinInput.value = min;
        if (lengthMaxInput) lengthMaxInput.value = max;
    }

    function updateLengthSlider(val, type) {
        val = parseInt(val);
        if (val < lengthMinLimit) val = lengthMinLimit;
        if (val > lengthMaxLimit) val = lengthMaxLimit;

        if (type === 'min') {
            if (val > parseInt(lengthMaxRange.value) - 10) val = parseInt(lengthMaxRange.value) - 10;
            lengthMinRange.value = val;
        } else {
            if (val < parseInt(lengthMinRange.value) + 10) val = parseInt(lengthMinRange.value) + 10;
            lengthMaxRange.value = val;
        }
        updateLengthInputs();
    }

    /* --- Generic Drag --- */
    let activeType = null;
    let activeLimitType = null;

    function startDrag(e, type, limitType) {
        e.preventDefault();
        activeType = type;
        activeLimitType = limitType;
        document.addEventListener('mousemove', onDrag);
        document.addEventListener('mouseup', stopDrag);
    }

    function onDrag(e) {
        if (!activeType) return;

        let track, maxVal, minVal, updateFunc, step;

        if (activeType === 'price') {
            track = priceTrack;
            maxVal = priceMaxVal;
            minVal = 0;
            step = 50;
            updateFunc = updatePriceSlider;
        } else if (activeType === 'width') {
            track = widthTrack;
            maxVal = widthMaxLimit;
            minVal = widthMinLimit;
            step = 10;
            updateFunc = updateWidthSlider;
        } else if (activeType === 'length') {
            track = lengthTrack;
            maxVal = lengthMaxLimit;
            minVal = lengthMinLimit;
            step = 10;
            updateFunc = updateLengthSlider;
        }

        const sliderRect = track.parentElement.getBoundingClientRect();
        let percent = (e.clientX - sliderRect.left) / sliderRect.width * 100;

        if (percent < 0) percent = 0;
        if (percent > 100) percent = 100;

        const range = maxVal - minVal;
        const rawVal = minVal + (percent * range / 100);

        const val = Math.round(rawVal / step) * step;

        updateFunc(val, activeLimitType);
    }

    function stopDrag() {
        activeType = null;
        document.removeEventListener('mousemove', onDrag);
        document.removeEventListener('mouseup', stopDrag);
    }

    document.addEventListener('DOMContentLoaded', () => {
        if (typeof updatePriceInputs === 'function') updatePriceInputs();
        if (typeof updateWidthInputs === 'function') updateWidthInputs();
        if (typeof updateLengthInputs === 'function') updateLengthInputs();
    });
</script>
<?= $this->endSection() ?>

