#########################
Contrôleurs (Controllers)
#########################

Les contrôleurs orchestrent les flux de l'application.

*************
Espace Public
*************

.. php:class:: App\Controllers\Home

   Page d'accueil, mise en avant des produits récents et catégories.

.. php:class:: App\Controllers\Catalog

   Catalogue complet avec filtres (Prix, Catégorie, Recherche). Gère la pagination.

.. php:class:: App\Controllers\Cart

   Gestion du panier : Ajouter (AJAX), Supprimer, Voir le récapitulatif.

.. php:class:: App\Controllers\Checkout

   Tunnel de commande :
   1. Vérification de la connexion.
   2. Choix de l'adresse de livraison.
   3. Simulation de paiement.
   4. Création de la commande via ``OrderModel``.

*************
Espace Client
*************

.. php:class:: App\Controllers\Client\Dashboard

   Vue synthétique pour le client (Dernières commandes, infos perso).

.. php:class:: App\Controllers\Client\Orders

   Suivi des commandes et accès aux factures/détails.

.. php:class:: App\Controllers\Client\Reviews

   Permet au client de laisser un avis sur les produits qu'il a achetés et reçus.

**************
Espace Vendeur
**************

.. php:class:: App\Controllers\Seller\Products

   CRUD complet des produits :
   * Liste avec filtres.
   * Création de fiche produit.
   * Edition des détails.

.. php:class:: App\Controllers\Seller\Photos

   Gestionnaire spécifique pour l'upload multiple de photos par produit.

.. php:class:: App\Controllers\Seller\Orders

   Vue des produits vendus. Le vendeur peut changer le statut de préparation (ex: passer à "Expédié").

************
Espace Admin
************

.. php:class:: App\Controllers\Admin\Users

   Modération des comptes. Permet de valider ou rejeter les candidatures de vendeurs.

.. php:class:: App\Controllers\Admin\Dashboard

   Statistiques globales de la plateforme (Chiffre d'affaires total, nombre d'inscrits, top ventes).