<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>TapisMarket - <?= esc($title ?? "Bienvenu") ?></title>
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <link rel="stylesheet" href="<?= base_url('Styles/style.css') ?>">
    <link
        href="https://fonts.googleapis.com/css2?family=Onest:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap"
        rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'body': '#fdfcf8',
                        'contrast': '#f4f4f5',
                        'primary': {
                            DEFAULT: '#111827',
                            hover: '#374151',
                        },
                        'accent': {
                            DEFAULT: '#b4690e',
                            light: '#d98d2e',
                            fade: 'rgba(180, 105, 14, 0.1)',
                        },
                        'main': '#1a1a1a',
                        'muted': '#64748b',
                        'border-custom': '#eef2f6',
                    },
                    fontFamily: {
                        sans: ['Onest', 'sans-serif'],
                        serif: ['Playfair Display', 'serif'],
                    },
                    borderRadius: {
                        'custom': '16px',
                    },
                    boxShadow: {
                        'sm-custom': '0 2px 8px rgba(0,0,0,0.04)',
                        'md-custom': '0 8px 24px rgba(0,0,0,0.06)',
                    }
                },
            },
        }
    </script>
    <?= $this->renderSection('head') ?>
</head>

<body>

    <?= $this->include("headers/" . ($header ?? "default")) ?>

    <div class="absolute top-[100px] right-6 z-[100] flex flex-col gap-3 w-full max-w-[350px]">
        <?php
        $alerts = [
            'error' => ['bg' => 'bg-red-50', 'text' => 'text-red-800', 'border' => 'border-red-200', 'icon' => 'error'],
            'success' => ['bg' => 'bg-green-50', 'text' => 'text-green-800', 'border' => 'border-green-200', 'icon' => 'check_circle'],
            'info' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-800', 'border' => 'border-blue-200', 'icon' => 'info'],
        ];

        foreach ($alerts as $type => $style):
            $disable_var_name = "disable_{$type}_alert";
            $is_disabled = $this->data[$disable_var_name] ?? $disable_alerts ?? false;
            if (!$is_disabled && session()->getFlashdata($type)): ?>
                <div
                    class="flex items-center p-4 rounded-custom border <?= $style['bg'] ?> <?= $style['text'] ?> <?= $style['border'] ?> shadow-lg-custom">
                    <div class="flex-1 font-sans text-sm font-medium">
                        <?= session()->getFlashdata($type) ?>
                    </div>
                    <button onclick="this.parentElement.remove()" class="ml-4 opacity-50 hover:opacity-100 transition-opacity">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            <?php endif;
        endforeach; 
        ?>
    </div>

    <main>
        <?= $this->renderSection('content') ?>
    </main>

    <?= $this->include("footers/" . ($footer ?? "default")) ?>

</body>

</html>