<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<div class="min-h-screen bg-cream font-sans flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-custom border border-border-custom shadow-md-custom p-8 text-center">
        
        <div class="mb-6 inline-flex items-center justify-center w-16 h-16 bg-cream rounded-full border border-border-light">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>

        <h1 class="font-serif text-3xl text-primary mb-4 leading-tight">
            Vous êtes en attente d'acceptation
        </h1>

        <p class="text-muted leading-relaxed mb-8">
            Bienvenue parmi nous ! Votre compte vendeur est actuellement en cours de révision par notre équipe. 
            Vous recevrez un e-mail dès que votre boutique sera activée.
        </p>

        <div class="h-px bg-border-custom w-full mb-8"></div>

        <div class="space-y-3">
            <?= view('partials/black_button', [ 
                'url' => '/', 
                'label' => 'Retours au site',
                'customClass' => 'w-full rounded-xl'
            ]) ?>
        </div>

        <p class="mt-6 text-sm text-muted">
            Délai moyen de réponse : <span class="text-accent-light font-medium">24h à 48h</span>
        </p>
    </div>
</div>

<?= $this->endSection() ?>