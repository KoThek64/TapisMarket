##################
Modèles (Models)
##################

Les modèles sont responsables des interactions avec la base de données. Ils sont situés dans ``app/Models``.

**************************
Utilisateurs & Rôles
**************************

.. php:class:: App\Models\UserModel

   Gère la table ``users`` (table mère de tous les comptes).

   .. php:method:: verifierConnexion(string $email, string $motDePasse)

      Vérifie les identifiants de l'utilisateur (email et hachage du mot de passe).
      :returns: L'entité User ou false.

   .. php:method:: getParEmail(string $email)

      Récupère un utilisateur par son email.

   .. php:method:: getUtilisateursParRole(string $role, int $perPage)

      Récupère une liste paginée d'utilisateurs filtrée par rôle (``ADMIN``, ``SELLER``, ``CUSTOMER``).

.. php:class:: App\Models\AdministratorModel

   .. php:method:: getProfilAdmin(int $id)

      Récupère les informations complètes d'un administrateur grâce à la jointure avec la table utilisateur.

.. php:class:: App\Models\CustomerModel

   .. php:method:: getProfilComplet(int $id)

      Récupère les informations complètes d'un client (téléphone, date de naissance) en joignant la table utilisateur.

   .. php:method:: getDerniersInscrits(int $limit)

      Récupère la liste des derniers clients inscrits pour le tableau de bord administrateur.

.. php:class:: App\Models\SellerModel

   .. php:method:: getProfilComplet(int $id)

      Récupère les informations complètes d'un vendeur (boutique, SIRET, description et données utilisateur).

   .. php:method:: getVendeursEnAttente(int $perPage)

      Récupère la liste paginée des vendeurs dont le statut est ``PENDING_VALIDATION``.

   .. php:method:: validerVendeur(int $id)

      Met à jour le statut du vendeur à ``VALIDATED``.

   .. php:method:: refuserVendeur(int $id, string $motif)

      Met à jour le statut du vendeur à ``REFUSED`` et enregistre le motif du refus.

.. php:class:: App\Models\AddressModel

   .. php:method:: getAdressesUtilisateur(int $idUtilisateur)

      Retourne la liste des adresses (livraison/facturation) associées à un utilisateur spécifique.

**************************
Catalogue & Produits
**************************

.. php:class:: App\Models\CategoryModel

   .. php:method:: getCategoriesMenu()

      Retourne la liste des catégories triées par nom pour l'affichage dans le menu principal.

.. php:class:: App\Models\ProductModel

   .. php:method:: getParAlias(string $alias)

      Récupère la fiche détaillée d'un produit (avec infos vendeur et catégorie) via son alias URL (slug).

   .. php:method:: getParCategorie(int $id, string $tri, int $perPage)

      Récupère les produits validés d'une catégorie avec pagination.
      
      :param string $tri: Mode de tri ('price_asc', 'price_desc', 'recent').

   .. php:method:: rechercher(string $terme, int $perPage)

      Effectue une recherche textuelle (`LIKE`) sur le titre et la description des produits validés.

   .. php:method:: getProduitsVendeur(int $idVendeur)

      Liste tous les produits appartenant à un vendeur spécifique (utilisé dans le Back-office vendeur).

   .. php:method:: incrementerStock(int $id, int $qte)

      Ajoute une quantité au stock disponible d'un produit.

   .. php:method:: decrementerStock(int $id, int $qte)

      Soustrait une quantité au stock disponible d'un produit (appelé lors de la validation d'une commande).

   .. php:method:: getProduitsEnAttente()

      Récupère la liste des produits nécessitant une validation par l'administrateur (Statut ``PENDING``).

.. php:class:: App\Models\ProductPhotoModel

   .. php:method:: getGalerie(int $idProduit)

      Retourne l'ensemble des photos associées à un produit, triées par ordre d'affichage.

   .. php:method:: getImagePrincipale(int $idProduit)

      Retourne uniquement la photo définie comme couverture (ordre d'affichage 1).

   .. php:method:: definirPrincipale(int $idPhoto, int $idProduit)

      Définit une photo comme principale (ordre 1) et décale automatiquement l'ordre des autres photos.

   .. php:method:: supprimerTout(int $idProduit)

      Supprime toutes les photos liées à un produit (nettoyage).

.. php:class:: App\Models\ReviewModel

   .. php:method:: getAvisPourProduit(int $idProduit)

      Récupère les avis **validés** d'un produit avec le nom de l'auteur pour l'affichage public.

   .. php:method:: getStatsProduit(int $idProduit)

      Calcule la note moyenne et le nombre total d'avis validés pour un produit.

   .. php:method:: aDejaNote(int $idProduit, int $idClient)

      Vérifie si un client a déjà déposé un avis sur un produit donné (pour empêcher les doublons).

**************************
Panier & Commandes
**************************

.. php:class:: App\Models\CartModel

   .. php:method:: getPanierActif(int $idClient)

      Récupère le panier en cours d'un client ou en crée un nouveau s'il n'existe pas.

   .. php:method:: getArticlesPanier(int $idPanier)

      Récupère le contenu détaillé du panier (produits, prix unitaires, images principales).

   .. php:method:: mettreAJourTotal(int $idPanier)

      Recalcule la somme des lignes et met à jour le champ ``total`` du panier en base de données.

   .. php:method:: viderPanier(int $idPanier)

      Supprime le contenu du panier (lignes) et remet le total à zéro (sans supprimer le panier lui-même).

.. php:class:: App\Models\CartItemModel

   .. php:method:: ajouterArticle(int $idPanier, int $idProduit, int $quantite)

      Ajoute un article au panier. Si l'article existe déjà, incrémente simplement la quantité.

   .. php:method:: modifierQuantite(int $idPanier, int $idProduit, int $nouvelleQuantite)

      Met à jour la quantité d'un article. Si la quantité tombe à 0, l'article est supprimé.

   .. php:method:: getNombreArticlesTotal(int $idPanier)

      Compte le nombre total d'articles (somme des quantités) pour l'affichage du badge panier dans le header.

.. php:class:: App\Models\OrderModel

   .. php:method:: getHistoriqueClient(int $idClient)

      Liste l'historique des commandes passées par un client, triées par date décroissante.

   .. php:method:: getParReference(string $reference)

      Recherche une commande par sa référence unique (ex: ``CMD-A1B2...``).

   .. php:method:: getCommandesEnCours()

      Récupère les commandes non finalisées (non livrées/annulées) pour le suivi logistique.

   .. php:method:: getTotalVentes()

      Calcule le chiffre d'affaires total de toutes les commandes validées (Dashboard Admin).

.. php:class:: App\Models\OrderItemModel

   .. php:method:: getVentesVendeur(int $idVendeur)

      Récupère les lignes de commandes concernant spécifiquement les produits d'un vendeur (pour son CA personnel).

   .. php:method:: getBestSellersVendeur(int $idVendeur)

      Identifie les produits les plus vendus d'un vendeur (Top des ventes).