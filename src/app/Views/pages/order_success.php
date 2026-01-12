<?= $this->extend('layouts/simple') ?>

<?= $this->section('head') ?>
    <meta http-equiv="refresh" content="5;url=<?= base_url('/') ?>" />
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<main class="flex-grow flex flex-col items-center justify-center text-center px-4 mt-10">

    <div class="mb-8">
        <img src="https://cdn-icons-png.flaticon.com/512/148/148767.png"
            alt="Succès"
            class="w-20 h-20"
            style="filter: invert(36%) sepia(93%) saturate(1376%) hue-rotate(88deg) brightness(103%) contrast(106%);">
    </div>

    <h1 class="font-serif text-4xl md:text-5xl text-gray-900 mb-6">
        Merci pour votre commande !
    </h1>

    <p class="text-gray-500 text-lg md:text-xl max-w-lg leading-relaxed mb-8">
        Votre commande a été enregistrée avec succès. Vous recevrez bientôt un email de confirmation.
    </p>

    <p class="text-sm text-gray-400 font-medium mb-8 flex items-center justify-center gap-2">
        <svg class="animate-spin h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Redirection vers l'accueil dans quelques instants...
    </p>

    <a href="<?= base_url('/') ?>" class="bg-primary text-white px-8 py-3.5 rounded-full font-bold text-sm hover:bg-gray-800 transition shadow-lg transform active:scale-[0.99]">
        Retour à l'accueil maintenant
    </a>

</main>

<?= $this->endSection() ?>