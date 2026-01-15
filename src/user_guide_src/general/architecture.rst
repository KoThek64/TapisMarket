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

1.  **Héritage des Utilisateurs** : La table ``users`` porte les identifiants. Les tables ``customers`` et ``sellers`` étendent ce profil via une relation 1:1.
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
     - Mot de passe haché (Bcrypt).
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
     - **FK** vers `users.id`. Propriétaire de l'adresse.
   * - street / city / zip
     - VARCHAR
     - Détails postaux complets.

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
     - Nom de la catégorie (ex: "Tapis Persan").
   * - alias
     - VARCHAR(120)
     - Slug URL (ex: "tapis-persan").

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
   * - price
     - DECIMAL(10,2)
     - Prix unitaire TTC.
   * - stock_available
     - INT
     - Stock temps réel.
   * - product_status
     - ENUM
     - 'PENDING_VALIDATION', 'APPROVED', 'REFUSED', 'OFFLINE'.
   * - dimensions
     - VARCHAR(50)
     - Dimensions (ex: "200x300").
   * - material
     - VARCHAR(100)
     - Matière principale (ex: "Laine").

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
     - Nom du fichier stocké (hashé).
   * - display_order
     - INT
     - Ordre d'affichage (1 = Image Principale).

Groupe : Commandes & Panier
===========================

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
     - Montant total payé par le client.
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

.. list-table:: Table `carts` (Paniers volatils)
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

Groupe : Social
===============

.. list-table:: Table `reviews` (Avis Clients)
   :widths: 25 20 55
   :header-rows: 1

   * - Champ
     - Type
     - Description
   * - rating
     - TINYINT
     - Note (1 à 5).
   * - comment
     - TEXT
     - Avis textuel.
   * - moderation_status
     - ENUM
     - 'PUBLISHED', 'REFUSED'.
