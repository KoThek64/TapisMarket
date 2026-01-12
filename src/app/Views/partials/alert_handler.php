<div class="">
    <?php
    $alerts = [
        'error' => ['bg' => 'bg-red-50', 'text' => 'text-red-800', 'border' => 'border-red-100', 'icon' => 'error'],
        'success' => ['bg' => 'bg-green-50', 'text' => 'text-green-800', 'border' => 'border-green-100', 'icon' => 'check_circle'],
        'info' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-800', 'border' => 'border-blue-100', 'icon' => 'info'],
    ];

    foreach ($alerts as $type => $style):
        $disable_var_name = "custom_{$type}_alert";
        $is_disabled = $this->data[$disable_var_name] ?? $disable_alerts ?? false;
        if (!$is_disabled && session()->getFlashdata($type)): ?>
            <div
                class="<?= $style['bg'] . ' ' . $style['text'] . ' ' . $style['border'] ?> border p-4 rounded-xl flex items-center gap-3">

                <p class="flex-1 font-sans text-sm font-medium">
                    <?= session()->getFlashdata($type) ?>
                </p>

                <button onclick="this.parentElement.remove()" class="ml-4 opacity-50 hover:opacity-100 transition-opacity">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
        <?php endif;
    endforeach;
    ?>
</div>