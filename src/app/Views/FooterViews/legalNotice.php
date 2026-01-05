
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Mentions Légales - TapisMarket</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Onest:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Onest', sans-serif; }
        h1, h2, h3 { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 flex flex-col min-h-screen">

    <!-- To Modify with the header partial -->
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
            
            
            <div class="mb-12 text-center">
                <h1 class="text-4xl md:text-5xl font-bold text-slate-900 mb-4">Mentions Légales</h1>
                <p class="text-slate-500">En vigueur au 01/01/2025</p>
            </div>

            
            <div class="space-y-12 bg-white p-8 md:p-12 rounded-2xl shadow-sm border border-slate-100">

                
                <section>
                    <h2 class="text-2xl font-bold text-slate-800 mb-4 flex items-center gap-3">
                        <span class="bg-slate-100 text-slate-600 w-8 h-8 rounded-full flex items-center justify-center text-sm">1</span>
                        Édition du site
                    </h2>
                    <p class="text-slate-600 leading-relaxed">
                        Le site TapisMarket est édité par l'équipe projet 4.02 dans le cadre de la SAE.<br><br>
                        <strong>Adresse :</strong> IUT de Nantes<br>
                        <strong>Contact :</strong> contact@tapismarket.fr<br>
                        <strong>Directeur de la publication :</strong> L'équipe 4.02
                    </p>
                </section>

                <hr class="border-slate-100">

               
                <section>
                    <h2 class="text-2xl font-bold text-slate-800 mb-4 flex items-center gap-3">
                        <span class="bg-slate-100 text-slate-600 w-8 h-8 rounded-full flex items-center justify-center text-sm">2</span>
                        Hébergement
                    </h2>
                    <p class="text-slate-600 leading-relaxed">
                        Le site est hébergé par l'infrastructure de l'IUT de Lannion / Université de Rennes.<br>
                        Les données sont stockées sur des serveurs situés en France.
                    </p>
                </section>

                <hr class="border-slate-100">

               
                <section>
                    <h2 class="text-2xl font-bold text-slate-800 mb-4 flex items-center gap-3">
                        <span class="bg-slate-100 text-slate-600 w-8 h-8 rounded-full flex items-center justify-center text-sm">3</span>
                        Propriété intellectuelle
                    </h2>
                    <p class="text-slate-600 leading-relaxed">
                        L'ensemble de ce site relève de la législation française et internationale sur le droit d'auteur et la propriété intellectuelle. Tous les droits de reproduction sont réservés, y compris pour les documents téléchargeables et les représentations iconographiques et photographiques.<br><br>
                        La reproduction de tout ou partie de ce site sur un support électronique quel qu'il soit est formellement interdite sauf autorisation expresse du directeur de la publication.
                    </p>
                </section>

                <hr class="border-slate-100">

                
                <section>
                    <h2 class="text-2xl font-bold text-slate-800 mb-4 flex items-center gap-3">
                        <span class="bg-slate-100 text-slate-600 w-8 h-8 rounded-full flex items-center justify-center text-sm">4</span>
                        Données personnelles (RGPD)
                    </h2>
                    <p class="text-slate-600 leading-relaxed">
                        Conformément au Règlement Général sur la Protection des Données (RGPD), vous disposez d’un droit d’accès, de rectification et de suppression des données vous concernant.<br><br>
                        Aucune information personnelle n'est collectée à votre insu. Aucune information personnelle n'est cédée à des tiers. Les courriels, les adresses électroniques ou autres informations nominatives dont ce site est destinataire ne font l'objet d'aucune exploitation et ne sont conservés que pour la durée nécessaire à leur traitement.
                    </p>
                </section>

            </div>
        </div>
    </main>

    
    <?= view('Partial/footer') ?>

</body>
</html>