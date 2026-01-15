##################
Entités (Entities)
##################

Les entités encapsulent les données d'une ligne de table et fournissent des méthodes utilitaires pour le formatage et l'affichage.

.. php:class:: App\Entities\User

   .. php:method:: setMotDePasse(string $motDePasse)

      Hache le mot de passe avant l'enregistrement en base.
   
   .. php:method:: getIdentite()

      Retourne le ``Prénom NOM`` formaté proprement.

   .. php:method:: estAdmin()

      :returns: true si le rôle est ADMIN.

   .. php:method:: estVendeur()

      :returns: true si le rôle est SELLER.

.. php:class:: App\Entities\Seller

   .. php:method:: estActif()

      :returns: true si le statut est VALIDATED.

   .. php:method:: getSiretFormate()

      Formate l'affichage du numéro SIRET (ex: avec des espaces).

.. php:class:: App\Entities\Product

   .. php:method:: estDisponible()

      Vérifie si le produit est achetable (Statut APPROVED + Stock > 0).

   .. php:method:: aBesoinDeStock()

      Indique si le stock est critique (<= 3 unités).

   .. php:method:: getPrixFormate()

      Retourne le prix avec le sigle Euro (ex: ``125,00 €``).

   .. php:method:: getImage()

      Retourne l'URL absolue de l'image principale, ou une image par défaut.

.. php:class:: App\Entities\Review

   .. php:method:: getEtoiles()

      Génère le code HTML (SVG/Icones) représentant la note sur 5.

   .. php:method:: getDateRelative()

      Affiche la date au format relatif (ex: "il y a 2 jours").

.. php:class:: App\Entities\Cart

   .. php:method:: getTotalFormate()

      Affiche le montant total du panier formaté en euros.

   .. php:method:: estVide()

      :returns: true si le panier ne contient aucun article.

.. php:class:: App\Entities\Order

   .. php:method:: estTerminee()

      Vérifie si la commande est clôturée (LIVREE ou ANNULEE).

   .. php:method:: getCouleurStatut()

      Retourne une classe CSS (Tailwind) correspondant à l'état (ex: ``bg-green-100`` pour payé).