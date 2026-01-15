#########################
Contrôleurs (Controllers)
#########################

Les contrôleurs orchestrent les flux de l'application et sont organisés par espaces (Namespaces).

****************
Authentification
****************

.. php:class:: App\Controllers\Auth

   Gère l'inscription, la connexion et la déconnexion.
   
   * **Login** : Vérifie email/mot de passe et initialise la session.
   * **Register** : Crée un nouvel utilisateur (Client ou Vendeur).
   * **Logout** : Détruit la session.

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

   Vérification de commande : Validation adresse et création commande.

*************
Espace Client
*************

.. php:class:: App\Controllers\Client\Dashboard

   Vue synthétique pour le client (Dernières commandes, infos perso).

.. php:class:: App\Controllers\Client\Orders

   Suivi des commandes et accès aux factures/détails.

.. php:class:: App\Controllers\Client\Reviews

   Gestion des avis : Laisser un commentaire sur un produit livré.

.. php:class:: App\Controllers\Client\Addresses

   CRUD des adresses de livraison/facturation.

.. php:class:: App\Controllers\Client\Profile

   Modification des informations personnelles (Nom, Mot de passe).

**************
Espace Vendeur
**************

.. php:class:: App\Controllers\Seller\Dashboard

   Tableau de bord vendeur : Statistiques de ventes et état de la boutique.

.. php:class:: App\Controllers\Seller\Products

   Gestion du catalogue : Ajouter, Modifier, Archiver des produits.

.. php:class:: App\Controllers\Seller\Photos

   Gestionnaire spécifique pour l'upload multiple de photos par produit (max 5 par produit).

.. php:class:: App\Controllers\Seller\Orders

   Gestion des commandes reçues. Permet de changer le statut (Préparation -> Expédié).

.. php:class:: App\Controllers\Seller\Shop

   Personnalisation de la boutique (Nom, Description).

.. php:class:: App\Controllers\Seller\Reviews

   Consultation des avis laissés par les clients sur les produits du vendeur.

************
Espace Admin
************

.. php:class:: App\Controllers\Admin\Dashboard

   Vue globale de l'activité du site (KPIs).

.. php:class:: App\Controllers\Admin\Users

   Modération des utilisateurs et validation des vendeurs.

.. php:class:: App\Controllers\Admin\Categories

   Gestion des catégories de produits (Création, Image, Alias).

.. php:class:: App\Controllers\Admin\Products

   Modération des produits : Valider ou Refuser les produits soumis par les vendeurs.

.. php:class:: App\Controllers\Admin\Reviews

   Modération des avis clients (Suppression des contenus inappropriés).

.. php:class:: App\Controllers\Admin\Orders

   Vue d'ensemble de toutes les commandes de la plateforme (Litiges, Suivi).
