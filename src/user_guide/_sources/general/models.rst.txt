################
Modèles (Models)
################

Les modèles gèrent les interactions avec la base de données. Ils sont situés dans ``app/Models``.

.. note::
   Tous les modèles héritent de ``CodeIgniter\Model``. Ils disposent nativement des méthodes :
   ``find()``, ``findAll()``, ``save()``, ``delete()``, ``first()``.

.. php:namespace:: App\Models

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

CustomerModel
=============

.. php:class:: CustomerModel

    Extension du profil pour les clients (table ``customers``).

    .. php:method:: getFullProfile($id)

        Récupère les infos utilisateur jointes aux infos client.

Catalogue & Produits
********************

ProductModel
============

.. php:class:: ProductModel

    .. php:method:: getByAlias($alias)

        Récupère la fiche détaillée d'un produit via son slug URL.

    .. php:method:: filterProducts($filters, $perPage)

        Moteur de recherche avancé.
