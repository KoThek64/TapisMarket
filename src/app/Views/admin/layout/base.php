<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= esc($title ?? 'Administration') ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Onest:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { cream: '#fdfcf8', accent: '#b4690e', 'accent-light': '#d98d2e', primary: '#111827', muted: '#64748b', border: '#e2e8f0' },
                    fontFamily: { sans: ['Onest', 'sans-serif'], serif: ['Playfair Display', 'serif'] },
                    borderRadius: { 'xl': '16px', '2xl': '24px' }
                }
            }
        }
    </script>
</head>
<body class="bg-cream text-primary font-sans antialiased h-screen flex overflow-hidden">

    <?= view('admin/partials/sidebar') ?>

    <main class="flex-1 flex flex-col h-screen overflow-hidden bg-cream relative">
        
        <?= $this->renderSection('header') ?>

        <div class="flex-1 overflow-y-auto p-8 lg:p-12 space-y-8">
            
            <?= view('admin/partials/alerts') ?>
            
            <?= $this->renderSection('content') ?>

        </div>
    </main>

    <?= $this->renderSection('modals') ?>

    <?= $this->renderSection('scripts') ?>

</body>
</html>