<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>TapisMarket - <?= esc($title ?? "Bienvenu") ?></title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        cream: '#fdfcf8',
                        body: '#fdfcf8', // Alias for backward compatibility if needed
                        accent: '#b4690e',
                        'accent-light': '#d98d2e',
                        primary: '#111827',
                        muted: '#64748b',
                        border: '#e2e8f0',
                        'border-light': '#e5e7eb', // Added to be safe
                        'border-custom': '#eef2f6', // Keep existing
                    },
                    fontFamily: {
                        sans: ['Onest', 'sans-serif'],
                        serif: ['Playfair Display', 'serif']
                    },
                    borderRadius: {
                        'xl': '16px',
                        '2xl': '24px',
                        'custom': '16px'
                    },
                    boxShadow: {
                        'sm-custom': '0 2px 8px rgba(0,0,0,0.04)',
                        'md-custom': '0 8px 24px rgba(0,0,0,0.06)',
                    }
                }
            }
        }
    </script>
    <?= $this->renderSection('head') ?>
</head>

<body class="flex flex-col min-h-screen bg-cream text-primary font-sans antialiased">

    <?= $this->include("headers/" . ($header ?? "default")) ?>

    <main class="flex-grow w-full">
        <?= $this->include("partials/alert_handler") ?>
        <?= $this->renderSection('content') ?>
    </main>

    <?= $this->include("footers/" . ($footer ?? "default")) ?>

</body>

</html>
