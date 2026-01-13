<?php
// Initialisation des valeurs par défaut pour éviter les erreurs
$actionUrl = $actionUrl ?? current_url();
$searchName = $searchName ?? 'search';
$searchValue = $searchValue ?? ($search ?? '');
$searchPlaceholder = $searchPlaceholder ?? 'Rechercher...';
$filters = $filters ?? []; 


$hasActiveFilter = !empty($searchValue);
if (!$hasActiveFilter) {
    foreach ($filters as $filter) {
        if (!empty($filter['selected'])) {
            $hasActiveFilter = true;
            break;
        }
    }
}
?>

<form action="<?= $actionUrl ?>" method="get" class="mb-8">
    <div class="flex flex-col md:flex-row gap-4 items-center">
        <!-- Barre de recherche -->
        <div class="flex-grow w-full md:w-auto relative">
            <input type="text"
                   name="<?= esc($searchName) ?>"
                   value="<?= esc($searchValue) ?>"
                   placeholder="<?= esc($searchPlaceholder) ?>"
                   class="w-full pl-10 pr-4 py-2.5 bg-white border border-border rounded-xl focus:ring-2 focus:ring-accent/50 focus:border-accent transition-all shadow-sm"
            >
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </div>

        <!-- Boucle sur les filtres dynamiques -->
        <?php foreach ($filters as $filter): ?>
            <div class="w-full md:w-56 relative">
                <select name="<?= esc($filter['name']) ?>"
                        class="w-full pl-4 pr-10 py-2.5 bg-white border border-border rounded-xl focus:ring-2 focus:ring-accent/50 focus:border-accent appearance-none cursor-pointer shadow-sm"
                        onchange="this.form.submit()"
                >
                    <?php if (!empty($filter['placeholder'])): ?>
                        <option value=""><?= esc($filter['placeholder']) ?></option>
                    <?php endif; ?>
                    
                    <?php foreach ($filter['options'] as $value => $label): ?>
                        <option value="<?= esc($value) ?>" <?= (string)($filter['selected'] ?? '') === (string)$value ? 'selected' : '' ?>>
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
        <?php endforeach; ?>
        
        <!-- Bouton Réinitialiser -->
        <?php if ($hasActiveFilter): ?>
            <a href="<?= $actionUrl ?>" class="w-full md:w-auto text-center px-4 py-2.5 border border-border text-muted font-bold rounded-xl hover:bg-gray-50 transition-colors">
                Réinitialiser
            </a>
        <?php endif; ?>
    </div>
</form>