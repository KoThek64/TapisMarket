
<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>
        <div class="max-w-3xl mx-auto px-6 py-16">
            
            
            <div class="mb-12 text-center">
                <h1 class="text-4xl md:text-5xl font-bold text-slate-900 mb-4">Questions Fréquentes</h1>
                <p class="text-slate-500">Tout ce que vous devez savoir sur nos tapis et services.</p>
            </div>

            
            <div class="space-y-4">

            
                <details class="group bg-white rounded-xl border border-slate-200 overflow-hidden transition-all duration-300 hover:border-blue-300 open:border-blue-500 open:ring-1 open:ring-blue-500">
                    <summary class="flex items-center justify-between p-6 cursor-pointer font-medium text-slate-900 select-none">
                        <span>Comment entretenir mon tapis ?</span>
                        <span class="transition-transform duration-300 group-open:rotate-180 text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                            </svg>
                        </span>
                    </summary>
                    <div class="px-6 pb-6 text-slate-600 leading-relaxed border-t border-slate-100 pt-4">
                        Pour un entretien régulier, passez l'aspirateur une à deux fois par semaine dans le sens du poil. En cas de tache, agissez immédiatement en tamponnant (sans frotter) avec un chiffon propre et humide. Pour un nettoyage en profondeur, nous recommandons un nettoyage professionnel tous les 2 à 3 ans.
                    </div>
                </details>

                
                <details class="group bg-white rounded-xl border border-slate-200 overflow-hidden transition-all duration-300 hover:border-blue-300 open:border-blue-500 open:ring-1 open:ring-blue-500">
                    <summary class="flex items-center justify-between p-6 cursor-pointer font-medium text-slate-900 select-none">
                        <span>Quels sont les délais de livraison ?</span>
                        <span class="transition-transform duration-300 group-open:rotate-180 text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                            </svg>
                        </span>
                    </summary>
                    <div class="px-6 pb-6 text-slate-600 leading-relaxed border-t border-slate-100 pt-4">
                        Les tapis en stock sont expédiés sous 24h à 48h. La livraison prend ensuite 2 à 5 jours ouvrés selon votre localisation. Pour les tapis sur mesure, comptez un délai de fabrication de 4 à 6 semaines.
                    </div>
                </details>

                
                <details class="group bg-white rounded-xl border border-slate-200 overflow-hidden transition-all duration-300 hover:border-blue-300 open:border-blue-500 open:ring-1 open:ring-blue-500">
                    <summary class="flex items-center justify-between p-6 cursor-pointer font-medium text-slate-900 select-none">
                        <span>Les photos sont-elles fidèles à la réalité ?</span>
                        <span class="transition-transform duration-300 group-open:rotate-180 text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                            </svg>
                        </span>
                    </summary>
                    <div class="px-6 pb-6 text-slate-600 leading-relaxed border-t border-slate-100 pt-4">
                        Nous faisons notre maximum pour que les photos soient les plus fidèles possible. Cependant, selon le réglage de votre écran et l'éclairage de votre pièce, de légères variations de nuances peuvent apparaître. N'hésitez pas à consulter les avis clients qui contiennent souvent des photos "en situation".
                    </div>
                </details>

                
                <details class="group bg-white rounded-xl border border-slate-200 overflow-hidden transition-all duration-300 hover:border-blue-300 open:border-blue-500 open:ring-1 open:ring-blue-500">
                    <summary class="flex items-center justify-between p-6 cursor-pointer font-medium text-slate-900 select-none">
                        <span>Puis-je retourner mon tapis s'il ne me plaît pas ?</span>
                        <span class="transition-transform duration-300 group-open:rotate-180 text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                            </svg>
                        </span>
                    </summary>
                    <div class="px-6 pb-6 text-slate-600 leading-relaxed border-t border-slate-100 pt-4">
                        Absolument. Vous disposez de 30 jours pour changer d'avis. Consultez notre page <a href="<?= base_url('/delivery-returns') ?>" class="text-blue-600 hover:underline">Livraison & Retours</a> pour connaître la procédure exacte.
                    </div>
                </details>

                
                 <details class="group bg-white rounded-xl border border-slate-200 overflow-hidden transition-all duration-300 hover:border-blue-300 open:border-blue-500 open:ring-1 open:ring-blue-500">
                    <summary class="flex items-center justify-between p-6 cursor-pointer font-medium text-slate-900 select-none">
                        <span>Proposez-vous des tapis sur mesure ?</span>
                        <span class="transition-transform duration-300 group-open:rotate-180 text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                            </svg>
                        </span>
                    </summary>
                    <div class="px-6 pb-6 text-slate-600 leading-relaxed border-t border-slate-100 pt-4">
                        Oui, certains de nos modèles sont disponibles en dimensions personnalisées. Recherchez la mention "Sur mesure disponible" sur la fiche produit ou contactez notre service client pour un devis spécifique.
                    </div>
                </details>

            </div>

            
            <div class="mt-16 text-center bg-blue-50 rounded-2xl p-8 border border-blue-100">
                <h3 class="text-xl font-bold text-blue-900 mb-2">Vous ne trouvez pas votre réponse ?</h3>
                <p class="text-blue-700 mb-6">Notre équipe est là pour vous aider du lundi au vendredi.</p>
                <a href="mailto:contact@tapismarket.fr" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-700 transition-colors shadow-lg shadow-blue-600/20">
                    Contactez le support
                </a>
            </div>

        </div>
    </main>

    
     <?= $this->endSection() ?>