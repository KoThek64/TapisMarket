########################
Installation & Démarrage
########################

Ce guide détaille comment installer et lancer le projet Marketplace Tapis en local.

*********
Prérequis
*********

* **PHP** 8.1 ou supérieur (extensions : intl, mbstring, mysql).
* **Composer** (Gestionnaire de dépendances).
* **MySQL** ou MariaDB.
* **Serveur Web** (Apache/Nginx) ou le serveur interne PHP.

************
Installation
************

1. **Cloner le projet** ::

    git clone <url_du_repo>
    cd marketplace-tapis

2. **Installer les dépendances** ::

    composer install

3. **Configuration de l'environnement**
   Copiez le fichier env en .env et configurez votre base de données :

   .. code-block:: ini

      database.default.hostname = localhost
      database.default.database = sae_tapis
      database.default.username = root
      database.default.password =
      database.default.DBDriver = MySQLi

      CI_ENVIRONMENT = development

4. **Migrations (Création des tables)** ::

    php spark migrate

************************
Jeu de Données (Seeding)
************************

Le projet inclut un seeder puissant pour générer des données de test réalistes (Vendeurs, Produits, Commandes).

Lancer le seed complet
======================

Créez d'abord le dossier pour les images sources : writable/seed_images/ et placez-y quelques images (jpg/png).

Ensuite, lancez la commande ::

    php spark db:seed DataSeeder

.. note::
   Le seeder crée automatiquement :
   * **Admins** : admin@tapis.com / 123456
   * **Vendeurs** : seller0@mail.com (etc...)
   * **Clients** : client0@mail.com (etc...)
