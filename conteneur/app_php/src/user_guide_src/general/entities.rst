##################
Entités (Entities)
##################

Les entités encapsulent les données d'une ligne de table.

.. php:namespace:: App\Entities

Utilisateurs & Rôles
********************

User
====

.. php:class:: User

    .. php:method:: getIdentite()

        Retourne le ``Prénom NOM`` formaté proprement.

    .. php:method:: estAdmin()

        Renvoie true si le rôle est ADMIN.

Catalogue & Produits
********************

Product
=======

.. php:class:: Product

    .. php:method:: getPrixFormate()

        Retourne le prix avec le sigle Euro (ex: ``125,00 €``).

    .. php:method:: getImage()

        Retourne l'URL absolue de l'image principale.
