##################
Sécurité & Routage
##################

La sécurité de l'application repose sur le système de **Filters** de CodeIgniter 4.

*********************
Filtres (Middlewares)
*********************

.. php:class:: App\Filters\AuthFilter

   Ce filtre intercepte les requêtes avant qu'elles n'atteignent les contrôleurs.

   * **Rôle :** Vérifie que l'utilisateur est connecté et possède les bons droits.
   * **Configuration :** Définie dans app/Config/Filters.php.

   Règles appliquées :
   
   1. **Admin** (/admin/*) :
   
      * Doit être connecté.
      * Doit avoir role === 'ADMIN'.
      * Sinon : Redirection vers Accueil ou Login.

   2. **Vendeur** (/seller/*) :
   
      * Doit être connecté.
      * Doit avoir role === 'SELLER'.
      * Doit avoir status === 'VALIDATED' (Un vendeur en attente ne peut pas gérer ses produits).

   3. **Client** (/client/*) :
   
      * Doit être connecté.

***************
Protection CSRF
***************

Toutes les soumissions de formulaires (POST) sont protégées par un jeton CSRF automatique pour empêcher les attaques inter-sites.
