<?php $filters = $filters ?? []; ?>
<?php foreach ($filters as $filter): ?>
    <div class="w-full md:w-56 relative border-l md:border-l-2 border-transparent md:border-gray-100 md:pl-4">
        <select name="<?= esc($filter['name']) ?>"
                class="w-full h-full pl-4 pr-10 py-2.5 bg-white border border-border rounded-xl focus:ring-2 focus:ring-accent/50 focus:border-accent appearance-none cursor-pointer shadow-sm text-sm font-medium text-gray-700"
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