<div class="flex-grow w-full md:w-auto relative group">
    <input type="text"
           name="<?= esc($name ?? 'search') ?>"
           value="<?= esc($value ?? '') ?>"
           placeholder="<?= esc($placeholder ?? 'Rechercher...') ?>"
           class="w-full pl-10 pr-12 py-2.5 bg-white border border-border rounded-xl focus:ring-2 focus:ring-accent/50 focus:border-accent transition-all shadow-sm"
    >
    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
        <svg class="h-5 w-5 text-muted group-focus-within:text-accent transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
    </div>
    <button type="submit" class="absolute inset-y-0 right-0 pr-3 flex items-center text-muted hover:text-accent cursor-pointer transition-colors" title="Rechercher">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
        </svg>
    </button>
</div>