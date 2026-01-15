################################
Installation & Démarrage
################################

Ce guide explique comment installer le projet en local pour le développement.

******************************
Pré-requis Techniques
******************************

Assurez-vous d'avoir les outils suivants installés sur votre machine :

* **PHP 8.1** ou supérieur (extensions ``intl``, ``mbstring``, ``mysqli`` requises).
* **Composer** (Gestionnaire de dépendances PHP).
* **MySQL** ou **MariaDB** (Base de données).
* **Git** (Gestion de version).

******************************
Installation
******************************

1. **Cloner le projet**

   .. code-block:: bash

      git clone https://gitlab.univ-nantes.fr/pub/but/but2/sae/groupe4/eq_4_02_aignelot-youenn_bernard-adam_filmont-felix_lachaise-mattys_plu-niels.git
      cd marketplace-tapis

2. **Installer les dépendances**

   .. code-block:: bash

      composer install

3. **Configuration de l'environnement**

   Copiez le fichier d'exemple et configurez votre base de données :

   .. code-block:: bash

      cp env .env

   Ouvrez ``.env`` et modifiez ces lignes :

   .. code-block:: ini

      CI_ENVIRONMENT = development
      database.default.hostname = localhost
      database.default.database = tp
      database.default.username = root
      database.default.password = root

4. **Migration de la Base de Données**

   Créez les tables automatiquement :

   .. code-block:: bash

      php spark migrate

******************************
Peupler la Base de Données
******************************

Le projet inclut des jeux de données (Seeders) pour ne pas démarrer avec une boutique vide.

**Lancer le remplissage :**

.. code-block:: bash

    # Créer les comptes Admin, Vendeurs et Clients de test
    php spark db:seed DataSeeder

    # (Optionnel) Générer beaucoup de produits pour tester la pagination
    php spark db:seed BigDataSeeder

**Comptes de Test (Mots de passe)**

Voici les identifiants créés par défaut.
⚠️ **Le mot de passe est identique pour tous les comptes :** ``123456``

+-----------+---------------------+--------------+
| Rôle      | Email               | Mot de passe |
+===========+=====================+==============+
| Admin     | admin@tapis.fr      | ``123456``   |
+-----------+---------------------+--------------+
| Vendeur   | vendeur@tapis.fr    | ``123456``   |
+-----------+---------------------+--------------+
| Client    | client@tapis.fr     | ``123456``   |
+-----------+---------------------+--------------+

******************************
Lancer le Serveur
******************************

Pour démarrer le serveur de développement local :

.. code-block:: bash

   php spark serve

Le site sera accessible sur : http://localhost:8080
