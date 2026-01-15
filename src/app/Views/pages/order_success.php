<?= $this->extend('layouts/default') ?>

<?= $this->section('head') ?>
<meta http-equiv="refresh" content="5;url=<?= base_url('/') ?>" />
<?= $this->endSection() ?>

<?= $this->section('content') ?>


<div class="flex flex-col items-center justify-center text-center px-4 min-h-[60vh] w-full max-w-3xl mx-auto">

    <div class="mb-8 animate-bounce">
        <div class="rounded-full bg-green-50 p-6 inline-flex shadow-sm">
            <svg class="w-16 h-16 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
    </div>

    <h1 class="font-serif text-4xl md:text-5xl text-gray-900 mb-6">
        Merci pour votre commande !
    </h1>

    <p class="text-gray-500 text-lg md:text-xl leading-relaxed mb-10 max-w-xl mx-auto">
        Votre commande a été enregistrée avec succès. Vous recevrez bientôt un email de confirmation.
    </p>

    <div class="flex flex-col items-center gap-6">
        <p
            class="text-sm text-gray-400 font-medium flex items-center justify-center gap-2 bg-gray-50 px-4 py-2 rounded-full">
            <svg class="animate-spin h-4 w-4 text-accent" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
            </svg>
            Redirection vers l'accueil dans quelques instants...
        </p>

        <a href="<?= base_url('/') ?>"
            class="group bg-primary text-white px-8 py-3.5 rounded-xl font-bold text-sm hover:bg-accent transition-all duration-300 shadow-lg hover:shadow-accent/20 flex items-center gap-2">
            <span>Retour à l'accueil maintenant</span>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor" class="w-4 h-4 transition-transform group-hover:translate-x-1">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
            </svg>
        </a>
    </div>

</div>


<?= $this->endSection() ?>

