##################
Entités (Entities)
##################

Les entités représentent les objets manipulés par l'application (une ligne de base de données = un objet). Elles contiennent la logique de formatage et les petits calculs d'affichage.

.. php:namespace:: App\Entities

********************
Utilisateurs & Rôles
********************

User
====

.. php:class:: User

    L'entité centrale gérant les comptes de connexion.

    .. php:method:: getIdentity()

        Retourne l'identité formatée (Ex: ``Prénom NOM``).

    .. php:method:: isAdmin()

        Vérifie si l'utilisateur a le rôle ``ADMIN``.

    .. php:method:: isSeller()

        Vérifie si l'utilisateur a le rôle ``SELLER``.

    .. php:method:: setPassword($password)

        Hache automatiquement le mot de passe avant l'insertion en base.

    .. php:method:: getFormattedRegistrationDate()

        Retourne la date d'inscription au format lisible (d/m/Y).

Seller
======

.. php:class:: Seller

    Extension du profil utilisateur pour les vendeurs.

    .. php:method:: isActive()

        Retourne ``true`` si le vendeur est validé (``SELLER_VALIDATED``).

    .. php:method:: isPending()

        Retourne ``true`` si le vendeur est en attente de validation.

    .. php:method:: getFormattedSiret()

        Formate le SIRET avec des espaces (ex: ``123 456 789 00012``).

Customer
========

.. php:class:: Customer

    Extension du profil utilisateur pour les clients.

    .. php:method:: getFormattedPhone()

        Formate le numéro de téléphone par paires de chiffres.

    .. php:method:: getIdentity()

        Retourne le nom complet ou "Client #ID" si non renseigné.

Address
=======

.. php:class:: Address

    .. php:method:: getFullAddress()

        Concatène les champs pour retourner une adresse postale complète en une chaîne.

********************
Catalogue & Produits
********************

Product
=======

.. php:class:: Product

    .. php:method:: isAvailable()

        Vérifie si le produit est approuvé ET s'il y a du stock (> 0).

    .. php:method:: needsStock()

        Retourne ``true`` si le stock est critique (<= 3).

    .. php:method:: getFormattedPrice()

        Retourne le prix formaté avec la devise (ex: ``125,00 €``).

    .. php:method:: getLink()

        Génère l'URL canonique du produit (basée sur l'alias ou l'ID).

    .. php:method:: getImage()

        Retourne l'URL de l'image principale ou une image par défaut.

    .. php:method:: getStars()

        Génère le code HTML des étoiles de notation (basé sur la note moyenne).

Category
========

.. php:class:: Category

    .. php:method:: getLink()

        Génère le lien vers la page de la catégorie.

    .. php:method:: getImage()

        Retourne l'image de la catégorie.

Review
======

.. php:class:: Review

    .. php:method:: getStars()

        Génère le code HTML des étoiles pour cet avis spécifique.

    .. php:method:: getCommentExcerpt($length = 50)

        Coupe le commentaire pour l'affichage en liste (avec "...").

    .. php:method:: getRelativeDate()

        Retourne la date au format relatif (ex: "il y a 2 jours").

    .. php:method:: isPublished()

        Vérifie si l'avis a été modéré et publié.

ProductPhoto
============

.. php:class:: ProductPhoto

    .. php:method:: getSrc()

        Retourne l'URL complète du fichier image.

    .. php:method:: isMain()

        Indique si c'est la photo de couverture (ordre d'affichage = 1).

********************
Commandes & Panier
********************

Cart
====

.. php:class:: Cart

    Représente le panier actif.

    .. php:method:: getFormattedTotal()

        Le total du panier formaté en devise.

    .. php:method:: isEmpty()

        Vérifie si le panier est vide (total = 0).

    .. php:method:: isAbandoned($hours = 24)

        Vérifie si le panier n'a pas été modifié depuis un certain temps.

CartItem
========

.. php:class:: CartItem

    Une ligne dans le panier.

    .. php:method:: getSubtotal()

        Calcule le prix total de la ligne (Prix unitaire * Quantité).

    .. php:method:: getFormattedSubtotal()

        Le sous-total formaté.

    .. php:method:: getProductLink()

        Lien vers la fiche du produit concerné.

Order
=====

.. php:class:: Order

    Une commande validée.

    .. php:method:: getFormattedPrice()

        Le montant total TTC formaté.

    .. php:method:: isCompleted()

        Vérifie si la commande est terminée (Livrée ou Annulée).

    .. php:method:: getFullDeliveryAddress()

        Retourne l'adresse de livraison formatée en une ligne.

    .. php:method:: getFormattedDate()

        Date de la commande formatée.

OrderItem
=========

.. php:class:: OrderItem

    Détail d'un produit dans une commande.

    .. php:method:: getTotal()

        Calcul : Prix unitaire à l'achat * Quantité.

    .. php:method:: getFormattedTotal()

        Total de la ligne formaté.

    .. php:method:: getImage()

        Récupère l'image du produit (historique ou actuelle).