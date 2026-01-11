<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>TapisMarket - <?= esc($title ?? "Espace Client") ?></title>

    <link
        href="https://fonts.googleapis.com/css2?family=Onest:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap"
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
</head>

<body class="bg-cream text-primary font-sans antialiased h-screen flex overflow-hidden">

    <?= view('partials/client/sidebar') ?>

    <main class="flex-1 flex flex-col h-screen overflow-hidden bg-cream relative">
        
        <?= $this->include("headers/" . ($header ?? "client")) ?>

        <div class="flex-1 overflow-y-auto p-8 lg:p-12 space-y-8">
            
            <?= $this->include("partials/alert_handler") ?>
            <?= $this->renderSection('content') ?>
            <?= $this->include("footers/" . ($footer ?? "client")) ?>

        </div>
    </main>
</body>

</html>