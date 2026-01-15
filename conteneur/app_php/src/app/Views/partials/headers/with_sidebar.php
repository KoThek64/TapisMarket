<header
    class="h-24 px-8 flex justify-between items-center bg-white/80 backdrop-blur-md border-b border-border sticky top-0 z-20">

    <div class="flex items-center gap-4">
        <div class="lg:hidden">
            <button onclick="toggleSidebar()"
                class="p-2 bg-white border border-border rounded-lg shadow-sm text-primary hover:bg-gray-50">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16">
                    </path>
                </svg>
            </button>
        </div>

        <div>
            <h1 class="font-serif text-2xl md:text-3xl font-bold text-primary"><?= esc($title) ?></h1>
            <p class="text-muted text-sm font-medium hidden md:block"><?= esc($subtitle) ?></p>
        </div>
    </div>

    <div class="flex items-center gap-4">
        <?= $this->renderSection('header_content') ?>
        <div
            class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center font-serif font-bold text-lg shadow-lg shadow-primary/20">
            A
        </div>
    </div>
</header>
