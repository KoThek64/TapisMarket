######################
Déploiement Production
######################

Cette section détaille la mise en production sur un serveur Linux (type VPS ou Serveur IUT).

************************
Architecture du Dossier
************************

Sur le serveur, le projet doit être installé dans votre dossier Web (ex: ``/var/www/html`` ou ``/var/www/tp``).
Attention : La racine du site Web (DocumentRoot) doit pointer vers le dossier ``public/``.

.. code-block:: text

    /var/www/tp/
    ├── app/
    ├── public/       <-- RACINE DU SITE WEB (Apache/Nginx)
    ├── writable/     <-- DOIT ÊTRE EN ÉCRITURE
    ├── .env          <-- FICHIER DE PROD
    └── spark

************************
Configuration Serveur
************************

Configuration type pour Apache (VirtualHost) :

.. code-block:: apache

    <VirtualHost *:80>
        ServerName tp.votre-domaine.fr
        DocumentRoot /var/www/tp/public

        <Directory /var/www/tp/public>
            Options Indexes FollowSymLinks
            AllowOverride All
            Require all granted
        </Directory>
    </VirtualHost>

************************
Permissions & Sécurité
************************

1. **Permissions** : Apache doit pouvoir écrire dans `writable` pour les caches et uploads d'images.

   .. code-block:: bash

      cd /var/www/tp
      chown -R www-data:www-data writable/
      chmod -R 775 writable/

2. **Base de Données (Prod)** : 
   Modifiez le fichier ``.env`` pour utiliser la base **tp**.

   .. code-block:: ini

      CI_ENVIRONMENT = production
      
      # URL de production
      app.baseURL = 'https://tp.votre-domaine.fr/'

      # Accès BDD
      database.default.hostname = localhost
      database.default.database = tp
      database.default.username = user
      database.default.password = 'VotreMotDePasseProd'
      database.default.DBDriver = MySQLi
