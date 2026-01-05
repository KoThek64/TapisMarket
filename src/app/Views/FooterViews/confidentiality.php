
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Politique de Confidentialité - TapisMarket</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Onest:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Onest', sans-serif; }
        h1, h2, h3 { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 flex flex-col min-h-screen">

    <!-- To Modify with the partial header -->    
    <header class="bg-white border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-[1600px] mx-auto px-[5%] h-20 flex items-center justify-between">
            <a href="<?= base_url('/') ?>" class="text-2xl font-bold font-serif text-slate-900 hover:text-blue-600 transition-colors">
                TapisMarket
            </a>
            <a href="<?= base_url('/') ?>" class="text-sm font-medium text-slate-500 hover:text-slate-900 flex items-center gap-2">
                <span>← Retour à l'accueil</span>
            </a>
        </div>
    </header>

    <main class="flex-grow">
        <div class="max-w-4xl mx-auto px-6 py-16">
            
            <!-- Titre de la page -->
            <div class="mb-12 text-center">
                <h1 class="text-4xl md:text-5xl font-bold text-slate-900 mb-4">Politique de Confidentialité</h1>
                <p class="text-slate-500">Dernière mise à jour : 05/01/2026</p>
            </div>

            <!-- Contenu Confidentialité -->
            <div class="space-y-12 bg-white p-8 md:p-12 rounded-2xl shadow-sm border border-slate-100">

                <!-- Article 1 -->
                <section>
                    <h2 class="text-2xl font-bold text-slate-800 mb-4 flex items-center gap-3">
                        <span class="bg-slate-100 text-slate-600 w-8 h-8 rounded-full flex items-center justify-center text-sm">1</span>
                        Collecte des données
                    </h2>
                    <p class="text-slate-600 leading-relaxed">
                        Nous collectons uniquement les données nécessaires au bon fonctionnement de nos services :<br>
                        - Informations de compte (Nom, Prénom, Email)<br>
                        - Informations de commande (Adresse de livraison, Historique d'achats)<br>
                        Ces données sont collectées lorsque vous créez un compte ou passez une commande sur TapisMarket.
                    </p>
                </section>

                <hr class="border-slate-100">

                <!-- Article 2 -->
                <section>
                    <h2 class="text-2xl font-bold text-slate-800 mb-4 flex items-center gap-3">
                        <span class="bg-slate-100 text-slate-600 w-8 h-8 rounded-full flex items-center justify-center text-sm">2</span>
                        Utilisation des données
                    </h2>
                    <p class="text-slate-600 leading-relaxed">
                        Vos données sont utilisées exclusivement pour :<br>
                        - Gérer votre compte client<br>
                        - Traiter et livrer vos commandes<br>
                        - Vous informer sur le statut de vos achats<br>
                        - Améliorer votre expérience utilisateur sur notre site.
                    </p>
                </section>

                <hr class="border-slate-100">

                <!-- Article 3 -->
                <section>
                    <h2 class="text-2xl font-bold text-slate-800 mb-4 flex items-center gap-3">
                        <span class="bg-slate-100 text-slate-600 w-8 h-8 rounded-full flex items-center justify-center text-sm">3</span>
                        Cookies
                    </h2>
                    <p class="text-slate-600 leading-relaxed">
                        Notre site utilise des cookies techniques essentiels au fonctionnement du panier et de la connexion utilisateur. Aucune donnée n'est revendue à des tiers publicitaires. Vous pouvez configurer votre navigateur pour refuser les cookies, mais cela pourrait limiter certaines fonctionnalités du site.
                    </p>
                </section>

                <hr class="border-slate-100">

                <!-- Article 4 -->
                <section>
                    <h2 class="text-2xl font-bold text-slate-800 mb-4 flex items-center gap-3">
                        <span class="bg-slate-100 text-slate-600 w-8 h-8 rounded-full flex items-center justify-center text-sm">4</span>
                        Vos droits
                    </h2>
                    <p class="text-slate-600 leading-relaxed">
                        Vous avez le droit de demander l'accès, la modification ou la suppression complète de vos données personnelles à tout moment. Pour exercer ce droit, veuillez nous contacter via notre formulaire de contact ou par email à privacy@tapismarket.fr.
                    </p>
                </section>

            </div>
        </div>
    </main>

    
    <?= view('Partial/footer') ?>

</body>
</html>