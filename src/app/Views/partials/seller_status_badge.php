<?php if ($status === 'VALIDATED'): ?>
    <div class="relative group cursor-default">
        <span class="h-3 w-3 block rounded-full bg-green-500 shadow-sm border border-green-200"></span>
        <span
            class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover:block px-2 py-1 bg-gray-800 text-white text-xs rounded whitespace-nowrap">Compte
            validé</span>
    </div>

<?php elseif ($status === 'PENDING_VALIDATION'): ?>
    <div class="relative group cursor-default">
        <span class="relative flex h-3 w-3">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-3 w-3 bg-orange-500 border border-orange-200"></span>
        </span>
        <span
            class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover:block px-2 py-1 bg-gray-800 text-white text-xs rounded whitespace-nowrap">En
            attente</span>
    </div>

<?php elseif ($status === 'REFUSED'): ?>
    <div class="relative group cursor-default">
        <span class="h-3 w-3 block rounded-full bg-red-500 shadow-sm border border-red-200"></span>
        <span
            class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover:block px-2 py-1 bg-gray-800 text-white text-xs rounded whitespace-nowrap">Candidature
            refusée</span>
    </div>
<?php endif; ?>

