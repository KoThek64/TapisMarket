########################
Vues & Interface (Views)
########################

L'interface est construite avec le moteur de template natif de CodeIgniter et **Tailwind CSS**.

**********************
Structure des Dossiers
**********************

.. code-block:: text

   app/Views/
   ├── layouts/          
   │   ├── default.php       # Layout public (Navbar + Footer)
   │   └── with_sidebar.php  # Layout Dashboard (Admin/Vendeur/Client)
   ├── partials/         # Composants réutilisables
   │   ├── carpet_card.php   # Carte produit catalogue
   │   ├── alert_handler.php # Notifications
   │   └── sidebar/          # Menus latéraux dynamiques
   ├── pages/            # Pages statiques ou uniques (Home, Checkout...)
   ├── admin/            # Vues spécifiques Admin
   ├── seller/           # Vues spécifiques Vendeur
   └── client/           # Vues spécifiques Client

***************
Composants Clés
***************

Carte Produit (Carpet Card)
===========================
Utilisée dans le catalogue et l'accueil. Affiche :

* Image principale (ou placeholder).
* Titre et prix.
* Badge "Stock faible" si nécessaire.
* Note moyenne (Étoiles).

Layout "With Sidebar"
=====================
Layout intelligent utilisé pour tous les tableaux de bord.
Il change le contenu de la barre latérale (sidebar/panel.php) dynamiquement selon le rôle de l'utilisateur (Admin, Vendeur ou Client).
