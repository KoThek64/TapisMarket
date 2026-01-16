########################
Installation & Démarrage
########################

Cette page détaille les différentes méthodes pour lancer le projet, que ce soit en production (avec Podman) ou en développement local.

***********************************
1. Production (Podman & Conteneurs)
***********************************

Le projet utilise ``podman`` et ``podman-compose``.

Lancer le projet
================

1. **Copier les sources**
   
   Il faut dans un premier temps copier le dossier ``src`` dans le conteneur :

   .. code-block:: bash

      cp -R src conteneur/app_php/src

2. **Démarrer les conteneurs**

   Lancez ``podman-compose`` en spécifiant le fichier d'environnement :

   .. code-block:: bash

      podman-compose -f conteneur/compose.prod.yml --env-file conteneur/prod.env up -d

3. **Migrations et Données**

   Une fois les conteneurs lancés, exécutez les migrations :

   .. code-block:: bash

      podman-compose -f conteneur/compose.prod.yml exec web-prod php spark migrate

   (Optionnel) Pour peupler la base avec des données de test :

   .. code-block:: bash

      podman-compose -f conteneur/compose.prod.yml exec web-prod php spark db:seed DataSeeder

Configuration (Variables d'environnement)
=========================================

Pour customiser l'instance, éditez le fichier ``conteneur/app_php/prod.env`` :

* ``ENVIRONMENT`` : L'environnement (soit ``production`` soit ``development``).
* ``DB_ROOT_PASSWORD`` : Le mot de passe root de la DB.
* ``DB_USER`` : L'utilisateur de la DB.
* ``DB_PASSWORD`` : Le mot de passe de l'utilisateur de la DB.
* ``DB_DATABASE`` : Le nom de la DB que le site va utiliser.

**Comptes de Test**

Voici les identifiants créés par défaut.
**Le mot de passe est identique pour tous les comptes :** ``123456``

+-----------+---------------------+--------------+
| Rôle      | Email               | Mot de passe |
+===========+=====================+==============+
| Admin     | admin@tapis.com     | ``123456``   |
+-----------+---------------------+--------------+
| Vendeur   | seller0@mail.com    | ``123456``   |
+-----------+---------------------+--------------+
| Client    | client0@mail.com    | ``123456``   |
+-----------+---------------------+--------------+

Le site sera accessible sur : http://localhost:8080