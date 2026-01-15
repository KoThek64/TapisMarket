<?php
// Default values using the global $user object available in client views
$firstname = $user->firstname ?? 'Client';
$initials = substr($firstname, 0, 1);
?>
<header
    class="h-24 px-8 flex justify-between items-center bg-white/80 backdrop-blur-md border-b border-border sticky top-0 z-20">
    <div>
        <h1 class="font-serif text-3xl font-bold text-primary"><?= esc($title) ?></h1>
        <p class="text-muted text-sm font-medium"><?= esc($subtitle ?? '') ?></p>
    </div>
    <div class="flex items-center gap-4">
        <!-- Always available 'Return to Shop' button -->
        <a href="<?= site_url('/') ?>"
            class="flex items-center gap-2 px-4 py-2 border border-border rounded-xl hover:bg-white hover:shadow-sm transition text-sm font-bold text-gray-700 bg-gray-50/50">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                </path>
            </svg>
            Retour à la boutique
        </a>

        <?= $action ?? '' ?>

        <div class="w-10 h-10 rounded-full bg-accent text-white flex items-center justify-center font-serif font-bold text-lg shadow-lg shadow-accent/20 cursor-default"
            title="<?= esc($firstname) ?>">
            <?= esc($initials) ?>
        </div>
    </div>
</header>
