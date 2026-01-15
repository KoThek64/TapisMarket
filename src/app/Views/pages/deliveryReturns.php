<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>
<div class="max-w-4xl mx-auto px-6 py-16">


    <div class="mb-12 text-center">
        <h1 class="text-4xl md:text-5xl font-bold text-slate-900 mb-4">Livraison & Retours</h1>
        <p class="text-slate-500">Satisfait ou remboursé sous 30 jours</p>
    </div>


    <div class="space-y-12 bg-white p-8 md:p-12 rounded-2xl shadow-sm border border-slate-100">


        <div class="bg-blue-50 border border-blue-100 p-6 rounded-xl text-blue-800 text-sm leading-relaxed">
            <strong>Note importante :</strong> Les tapis sont des objets volumineux. Merci de suivre scrupuleusement la
            procédure d'emballage ci-dessous pour garantir l'acceptation de votre retour.
        </div>

        <section>
            <h2 class="text-2xl font-bold text-slate-800 mb-4 flex items-center gap-3">
                <span
                    class="bg-slate-100 text-slate-600 w-8 h-8 rounded-full flex items-center justify-center text-sm">1</span>
                Faire la demande
            </h2>
            <p class="text-slate-600 leading-relaxed">
                Vous disposez de <strong>30 jours</strong> après réception pour changer d'avis.
                Pour initier un retour, rendez-vous dans votre espace "Mon Compte" > "Mes Commandes" et cliquez sur
                "Retourner un article". Vous recevrez par email une étiquette de transport prépayée.
            </p>
        </section>

        <hr class="border-slate-100">


        <section>
            <h2 class="text-2xl font-bold text-slate-800 mb-4 flex items-center gap-3">
                <span
                    class="bg-slate-100 text-slate-600 w-8 h-8 rounded-full flex items-center justify-center text-sm">2</span>
                Préparer le tapis (Important)
            </h2>
            <div class="text-slate-600 leading-relaxed space-y-3">
                <p>Pour éviter tout dommage durant le transport retour :</p>
                <ul class="list-disc pl-5 space-y-2 marker:text-blue-500">
                    <li><strong>Roulez le tapis</strong> serré (ne le pliez jamais, cela marque la fibre
                        définitivement).</li>
                    <li>Utilisez l'emballage plastique d'origine si possible, ou un plastique épais et solide.</li>
                    <li>Scellez hermétiquement les extrémités avec du ruban adhésif large.</li>
                </ul>
            </div>
        </section>

        <hr class="border-slate-100">


        <section>
            <h2 class="text-2xl font-bold text-slate-800 mb-4 flex items-center gap-3">
                <span
                    class="bg-slate-100 text-slate-600 w-8 h-8 rounded-full flex items-center justify-center text-sm">3</span>
                Expédition et Remboursement
            </h2>
            <p class="text-slate-600 leading-relaxed">
                Collez l'étiquette de retour sur l'emballage. Selon la taille du tapis, un transporteur viendra le
                récupérer chez vous sur rendez-vous, ou vous pourrez le déposer en point relais (pour les petits
                formats).<br><br>
                Le remboursement sera effectué sur votre moyen de paiement d'origine sous <strong>14 jours</strong>
                après réception et vérification de l'état du tapis à notre entrepôt.
            </p>
        </section>

    </div>
</div>
<?= $this->endSection() ?>

