<footer class="bg-slate-900 text-white pt-20 pb-10 mt-20">
    <div class="max-w-5xl mx-auto px-[5%] grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-24 mb-16">
        <div class="col-span-1 md:col-span-2 lg:col-span-1">
            <h3 class="font-serif text-2xl font-bold mb-6 text-white">TapisMarket</h3>
            <p class="text-slate-300 text-sm max-w-sm leading-relaxed">
                Votre marketplace de confiance pour découvrir et acheter des tapis d’exception.
            </p>
        </div>
        <div>
            <h4 class="font-bold text-[10px] uppercase tracking-[0.2em] text-slate-200 mb-6">Liens Rapides</h4>
            <ul class="space-y-3 text-sm text-slate-300">
                <li><a href="<?= base_url('/catalog') ?>" class="hover:text-white transition-colors">Catalogue
                        Complet</a></li>
                <li><a href="<?= base_url('/catalog') ?>" class="hover:text-white transition-colors">Tapis sur
                        Mesure</a></li>
                <li><a href="<?= base_url('/') ?>" class="hover:text-white transition-colors">Promotions</a></li>
            </ul>
        </div>
        <div>
            <h4 class="font-bold text-[10px] uppercase tracking-[0.2em] text-slate-200 mb-6">Service Client</h4>
            <ul class="space-y-3 text-sm text-slate-300">
                <li><a href="<?= base_url('/delivery-returns') ?>" class="hover:text-white transition-colors">Livraison
                        & Retours</a></li>

                <!-- redirects to a contact page, we don't really know what it is. -->
                <li><a href="<?= base_url('/faq') ?>" class="hover:text-white transition-colors">FAQ</a></li>
            </ul>
        </div>
    </div>
    <div
        class="max-w-5xl mx-auto px-[5%] border-t border-slate-800 pt-8 flex flex-col md:flex-row justify-between items-center text-[10px] text-slate-400 uppercase tracking-widest">
        <p>© 2025 TapisMarket. Tous droits réservés.</p>
        <div class="flex gap-6 mt-4 md:mt-0">
            <a href="<?= base_url('/legal-notice') ?>" class="hover:text-white">Mentions Légales</a>
            <a href="<?= base_url('/confidentiality') ?>" class="hover:text-white">Confidentialité</a>
        </div>
    </div>
</footer>
