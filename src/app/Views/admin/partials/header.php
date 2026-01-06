<header class="h-24 px-8 flex justify-between items-center bg-white/80 backdrop-blur-md border-b border-border sticky top-0 z-20">
    <div>
        <h1 class="font-serif text-3xl font-bold text-primary"><?= esc($title) ?></h1>
        <p class="text-muted text-sm font-medium"><?= esc($subtitle) ?></p>
    </div>
    <div class="flex items-center gap-4">
        <?= $action ?? '' ?>
        <div class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center font-serif font-bold text-lg shadow-lg shadow-primary/20">
            A
        </div>
    </div>
</header>