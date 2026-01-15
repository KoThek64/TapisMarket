<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?= esc($title) ?></title>
    <link
        href="https://fonts.googleapis.com/css2?family=Onest:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap"
        rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { cream: '#fdfcf8', accent: '#b4690e', primary: '#111827', muted: '#64748b', border: '#eef2f6' },
                    fontFamily: { sans: ['Onest', 'sans-serif'], serif: ['Playfair Display', 'serif'] },
                    borderRadius: { 'xl': '16px' }
                }
            }
        }
    </script>
</head>

<body class="bg-cream text-primary font-sans antialiased h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-2xl bg-white rounded-2xl shadow-xl border border-border overflow-hidden">
        <div class="bg-primary px-8 py-6 flex justify-between items-center">
            <h2 class="font-serif text-2xl font-bold text-white"><?= esc($title) ?></h2>
            <a href="<?= site_url('admin/categories') ?>" class="text-gray-300 hover:text-white transition">✕ Fermer</a>
        </div>

        <div class="p-8">
            <?php if (session()->has('errors')): ?>
                <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-6 text-sm border border-red-100">
                    <ul class="list-disc pl-5 space-y-1 font-medium">
                        <?php foreach (session('errors') as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php
            $formUrl = ($action === 'create') ? 'admin/categories' : 'admin/categories/' . $category->id;
            ?>
            <form action="<?= site_url($formUrl) ?>" method="post" class="space-y-6">
                <?= csrf_field() ?>

                <?php if ($action === 'edit'): ?>
                    <input type="hidden" name="_method" value="PUT">
                <?php endif; ?>

                <div>
                    <label class="block text-sm font-bold text-primary mb-2 tracking-tight">Nom de la catégorie</label>
                    <input type="text" name="name"
                        class="w-full px-5 py-3 rounded-xl border border-border bg-cream focus:bg-white focus:outline-none focus:ring-2 focus:ring-accent/20 focus:border-accent transition-all font-medium"
                        placeholder="Ex: Tapis Berbères" value="<?= old('name', $category->name) ?>" required>
                </div>

                <div>
                    <label class="block text-sm font-bold text-primary mb-2 tracking-tight">Description
                        (Optionnel)</label>
                    <textarea name="description" rows="4"
                        class="w-full px-5 py-3 rounded-xl border border-border bg-cream focus:bg-white focus:outline-none focus:ring-2 focus:ring-accent/20 focus:border-accent transition-all font-medium"
                        placeholder="Courte description pour le SEO..."><?= old('description', $category->description) ?></textarea>
                </div>

                <div class="pt-4 flex items-center justify-end gap-4 border-t border-border mt-8">
                    <a href="<?= site_url('admin/categories') ?>"
                        class="px-6 py-3 text-muted hover:text-primary font-bold transition text-sm">Annuler</a>
                    <button type="submit"
                        class="bg-accent hover:bg-yellow-700 text-white px-8 py-3 rounded-full font-bold shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5 text-sm uppercase tracking-widest">
                        Sauvegarder
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>

</html>
