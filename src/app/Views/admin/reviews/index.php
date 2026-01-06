<?= $this->extend('admin/layout/base') ?>

<?= $this->section('header') ?>
    <?= view('admin/partials/header', [
        'title'    => 'Avis Clients',
        'subtitle' => 'Modération des commentaires publiés',
        'action'   => '<a href="' . site_url('admin') . '" class="text-xs font-bold text-muted hover:text-primary transition uppercase border border-border px-4 py-2 rounded-full bg-white shadow-sm flex items-center gap-2"><span>← Retour</span></a>'
    ]) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

    <div class="bg-white rounded-2xl shadow-sm border border-border overflow-hidden flex flex-col">
        
        <div class="px-8 py-6 border-b border-border bg-gray-50/30 flex justify-between items-center">
            <div class="flex items-center gap-4">
                <h2 class="font-serif font-bold text-xl text-primary">Liste des avis</h2>
                
                <span class="bg-gray-100 text-muted text-[10px] px-3 py-1 rounded-full uppercase font-bold tracking-tighter border border-border">
                    Total : <?= $pager->getTotal() ?>
                </span>

                <?php if($criticalCount > 0): ?>
                    <a href="<?= site_url('admin/reviews?filter=critical') ?>" class="bg-red-100 text-red-600 text-[10px] px-3 py-1 rounded-full uppercase font-bold tracking-tighter border border-red-200 hover:bg-red-200 transition">
                        <?= $criticalCount ?> critiques
                    </a>
                <?php endif; ?>
            </div>
            
            <form method="get" action="<?= site_url('admin/reviews') ?>">
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-muted group-hover:text-accent transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    </div>
                    
                    <select name="filter" onchange="this.form.submit()" 
                            class="appearance-none pl-10 pr-10 py-2.5 bg-white border border-border text-primary text-sm font-bold rounded-xl shadow-sm hover:border-accent/50 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-accent/20 focus:border-accent transition-all cursor-pointer w-56">
                        <option value="">Tous les avis</option>
                        <option value="published" <?= ($currentFilter === 'published') ? 'selected' : '' ?>> Publiés</option>
                        <option value="rejected" <?= ($currentFilter === 'rejected') ? 'selected' : '' ?>> Rejetés</option>
                        <option value="critical" <?= ($currentFilter === 'critical') ? 'selected' : '' ?>> Note basse (≤ 2★)</option>
                    </select>

                    <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-muted group-hover:text-primary transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-white text-[10px] uppercase text-muted font-bold border-b border-border tracking-widest">
                    <tr>
                        <th class="px-8 py-5">Produit</th>
                        <th class="px-8 py-5">Note</th>
                        <th class="px-8 py-5 w-1/3">Commentaire</th>
                        <th class="px-8 py-5">Client</th>
                        <th class="px-8 py-5 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border text-sm">
                    <?php foreach ($reviews as $a): ?>
                    <tr class="hover:bg-cream transition-colors group">
                        <td class="px-8 py-5">
                            <div class="font-bold text-primary"><?= esc($a->product_name) ?></div>
                            <div class="text-[10px] text-muted mt-1"><?= date('d/m/Y', strtotime($a->published_at)) ?></div>
                        </td>
                        
                        <td class="px-8 py-5">
                            <?= $a->getStars() ?> 
                            <div class="text-[10px] font-bold text-muted mt-1">Note : <?= $a->rating ?>/5</div>
                        </td>

                        <td class="px-8 py-5">
                            <div class="relative group w-fit max-w-full cursor-help">
                                <p class="text-muted italic text-xs leading-relaxed line-clamp-3">"<?= esc($a->comment) ?>"</p>
                                <div class="absolute top-full left-0 mt-2 w-80 bg-gray-900 text-white text-xs p-4 rounded-xl shadow-2xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50 pointer-events-none border border-gray-700">
                                    <div class="absolute bottom-full left-4 border-8 border-transparent border-b-gray-900"></div>
                                    <p class="font-normal not-italic leading-relaxed relative z-10"><?= esc($a->comment) ?></p>
                                </div>
                            </div>
                            <?php if($a->moderation_status === 'REFUSED'): ?>
                                <span class="inline-block mt-2 text-[10px] font-bold bg-red-100 text-red-700 px-2 py-0.5 rounded border border-red-200">Masqué</span>
                            <?php endif; ?>
                        </td>

                        <td class="px-8 py-5">
                            <div class="text-sm font-medium text-primary"><?= esc($a->firstname) ?> <?= esc($a->lastname) ?></div>
                            <div class="text-xs text-muted"><?= esc($a->email) ?></div>
                        </td>

                        <td class="px-8 py-5 text-right">
                            <div class="flex justify-end gap-2">
                                <?php if($a->moderation_status === 'PUBLISHED'): ?>
                                    <a href="<?= site_url('admin/reviews/status/' . $a->id . '/REFUSED') ?>" 
                                       class="text-[10px] font-bold uppercase px-3 py-1.5 bg-white border border-border text-red-600 rounded hover:bg-red-50 hover:border-red-200 transition">
                                       Rejeter
                                    </a>
                                <?php else: ?>
                                    <a href="<?= site_url('admin/reviews/status/' . $a->id . '/PUBLISHED') ?>" 
                                       class="text-[10px] font-bold uppercase px-3 py-1.5 bg-white border border-border text-green-600 rounded hover:bg-green-50 hover:border-green-200 transition">
                                       Rétablir
                                    </a>
                                <?php endif; ?>
                                
                                <a href="javascript:void(0)" 
                                   onclick="openDeleteModal('<?= site_url('admin/reviews/delete/' . $a->id) ?>')"
                                   class="text-red-400 hover:text-red-600 font-bold transition p-2 hover:bg-red-50 rounded-lg inline-block" 
                                   title="Delete">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <div class="p-6 border-t border-border flex justify-center bg-gray-50/30">
            <?= $pager->links('reviews', 'tailwind') ?>
        </div>
    </div>

<?= $this->endSection() ?>

<?= $this->section('modals') ?>
    <?= view('admin/partials/delete_modal') ?>
<?= $this->endSection() ?>