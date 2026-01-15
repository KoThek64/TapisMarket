##############################
Architecture & Base de Données
##############################

Cette section détaille l'architecture technique du Marketplace, le schéma relationnel complet et le dictionnaire des données.

********************
Architecture Globale
********************

Le projet suit le motif de conception **MVC (Modèle-Vue-Contrôleur)** imposé par le framework CodeIgniter 4.

* **Modèles (`app/Models`)** : Couche d'accès aux données. Ils contiennent la logique métier.
* **Vues (`app/Views`)** : Interface utilisateur (HTML/Tailwind CSS).
* **Contrôleurs (`app/Controllers`)** : Orchestration des requêtes HTTP.

*************************
Schéma de Base de Données
*************************

Le schéma est normalisé (3NF). Voici les concepts clés :

1.  **Héritage des Utilisateurs** : La table ``users`` porte les identifiants. Les tables ``customers``, ``sellers`` et ``administrators`` étendent ce profil via une relation 1:1.
2.  **Snapshot des Commandes** : Les données vitales (prix, adresse) sont dupliquées dans la commande pour figer l'historique, indépendamment des modifications futures du vendeur.
3.  **Soft Deletes** : Les produits et utilisateurs ne sont jamais supprimés physiquement (``deleted_at``) pour maintenir l'intégrité des anciennes commandes.

************************
Dictionnaire des Données
************************

Voici la documentation exhaustive de toutes les tables du système.

Groupe : Utilisateurs & Rôles
=============================

.. list-table:: Table `users` (Comptes principaux)
   :widths: 25 20 55
   :header-rows: 1

   * - Champ
     - Type
     - Description
   * - id
     - INT
     - **PK**. Identifiant unique auto-incrémenté.
   * - email
     - VARCHAR(255)
     - **Unique**. Email de connexion.
   * - password
     - VARCHAR(255)
     - Mot de passe haché.
   * - role
     - ENUM
     - Rôle : 'ADMIN', 'SELLER', 'CUSTOMER'.
   * - lastname / firstname
     - VARCHAR(100)
     - Nom et Prénom de l'utilisateur.

.. list-table:: Table `sellers` (Profils Vendeurs)
   :widths: 25 20 55
   :header-rows: 1

   * - Champ
     - Type
     - Description
   * - user_id
     - INT
     - **PK, FK** vers `users.id`.
   * - shop_name
     - VARCHAR(100)
     - Nom commercial de la boutique.
   * - shop_description
     - TEXT
     - Description publique de la boutique.
   * - siret
     - CHAR(14)
     - **Unique**. Numéro d'identification légale.
   * - status
     - ENUM
     - 'PENDING_VALIDATION', 'VALIDATED', 'REFUSED', 'SUSPENDED'.
   * - refusal_reason
     - TEXT
     - Motif en cas de refus par l'admin.

.. list-table:: Table `customers` (Profils Clients)
   :widths: 25 20 55
   :header-rows: 1

   * - Champ
     - Type
     - Description
   * - user_id
     - INT
     - **PK, FK** vers `users.id`.
   * - phone
     - VARCHAR(20)
     - Numéro de téléphone personnel.
   * - birth_date
     - DATE
     - Date de naissance.

.. list-table:: Table `administrators` (Profils Admin)
   :widths: 25 20 55
   :header-rows: 1

   * - Champ
     - Type
     - Description
   * - user_id
     - INT
     - **PK, FK** vers `users.id`. Table de liaison simple.

.. list-table:: Table `addresses` (Carnet d'adresses)
   :widths: 25 20 55
   :header-rows: 1

   * - Champ
     - Type
     - Description
   * - id
     - INT
     - **PK**.
   * - user_id
     - INT
     - **FK** vers `users.id`. Propriétaire.
   * - street / city / zip
     - VARCHAR
     - Détails postaux complets.
   * - country
     - VARCHAR(100)
     - Pays de l'adresse.

Groupe : Catalogue Produit
==========================

.. list-table:: Table `categories`
   :widths: 25 20 55
   :header-rows: 1

   * - Champ
     - Type
     - Description
   * - id
     - INT
     - **PK**.
   * - name
     - VARCHAR(100)
     - Nom de la catégorie.
   * - alias
     - VARCHAR(120)
     - Slug URL (ex: "tapis-persan").
   * - image_url
     - VARCHAR
     - Chemin vers l'image de la catégorie.

.. list-table:: Table `products` (Fiches Produits)
   :widths: 25 20 55
   :header-rows: 1

   * - Champ
     - Type
     - Description
   * - id
     - INT
     - **PK**.
   * - seller_id
     - INT
     - **FK** vers `sellers.user_id`.
   * - category_id
     - INT
     - **FK** vers `categories.id`.
   * - title
     - VARCHAR(150)
     - Titre du produit.
   * - alias
     - VARCHAR(150)
     - **Unique**. Slug URL pour le SEO.
   * - price
     - DECIMAL(10,2)
     - Prix unitaire TTC.
   * - stock_available
     - INT
     - Stock temps réel.
   * - product_status
     - ENUM
     - 'PENDING_VALIDATION', 'APPROVED', 'REFUSED', 'OFFLINE'.
   * - description
     - TEXT
     - Contient `short_description` et `long_description`.

.. list-table:: Table `product_photos`
   :widths: 25 20 55
   :header-rows: 1

   * - Champ
     - Type
     - Description
   * - id
     - INT
     - **PK**.
   * - product_id
     - INT
     - **FK** vers `products.id`.
   * - file_name
     - VARCHAR(255)
     - Nom du fichier stocké.
   * - display_order
     - INT
     - Ordre d'affichage (1 = Image Principale).

Groupe : Commandes & Panier
===========================

.. list-table:: Table `carts` (Paniers)
   :widths: 25 20 55
   :header-rows: 1

   * - Champ
     - Type
     - Description
   * - id
     - INT
     - **PK**.
   * - customer_id
     - INT
     - **Unique**. Propriétaire du panier.
   * - total
     - DECIMAL
     - Total calculé (cache).
   * - updated_at
     - DATETIME
     - Date de dernière modification.

.. list-table:: Table `cart_items` (Contenu Panier)
   :widths: 25 20 55
   :header-rows: 1

   * - Champ
     - Type
     - Description
   * - id
     - INT
     - **PK**.
   * - cart_id
     - INT
     - **FK** vers `carts.id`.
   * - product_id
     - INT
     - **FK** vers `products.id`.
   * - quantity
     - INT
     - Quantité ajoutée au panier.

.. list-table:: Table `orders` (Commandes figées)
   :widths: 25 20 55
   :header-rows: 1

   * - Champ
     - Type
     - Description
   * - id
     - INT
     - **PK**.
   * - reference
     - VARCHAR(50)
     - **Unique**. Référence client (ex: CMD-XJ9).
   * - status
     - ENUM
     - 'PAID', 'PREPARING', 'SHIPPED', 'DELIVERED', 'CANCELLED'.
   * - total_ttc
     - DECIMAL(10,2)
     - Montant total (Produits + Frais de port).
   * - shipping_fees
     - DECIMAL(10,2)
     - Frais de port appliqués.
   * - delivery_method
     - VARCHAR(100)
     - Transporteur choisi (ex: "Express Carrier").
   * - delivery_street
     - VARCHAR
     - Adresse de livraison (copie figée).

.. list-table:: Table `order_items` (Lignes de commande)
   :widths: 25 20 55
   :header-rows: 1

   * - Champ
     - Type
     - Description
   * - order_id
     - INT
     - **FK** vers `orders.id`.
   * - product_id
     - INT
     - **FK** vers `products.id`.
   * - unit_price
     - DECIMAL
     - Prix unitaire **au moment de l'achat**.
   * - quantity
     - INT
     - Quantité achetée.

Groupe : Social
===============

.. list-table:: Table `reviews` (Avis Clients)
   :widths: 25 20 55
   :header-rows: 1

   * - Champ
     - Type
     - Description
   * - customer_id
     - INT
     - **FK** vers `customers.user_id`.
   * - product_id
     - INT
     - **FK** vers `products.id`.
   * - rating
     - TINYINT
     - Note (1 à 5).
   * - comment
     - TEXT
     - Avis textuel.
   * - moderation_status
     - ENUM
     - 'PUBLISHED', 'REFUSED'.

**********************
Diagramme Relationnel
**********************

Voici la structure globale de la base de données (MCD) du projet :

.. image:: ../_static/mcd.png
   :width: 100%
   :alt: Schéma de la base de données
   :align: center
