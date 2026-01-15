<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>
<div class="max-w-4xl mx-auto px-6 py-16">


    <div class="mb-12 text-center">
        <h1 class="text-4xl md:text-5xl font-bold text-slate-900 mb-4">Mentions Légales</h1>
        <p class="text-slate-500">En vigueur au 01/01/2025</p>
    </div>


    <div class="space-y-12 bg-white p-8 md:p-12 rounded-2xl shadow-sm border border-slate-100">


        <section>
            <h2 class="text-2xl font-bold text-slate-800 mb-4 flex items-center gap-3">
                <span
                    class="bg-slate-100 text-slate-600 w-8 h-8 rounded-full flex items-center justify-center text-sm">1</span>
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
                <span
                    class="bg-slate-100 text-slate-600 w-8 h-8 rounded-full flex items-center justify-center text-sm">2</span>
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
                <span
                    class="bg-slate-100 text-slate-600 w-8 h-8 rounded-full flex items-center justify-center text-sm">3</span>
                Propriété intellectuelle
            </h2>
            <p class="text-slate-600 leading-relaxed">
                L'ensemble de ce site relève de la législation française et internationale sur le droit d'auteur et la
                propriété intellectuelle. Tous les droits de reproduction sont réservés, y compris pour les documents
                téléchargeables et les représentations iconographiques et photographiques.<br><br>
                La reproduction de tout ou partie de ce site sur un support électronique quel qu'il soit est
                formellement interdite sauf autorisation expresse du directeur de la publication.
            </p>
        </section>

        <hr class="border-slate-100">


        <section>
            <h2 class="text-2xl font-bold text-slate-800 mb-4 flex items-center gap-3">
                <span
                    class="bg-slate-100 text-slate-600 w-8 h-8 rounded-full flex items-center justify-center text-sm">4</span>
                Données personnelles (RGPD)
            </h2>
            <p class="text-slate-600 leading-relaxed">
                Conformément au Règlement Général sur la Protection des Données (RGPD), vous disposez d’un droit
                d’accès, de rectification et de suppression des données vous concernant.<br><br>
                Aucune information personnelle n'est collectée à votre insu. Aucune information personnelle n'est cédée
                à des tiers. Les courriels, les adresses électroniques ou autres informations nominatives dont ce site
                est destinataire ne font l'objet d'aucune exploitation et ne sont conservés que pour la durée nécessaire
                à leur traitement.
            </p>
        </section>

    </div>
</div>
<?= $this->endSection() ?>

