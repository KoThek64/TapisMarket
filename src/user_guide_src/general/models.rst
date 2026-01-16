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

        Récupère une liste paginée d'utilisateurs selon leur rôle (ADMIN, SELLER, CUSTOMER).

    .. php:method:: getAdminAllUsersPaginated($perPage = 10, $role = null)

        Récupère tous les utilisateurs avec leurs statuts (vendeurs inclus) pour le dashboard admin.

    .. php:method:: getAdminLatestRegistered($limit = 5)

        Récupère les derniers utilisateurs inscrits.

CustomerModel
=============

.. php:class:: CustomerModel

    Extension du profil pour les clients (table ``customers``).

    .. php:method:: getFullProfile($id)

        Récupère les infos utilisateur jointes aux infos client.

    .. php:method:: getByEmail($email)

        Récupère le profil complet via l'email.

    .. php:method:: createCustomer($userData, $customerData = [])

        Crée un nouveau client et son compte utilisateur associé dans une transaction.

    .. php:method:: getLatestRegistered($limit = 5)

        Retourne les derniers clients inscrits pour le dashboard.

SellerModel
===========

.. php:class:: SellerModel

    Gère les vendeurs et leurs boutiques (table ``sellers``).

    .. php:method:: getFullProfile($userId)

        Récupère le profil complet du vendeur.

    .. php:method:: createSeller($userData, $sellerData = null)

        Crée un compte vendeur (et utilisateur) avec gestion des erreurs et transaction.

    .. php:method:: getSellersPendingValidation($perPage = 20)

        Récupère la liste des vendeurs en attente de validation par l'admin.

    .. php:method:: validateSeller($sellerId)

        Valide un compte vendeur (passe le statut à ``SELLER_VALIDATED``).

    .. php:method:: rejectSeller($sellerId, $reason)

        Refuse un vendeur avec un motif (passe le statut à ``SELLER_REFUSED``).

AdministratorModel
==================

.. php:class:: AdministratorModel

    Gère les administrateurs.

    .. php:method:: getAdminProfile($id)

        Récupère le profil complet d'un admin.

AddressModel
============

.. php:class:: AddressModel

    Gère les adresses de livraison des utilisateurs.

    .. php:method:: getUserAddresses($userId)

        Récupère toutes les adresses d'un utilisateur.

    .. php:method:: deleteAddress($addressId, $userId)

        Supprime une adresse spécifique d'un utilisateur.

********************
Catalogue & Produits
********************

ProductModel
============

.. php:class:: ProductModel

    Gère le catalogue produit.

    .. php:method:: getByAlias($alias)

        Récupère la fiche détaillée d'un produit via son slug URL.

    .. php:method:: filterProducts($filters, $perPage)

        Moteur de recherche avancé (filtres par prix, dimensions, matières, catégories, vendeur).

    .. php:method:: getSellerProducts($sellerId, $perPage = 10, $search = null, $status = null)

        Récupère les produits d'un vendeur spécifique avec filtres optionnels.

    .. php:method:: hasSufficientStock($productId, $quantity)

        Vérifie si la quantité demandée est disponible en stock.

    .. php:method:: decrementStock($productId, $quantity)

        Réduit le stock d'un produit après une commande.

    .. php:method:: getPendingProductsPaginated($perPage = 5)

        Récupère les produits en attente de validation pour l'admin.

    .. php:method:: validateProduct($id)

        Approuve un produit pour la mise en vente.

    .. php:method:: rejectProduct($id, $reason)

        Refuse un produit avec un motif explicatif.

    .. php:method:: getSimilarProducts($categoryId, $excludeId, $limit = 4)

        Suggère des produits similaires de la même catégorie.

    .. php:method:: getDimensionBounds()

        Calcule les dimensions min/max du catalogue pour les filtres.

CategoryModel
=============

.. php:class:: CategoryModel

    .. php:method:: getAllCategoriesPaginated($perPage = 10)

        Liste toutes les catégories par ordre alphabétique.

ProductPhotoModel
=================

.. php:class:: ProductPhotoModel

    Gère la galerie d'images des produits.

    .. php:method:: getGallery($productId)

        Récupère toutes les photos d'un produit.

    .. php:method:: getMainImage($productId)

        Récupère l'image de couverture (display_order = 1).

    .. php:method:: setMain($photoId, $productId)

        Définit une nouvelle image comme photo principale.

ReviewModel
===========

.. php:class:: ReviewModel

    Système d'avis et de notation.

    .. php:method:: getReviewsForProduct($productId)

        Récupère les avis publiés pour la fiche produit.

    .. php:method:: getSellerGlobalStats($sellerId)

        Calcule le nombre d'avis et la note moyenne d'un vendeur.

    .. php:method:: hasBoughtAndReceived($customerId, $productId)

        Vérifie si un client a acheté et reçu un produit (condition pour poster un avis).

    .. php:method:: getCriticalReviews($perPage = 10)

        Récupère les avis avec une note inférieure ou égale à 2 (pour modération).

********************
Commandes & Panier
********************

CartModel
=========

.. php:class:: CartModel

    Gère le panier global (entête).

    .. php:method:: getActiveCart($customerId)

        Récupère ou crée le panier actif d'un client.

    .. php:method:: getCartItems($cartId)

        Récupère le contenu détaillé du panier (avec infos produits).

    .. php:method:: updateTotal($cartId)

        Recalcule le montant total du panier.

    .. php:method:: emptyCart($cartId)

        Vide le contenu du panier.

CartItemModel
=============

.. php:class:: CartItemModel

    Gère les lignes individuelles du panier.

    .. php:method:: addItem($cartId, $productId, $quantity)

        Ajoute un produit ou augmente sa quantité s'il existe déjà.

    .. php:method:: updateQuantity($cartId, $productId, $newQuantity)

        Met à jour la quantité d'un produit (supprime si <= 0).

OrderModel
==========

.. php:class:: OrderModel

    Gère les commandes globales.

    .. php:method:: createOrderFromCart($customerId, $orderData, $cart, $items)

        Transforme un panier en commande, décrémente les stocks et calcule les frais de port.

    .. php:method:: getUserOrders($userId, $perPage = 10)

        Historique des commandes d'un client.

    .. php:method:: getByReference($reference)

        Trouve une commande via sa référence unique (ex: CMD-2024...).

    .. php:method:: getAllOrdersWithClient($perPage = 15, $status = null)

        Liste complète des commandes pour l'administration.

    .. php:method:: getTotalSales()

        Calcule le chiffre d'affaires total de la plateforme.

OrderItemModel
==============

.. php:class:: OrderItemModel

    Gère les lignes de commande (détails vendus par produit).

    .. php:method:: getSellerOrders($sellerId, $perPage = 5, $status = null)

        Récupère les commandes contenant des produits d'un vendeur spécifique.

    .. php:method:: getSellerTurnover($sellerId)

        Calcule le chiffre d'affaires d'un vendeur spécifique.

    .. php:method:: getSellerBestSellers($sellerId, $limit = 3)

        Récupère les produits les plus vendus d'un vendeur.

    .. php:method:: hasUserPurchasedProduct($userId, $productId)

        Vérifie si un utilisateur a déjà acheté ce produit (utile pour les droits d'avis).