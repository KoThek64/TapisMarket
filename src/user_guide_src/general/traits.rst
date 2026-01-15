#################
Traits Communs
#################

Les traits sont des morceaux de code réutilisables inclus dans plusieurs Entités ou Modèles. Ils se trouvent dans ``app/Traits``.

.. php:namespace:: App\Traits

DateTrait
=========
.. php:trait:: DateTrait

    .. php:method:: formatDate($date, $withTime = true)

        Formate une date en format français.

        :returns: La date formatée.

ImageTrait
==========
.. php:trait:: ImageTrait

    .. php:method:: getImageUrl($filename, $placeholderUrl = null)

        Génère l'URL complète vers l'image du produit.

PriceTrait
==========
.. php:trait:: PriceTrait

    .. php:method:: formatPrice($amount)

        Formate un montant en euros.
