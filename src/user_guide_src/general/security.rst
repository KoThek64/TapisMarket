###################
Sécurité & Routage
###################

Cette section détaille les mécanismes de protection de l'application tapismarket.

.. php:class:: App\Filters\AuthFilter

   Ce filtre intercepte les requêtes avant qu'elles n'atteignent les contrôleurs.

   * **Rôle :** Vérifie que l'utilisateur est connecté et possède les bons droits.
   * **Configuration :** Définie dans ``app/Config/Filters.php``.

   Règles appliquées :

   1. **Admin** (routes ``/admin/*``) :

      * Doit être connecté.
      * Doit avoir le rôle ``ADMIN``.
      * Sinon : Redirection vers Accueil ou Login.


   2. **Vendeur** (routes ``/seller/*``) :

      * Doit être connecté.
      * Doit avoir le rôle ``SELLER``.
      * Doit avoir le statut ``VALIDATED``.


   3. **Client** (routes ``/client/*``) :

      * Doit être connecté.

*************************
Matrice des Permissions
*************************

Récapitulatif des actions autorisées selon le rôle de l'utilisateur :

.. list-table::
   :widths: 40 15 15 15
   :header-rows: 1

   * - Action
     - Client
     - Vendeur
     - Admin
   * - **Parcourir le catalogue**
     - ✅
     - ✅
     - ✅
   * - **Passer une commande**
     - ✅
     - ❌
     - ❌
   * - **Gérer sa boutique**
     - ❌
     - ✅
     - 🔶 (Modération)
   * - **Gérer les produits**
     - ❌
     - ✅ (Les siens)
     - ✅ (Tous)
   * - **Voir les statistiques**
     - ❌
     - ✅ (Ventes)
     - ✅ (Globales)
   * - **Valider des vendeurs**
     - ❌
     - ❌
     - ✅
