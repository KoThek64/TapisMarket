<?php $pager->setSurroundCount(2) ?>
<nav aria-label="Pagination" class="flex items-center gap-1">
    <?php if ($pager->hasPrevious()): ?>
        <a href="<?= $pager->getFirst() ?>"
            class="px-3 py-2 rounded-lg border border-border hover:bg-white transition text-xs font-bold text-muted">«</a>
    <?php endif ?>

    <?php foreach ($pager->links() as $link): ?>
        <a href="<?= $link['uri'] ?>"
            class="px-4 py-2 rounded-lg border transition text-xs font-bold <?= $link['active'] ? 'bg-primary text-white border-primary shadow-lg shadow-primary/20' : 'border-border text-muted hover:bg-white' ?>">
            <?= $link['title'] ?>
        </a>
    <?php endforeach ?>

    <?php if ($pager->hasNext()): ?>
        <a href="<?= $pager->getLast() ?>"
            class="px-3 py-2 rounded-lg border border-border hover:bg-white transition text-xs font-bold text-muted">»</a>
    <?php endif ?>
</nav>
