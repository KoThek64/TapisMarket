<?= $this->extend('layouts/admin_section') ?>

<?= $this->section('header_content') ?>
<a href="<?= site_url('/') ?>"
    class="text-xs font-bold text-muted hover:text-primary transition uppercase tracking-wide mr-4">Retour au site</a>
<a href="<?= site_url('admin/categories/new') ?>"
    class="group flex items-center gap-2 px-5 py-2.5 bg-primary text-white rounded-full hover:bg-accent transition-all text-sm font-bold shadow-lg shadow-primary/30 hover:shadow-accent/30">
    <span>+ Nouvelle Catégorie</span>
</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="bg-white rounded-2xl shadow-sm border border-border overflow-hidden flex flex-col">

    <div class="px-8 py-6 border-b border-border bg-gray-50/30 flex justify-between items-center">
        <h2 class="font-serif font-bold text-xl text-primary">Liste des catégories</h2>

        <span
            class="bg-gray-100 text-muted text-[10px] px-3 py-1 rounded-full uppercase font-bold tracking-tighter border border-border">
            Total : <?= $pager->getTotal() ?>
        </span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-white text-[10px] uppercase text-muted font-bold border-b border-border tracking-widest">
                <tr>
                    <th class="px-8 py-5">Nom de la catégorie</th>
                    <th class="px-8 py-5">Slug</th>
                    <th class="px-8 py-5 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border">
                <?php foreach ($categories as $cat): ?>
                    <tr class="hover:bg-cream transition-colors group">
                        <td class="px-8 py-5">
                            <span
                                class="font-serif text-lg font-bold text-primary group-hover:text-accent transition-colors">
                                <?= esc($cat->name) ?>
                            </span>
                        </td>

                        <td class="px-8 py-5">
                            <span
                                class="px-3 py-1.5 bg-gray-50 border border-border rounded-lg text-xs text-muted font-mono">
                                /<?= esc($cat->alias) ?>
                            </span>
                        </td>

                        <td class="px-8 py-5 text-right">
                            <div class="flex items-center justify-end gap-3">
                                <a href="<?= site_url('admin/categories/' . $cat->id . '/edit') ?>"
                                    class="text-xs font-bold uppercase tracking-wide text-muted hover:text-accent transition border border-transparent hover:border-border px-3 py-1.5 rounded">
                                    Éditer
                                </a>

                                <a href="javascript:void(0)"
                                    onclick="openDeleteModal('<?= site_url('admin/categories/' . $cat->id) ?>')"
                                    class="text-xs font-bold uppercase tracking-wide text-red-400 hover:text-red-600 transition border border-transparent hover:border-red-100 hover:bg-red-50 px-3 py-1.5 rounded">
                                    Supprimer
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="p-6 border-t border-border flex justify-center bg-gray-50/30">
        <?= $pager->links('default', 'tailwind') ?>
    </div>

</div>

<?= $this->endSection() ?>

<?= $this->section('modals') ?>
<?= view('partials/delete_modal') ?>
<?= $this->endSection() ?>