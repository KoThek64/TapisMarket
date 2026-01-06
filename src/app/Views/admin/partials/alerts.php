<div class="space-y-4 mb-8">
    <?php if (session()->getFlashdata('success')): ?>
        <div class="bg-green-50 border border-green-200 text-green-800 px-6 py-4 rounded-xl flex items-center gap-3 shadow-sm">
            <span class="font-bold text-xl">✅</span> 
            <span class="font-medium"><?= session()->getFlashdata('success') ?></span>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('warning')): ?>
        <div class="bg-orange-50 border border-orange-200 text-orange-800 px-6 py-4 rounded-xl flex items-center gap-3 shadow-sm">
            <span class="font-bold text-xl">❌</span> 
            <span class="font-medium"><?= session()->getFlashdata('warning') ?></span>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="bg-red-50 border border-red-200 text-red-800 px-6 py-4 rounded-xl flex items-center gap-3 shadow-sm">
            <span class="font-bold text-xl">⚠️</span> 
            <span class="font-medium"><?= session()->getFlashdata('error') ?></span>
        </div>
    <?php endif; ?>
</div>