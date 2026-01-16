################
Modèles (Models)
################

Les modèles gèrent les interactions avec la base de données. Ils sont situés dans ``app/Models``.

.. note::
   Tous les modèles héritent de ``CodeIgniter\Model``. Ils disposent nativement des méthodes :
   ``find()``, ``findAll()``, ``save()``, ``delete()``, ``first()``.

.. php:namespace:: App\Models

********************
Utilisateurs & Rôles
********************

UserModel
=========

.. php:class:: UserModel

    Gère la table ``users`` (comptes de connexion).

    .. php:method:: checkConnection($email, $password)

        Vérifie les identifiants (email et mot de passe haché).

    .. php:method:: getByEmail($email)

        Récupère un utilisateur via son email.

    .. php:method:: getUsersByRole($role, $perPage = 20)

        Récupère une liste paginée d'utilisateurs selon leur rôle.

    .. php:method:: getAdminAllUsersPaginated($perPage = 10, $role = null)

        Récupère tous les utilisateurs avec leurs statuts (vendeurs inclus) pour l'admin.

    .. php:method:: countAllUsers()

        Compte le nombre total d'utilisateurs inscrits.

    .. php:method:: getAdminLatestRegistered($limit = 5)

        Récupère les derniers utilisateurs inscrits.

CustomerModel
=============

.. php:class:: CustomerModel

    Extension du profil pour les clients (table ``customers``).

    .. php:method:: getByEmail($email)

        Récupère le profil complet du client via l'email utilisateur.

    .. php:method:: getFullProfile($id)

        Récupère les infos utilisateur jointes aux infos client.

    .. php:method:: getLatestRegistered($limit = 5)

        Retourne les derniers clients inscrits pour le dashboard.

    .. php:method:: createCustomer($userData, $customerData = [])

        Crée un nouveau client et son compte utilisateur associé dans une transaction.

SellerModel
===========

.. php:class:: SellerModel

    Gère les vendeurs et leurs boutiques (table ``sellers``).

    .. php:method:: getByEmail($email)

        Récupère le profil complet du vendeur via l'email.

    .. php:method:: getFullProfile($userId)

        Récupère le profil complet du vendeur via son ID.

    .. php:method:: countSellersPendingValidation()

        Compte le nombre de vendeurs en attente de validation.

    .. php:method:: getSellersPendingValidation($perPage = 20)

        Récupère la liste des vendeurs en attente de validation.

    .. php:method:: validateSeller($sellerId)

        Valide un compte vendeur (statut ``SELLER_VALIDATED``).

    .. php:method:: rejectSeller($sellerId, $reason)

        Refuse un vendeur avec un motif (statut ``SELLER_REFUSED``).

    .. php:method:: createSeller($userData, $sellerData = null)

        Crée un compte vendeur (et utilisateur) avec gestion des erreurs.

AdministratorModel
==================

.. php:class:: AdministratorModel

    Gère les administrateurs.

    .. php:method:: getByEmail($email)

        Récupère le profil complet d'un admin via l'email.

    .. php:method:: getAdminProfile($id)

        Récupère le profil complet d'un admin via son ID.

AddressModel
============

.. php:class:: AddressModel

    Gère les adresses de livraison.

    .. php:method:: getUserAddresses($userId)

        Récupère toutes les adresses d'un utilisateur.

    .. php:method:: deleteAddress($addressId, $userId)

        Supprime une adresse spécifique.

********************
Catalogue & Produits
********************

ProductModel
============

.. php:class:: ProductModel

    Gère le catalogue produit.

    .. php:method:: hasSufficientStock($productId, $quantity)

        Vérifie si la quantité demandée est disponible.

    .. php:method:: getPendingProductsPaginated($perPage = 5)

        Récupère les produits en attente de validation (pour admin).

    .. php:method:: getAllProductsPaginated($perPage = 10)

        Récupère tous les produits (admin).

    .. php:method:: countPendingProducts()

        Compte les produits en attente.

    .. php:method:: validateProduct($id)

        Approuve un produit.

    .. php:method:: rejectProduct($id, $reason)

        Refuse un produit avec motif.

    .. php:method:: getByAlias($alias)

        Récupère un produit via son slug URL.

    .. php:method:: getByCategory($categoryId, $sort = 'recent', $perPage = 12)

        Liste les produits d'une catégorie.

    .. php:method:: search($term, $perPage = 12)

        Recherche par titre ou description.

    .. php:method:: getSellerProducts($sellerId, $perPage = 10, $search = null, $status = null)

        Récupère les produits d'un vendeur (filtres possibles).

    .. php:method:: decrementStock($productId, $quantity)

        Décrémente le stock.

    .. php:method:: incrementStock($productId, $quantity)

        Incrémente le stock.

    .. php:method:: checkSufficientStock($productId, $requestedQuantity)

        Vérifie la disponibilité du stock (alias).

    .. php:method:: getProductsPendingValidation()

        Liste simple des produits en attente.

    .. php:method:: getAllWithImage($limit = 6)

        Récupère les produits avec leur image principale.

    .. php:method:: getAllWithSeller($perPage = 15)

        Récupère les produits avec le nom de la boutique.

    .. php:method:: countSellerLowStock($sellerId, $threshold)

        Compte les produits en stock critique pour un vendeur.

    .. php:method:: countProductsPendingValidation()

        Compte global des produits à valider.

    .. php:method:: countSellerPendingProducts($sellerId)

        Compte les produits à valider pour un vendeur spécifique.

    .. php:method:: getDimensionBounds()

        Calcule les min/max dimensions du catalogue.

    .. php:method:: filterProducts($filters, $perPage = 12)

        Moteur de filtre avancé (prix, taille, matière, vendeur...).

    .. php:method:: getUniqueMaterials()

        Liste les matières existantes.

    .. php:method:: getActiveSellers()

        Liste les vendeurs ayant des produits actifs.

    .. php:method:: getSimilarProducts($categoryId, $excludeId, $limit = 4)

        Suggère des produits similaires.

    .. php:method:: countSellerProducts($sellerId)

        Compte tous les produits d'un vendeur.

CategoryModel
=============

.. php:class:: CategoryModel

    .. php:method:: getAllCategoriesPaginated($perPage = 10)

        Liste toutes les catégories triées.

ProductPhotoModel
=================

.. php:class:: ProductPhotoModel

    Gère la galerie d'images.

    .. php:method:: getUploadRules()

        Retourne les règles de validation pour l'upload d'images.

    .. php:method:: getGallery($productId)

        Récupère toutes les photos d'un produit.

    .. php:method:: getMainImage($productId)

        Récupère la photo de couverture.

    .. php:method:: setMain($photoId, $productId)

        Définit l'image principale.

    .. php:method:: deleteAll($productId)

        Supprime toutes les photos d'un produit.

    .. php:method:: getNextDisplayOrder($productId)

        Calcule la position de la prochaine photo.

    .. php:method:: getPhotosByProduct($productId)

        Alias de getGallery.

ReviewModel
===========

.. php:class:: ReviewModel

    Système d'avis.

    .. php:method:: getSellerGlobalStats($sellerId)

        Stats globales (moyenne, total) pour un vendeur.

    .. php:method:: getSellerReviews($sellerId, $perPage = 10, $sort = 'date_desc')

        Récupère les avis d'un vendeur (triable).

    .. php:method:: getReviewsForProduct($productId)

        Avis publiés d'un produit.

    .. php:method:: getSellerAverageRating($sellerId)

        Moyenne des notes d'un vendeur.

    .. php:method:: getProductStats($productId)

        Moyenne des notes d'un produit.

    .. php:method:: countPublishedReviewsForUser($userId)

        Nombre d'avis publiés par un utilisateur.

    .. php:method:: hasAlreadyRated($productId, $customerId)

        Vérifie si le client a déjà noté ce produit.

    .. php:method:: moderateReview($reviewId, $status)

        Change le statut de modération.

    .. php:method:: hasBoughtAndReceived($customerId, $productId)

        Vérifie l'achat et la livraison.

    .. php:method:: countCriticalReviews()

        Compte les avis négatifs (<= 2 étoiles).

    .. php:method:: getAllReviews($perPage = 10)

        Tous les avis (admin).

    .. php:method:: getCriticalReviews($perPage = 10)

        Avis critiques (admin).

    .. php:method:: getRejectedReviews($perPage = 10)

        Avis refusés (admin).

    .. php:method:: getPublishedReviews($perPage = 10)

        Avis publiés (admin).

    .. php:method:: getReviewsByFilter($filter, $perPage = 10)

        Filtre dynamique des avis.

    .. php:method:: getPaginatedReviewsForUser($userId, $perPage = 8)

        Mes avis (côté client).

    .. php:method:: getReviewForProductByUser($userId, $productId)

        Récupère l'avis spécifique d'un utilisateur sur un produit.

********************
Commandes & Panier
********************

CartModel
=========

.. php:class:: CartModel

    .. php:method:: getActiveCart($customerId)

        Récupère ou crée le panier actif.

    .. php:method:: getCartItems($cartId)

        Récupère les items du panier avec détails produits.

    .. php:method:: updateTotal($cartId)

        Recalcule le total.

    .. php:method:: emptyCart($cartId)

        Vide le panier.

    .. php:method:: deleteOldCarts($days = 30)

        Nettoyage des vieux paniers abandonnés.

CartItemModel
=============

.. php:class:: CartItemModel

    .. php:method:: addItem($cartId, $productId, $quantity)

        Ajoute ou met à jour un item.

    .. php:method:: updateQuantity($cartId, $productId, $newQuantity)

        Modifie la quantité.

    .. php:method:: removeItem($cartId, $productId)

        Supprime un item.

    .. php:method:: getTotalItemsCount($cartId)

        Compte le nombre total d'articles dans le panier.

OrderModel
==========

.. php:class:: OrderModel

    .. php:method:: getUserOrders($userId, $perPage = 10)

        Commandes d'un utilisateur.

    .. php:method:: countUserOrders($userId)

        Compte les commandes d'un utilisateur.

    .. php:method:: createOrderFromCart($customerId, $orderData, $cart, $items)

        Transforme panier en commande (complexe : stock, shipping, transaction).

    .. php:method:: getAllOrdersWithClient($perPage = 15, $status = null)

        Toutes les commandes (admin).

    .. php:method:: getGlobalTotalAmount()

        Montant total des commandes.

    .. php:method:: getOrderStatuses()

        Retourne la liste des statuts possibles.

    .. php:method:: getCustomerHistory($customerId)

        Historique complet d'un client.

    .. php:method:: getByReference($reference)

        Recherche par référence (CMD-XXX).

    .. php:method:: getOrderWithIdentity($orderId)

        Commande avec détails client complets.

    .. php:method:: getPendingOrders()

        Compte les commandes en attente.

    .. php:method:: countValidOrders()

        Compte les commandes valides (non annulées).

    .. php:method:: getTotalSales()

        Total des ventes (chiffre d'affaires plateforme).

    .. php:method:: getRecentOrders($limit = 5)

        Dernières commandes passées.

    .. php:method:: getPaginatedOrdersForClient($clientId, $perPage = 10)

        Commandes paginées pour un client.

    .. php:method:: getItemCount($orderId)

        Nombre d'articles dans une commande.

OrderItemModel
==============

.. php:class:: OrderItemModel

    .. php:method:: getSellerOrders($sellerId, $perPage = 5, $status = null)

        Commandes contenant des produits d'un vendeur.

    .. php:method:: getItemsForOrders($sellerId, $orderIds)

        Détails des articles pour une liste de commandes.

    .. php:method:: getSellerSales($sellerId, $perPage = 10)

        Liste détaillée des ventes vendeur.

    .. php:method:: countSellerSales($sellerId)

        Nombre de ventes vendeur.

    .. php:method:: getSellerTurnover($sellerId)

        Chiffre d'affaires vendeur.

    .. php:method:: getSellerTotalOrders($sellerId)

        Nombre de commandes concernées vendeur.

    .. php:method:: getSellerBestSellers($sellerId, $limit = 3)

        Meilleures ventes vendeur.

    .. php:method:: getPaginatedOrderItems($orderId, $perPage = 10)

        Détails commande paginés.

    .. php:method:: hasUserPurchasedProduct($userId, $productId)

        Vérifie achat pour autorisation avis.

    .. php:method:: countOrdersByStatus($sellerId)

        Stats des commandes par statut pour un vendeur.