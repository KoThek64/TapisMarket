#################
Traits
#################

Les traits sont des morceaux de code réutilisables inclus dans plusieurs Entités ou Modèles pour éviter la duplication de code. Ils se trouvent dans ``app/Traits``.

.. php:namespace:: App\Traits

DateTrait
=========
.. php:trait:: DateTrait

    Gère le formatage des dates.

    .. php:method:: formatDate($date, $withTime = true)

        Formate une date en format français (ex: ``12/05/2024 à 14:30``).

        :param mixed $date: L'objet Time ou string date.
        :param bool $withTime: Si true, ajoute l'heure.
        :returns: La date formatée.

    .. php:method:: formaterDateRelative($date)

        Retourne une date au format relatif "humain" (ex: ``il y a 2 heures``).

ImageTrait
==========
.. php:trait:: ImageTrait

    Gère la construction des URLs d'images.

    .. php:method:: getImageUrl($filename, $placeholderUrl = null)

        Génère l'URL absolue vers l'image du produit stockée dans ``uploads/``. Si le fichier est vide ou "default.jpg", retourne l'image par défaut.

    .. php:method:: getUrlImage($filename, $placeholderUrl = null)

        Alias de ``getImageUrl`` pour correspondre aux appels des Entités.

PriceTrait
==========
.. php:trait:: PriceTrait

    Gère l'affichage monétaire.

    .. php:method:: formatPrice($amount)

        Formate un montant float en chaîne monétaire française (ex: ``1 250,50 €``).

RatingTrait
===========
.. php:trait:: RatingTrait

    Gère l'affichage des notes (étoiles).

    .. php:method:: generateRatingHtml($rating)

        Génère le code HTML complet (avec styles Tailwind) pour afficher des étoiles (0 à 5) basées sur la note.

    .. php:method:: generateStarHtml($rating)

        Alias de ``generateRatingHtml`` utilisé par l'entité Review.