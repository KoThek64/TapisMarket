#######################
Helpers & Utilitaires
#######################

Le projet utilise des "Helpers" CodeIgniter personnalisés pour simplifier les tâches répétitives dans les Vues et les Contrôleurs.

****************
Auth Helper
****************
Chargé automatiquement. Gère l'état de connexion.

.. php:function:: user()

   Récupère l'entité de l'utilisateur actuellement connecté.

   :returns: App\Entities\User|null

.. php:function:: logged_in()

   Vérifie si un utilisateur est connecté.

   :returns: bool

.. php:function:: is_admin()

   Vérifie si l'utilisateur connecté a le rôle ADMIN.

   :returns: bool

.. php:function:: is_seller()

   Vérifie si l'utilisateur connecté a le rôle SELLER.
   
   :returns: bool

****************
Alert Helper
****************
Gère l'affichage des notifications flash (Succès, Erreur).

.. php:function:: display_alert()

   Génère le code HTML (Tailwind CSS) pour afficher les messages flash stockés en session.
   Supporte les types : success, error, warning, info.

****************
Icon Helper
****************

.. php:function:: icon(string $name, string $classes = '')

   Génère le SVG d'une icône HeroIcons.
   
   :param string $name: Nom de l'icône (ex: 'trash', 'user', 'cart').
   :param string $classes: Classes CSS additionnelles.
   :returns: string (HTML SVG)
