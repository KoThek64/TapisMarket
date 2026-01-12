<?php
$_uri = service('uri');
$_segment = $_uri->getTotalSegments() >= 2 ? $_uri->getSegment(2) : '';
$_subSegment = $_uri->getTotalSegments() >= 3 ? $_uri->getSegment(3) : '';

if (!function_exists('getMenuClass')) {
    function getMenuClass($isActive)
    {
        if ($isActive) {
            return 'bg-primary text-white font-medium shadow-lg shadow-primary/20 transform scale-[1.02]';
        }
        return 'text-muted hover:bg-cream hover:text-accent font-medium';
    }
}

?>

<a href="<?= site_url($item['url']) ?>"
    class="flex items-center gap-3 px-4 py-3.5 rounded-xl transition-all <?= getMenuClass($item['segment'] === $_segment) ?>">
    <?= icon($item['icon_name']) ?>
    <?= $item['name'] ?>
    <?php if (!empty($item['number'])): ?>
        <span class="ml-auto bg-orange-100 text-orange-700 py-0.5 px-2 rounded-full text-xs font-bold"><?= $item['number'] ?></span>
    <?php endif; ?>
</a>