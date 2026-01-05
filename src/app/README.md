## Modèles (App/Models)

Les modèles sont responsables des interactions avec la base de données.

### Gestion des Utilisateurs et Rôles

**UtilisateurModel**
* `verifierConnexion($email, $motDePasse)` : Vérifie les identifiants de l'utilisateur (email et hachage du mot de passe). Retourne l'objet utilisateur ou false.
* `getParEmail($email)` : Récupère un utilisateur par son email.
* `getUtilisateursParRole($role, $perPage)` : Récupère une liste paginée d'utilisateurs filtrée par rôle (ADMIN, VENDEUR, CLIENT).

**AdministrateurModel**
* `getProfilAdmin($id)` : Récupère les informations complètes d'un administrateur grace à la table utilisateur.

**ClientModel**
* `getProfilComplet($id)` : Récupère les informations complètes d'un client en joignant la table utilisateur.
* `getDerniersInscrits($limit)` : Récupère la liste des derniers clients inscrits pour le tableau de bord administrateur.

**VendeurModel**
* `getProfilComplet($id)` : Récupère les informations complètes d'un vendeur (boutique et données utilisateur).
* `getVendeursEnAttente($perPage)` : Récupère la liste paginée des vendeurs dont le statut est EN_ATTENTE_VALIDATION.
* `validerVendeur($id)` : Met à jour le statut du vendeur à VALIDE.
* `refuserVendeur($id, $motif)` : Met à jour le statut du vendeur à REFUSE et enregistre le motif du refus.

**AdresseModel**
* `getAdressesUtilisateur($idUtilisateur)` : Retourne la liste des adresses associées à un utilisateur spécifique.

### Catalogue et Produits

**CategorieModel**
* `getCategoriesMenu()` : Retourne la liste des catégories triées par nom pour l'affichage dans le menu.

**ProduitModel**
* `getParAlias($alias)` : Récupère la fiche détaillée d'un produit (avec infos vendeur et catégorie) via son alias URL.
* `getParCategorie($id, $tri, $perPage)` : Récupère les produits validés d'une catégorie avec pagination et gestion du tri (prix, récence).
* `rechercher($terme, $perPage)` : Effectue une recherche textuelle sur le titre et la description des produits.
* `getProduitsVendeur($idVendeur)` : Liste les produits appartenant à un vendeur spécifique (Back-office vendeur).
* `incrementerStock($id, $qte)` : Ajoute une quantité au stock disponible d'un produit.
* `decrementerStock($id, $qte)` : Soustrait une quantité au stock disponible d'un produit (lors d'une commande).
* `getProduitsEnAttente()` : Récupère la liste des produits nécessitant une validation par l'administrateur.

**PhotoProduitModel**
* `getGalerie($idProduit)` : Retourne l'ensemble des photos associées à un produit.
* `getImagePrincipale($idProduit)` : Retourne la photo définie comme couverture (ordre d'affichage 1).
* `definirPrincipale($idPhoto, $idProduit)` : Définit une photo comme principale et réorganise l'ordre des autres.
* `supprimerTout($idProduit)` : Supprime toutes les photos liées à un produit.

**AvisModel**
* `getAvisPourProduit($idProduit)` : Récupère les avis validés d'un produit avec le nom de l'auteur.
* `getStatsProduit($idProduit)` : Calcule la note moyenne et le nombre total d'avis validés pour un produit.
* `aDejaNote($idProduit, $idClient)` : Vérifie si un client a déjà déposé un avis sur un produit donné.

### Panier et Commandes

**PanierModel**
* `getPanierActif($idClient)` : Récupère le panier en cours d'un client ou en crée un nouveau s'il n'existe pas.
* `getArticlesPanier($idPanier)` : Récupère le contenu détaillé du panier (produits, prix, images).
* `mettreAJourTotal($idPanier)` : Recalcule et met à jour le montant total du panier en base de données.
* `viderPanier($idPanier)` : Supprime le contenu du panier et remet le total à zéro.

**LignePanierModel**
* `ajouterArticle($idPanier, $idProduit, $quantite)` : Ajoute un article au panier ou incrémente la quantité si déjà présent.
* `modifierQuantite($idPanier, $idProduit, $nouvelleQuantite)` : Met à jour la quantité d'un article (le supprime si quantité nulle).
* `supprimerArticle($idPanier, $idProduit)` : Retire un produit spécifique du panier.
* `getNombreArticlesTotal($idPanier)` : Compte le nombre total d'articles pour l'affichage du badge panier.

**CommandeModel**
* `getHistoriqueClient($idClient)` : Liste l'historique des commandes passées par un client.
* `getParReference($reference)` : Recherche une commande par sa référence unique.
* `getCommandeAvecIdentite($id)` : Récupère une commande avec les informations complètes du client associé.
* `getCommandesEnCours()` : Récupère les commandes non finalisées.
* `getTotalVentes()` : Calcule le chiffre d'affaires total des commandes validées (Admin).
* `getNombreCommandes()` : Compte le nombre total de commandes validées.
* `getCommandesRecente()` : Récupère les 5 dernières commandes.
* `getStatutCommandes()` : Retourne la liste des statuts possibles pour l'affichage.

**LigneCommandeModel**
* `getVentesVendeur($idVendeur)` : Récupère les lignes de commandes concernant les produits d'un vendeur.
* `countVentesVendeur($idVendeur)` : Compte le nombre total de ventes d'un vendeur.
* `getChiffreAffairesVendeur($idVendeur)` : Calcule le chiffre d'affaires total d'un vendeur.
* `getBestSellersVendeur($idVendeur)` : Identifie les produits les plus vendus d'un vendeur.
* `getLignesDuneCommande($idCommande)` : Récupère le détail des produits d'une commande spécifique.

---

## Entités (App/Entities)

Les entités encapsulent les données d'une ligne de table et fournissent des méthodes utilitaires pour le formatage et l'affichage.

**Utilisateur**
* `setMotDePasse($motDePasse)` : Hache le mot de passe avant enregistrement.
* `getIdentite()` : Retourne le Prénom et le NOM formatés.
* `estAdmin()` : Vérifie si l'utilisateur a le rôle ADMIN.
* `estVendeur()` : Vérifie si l'utilisateur a le rôle VENDEUR.
* `getDateInscription()` : Formate la date d'inscription.

**Client**
* `getTelephoneAffiche()` : Formate le numéro de téléphone avec des espaces.
* `getIdentite()` : Surcharge pour afficher l'identité du client.

**Vendeur**
* `estActif()` : Vérifie si le statut du vendeur est VALIDE.
* `estEnAttente()` : Vérifie si le statut est EN_ATTENTE_VALIDATION.
* `getSiretFormate()` : Formate l'affichage du numéro SIRET.

**Adresse**
* `getAdresseComplete()` : Concatène les champs d'adresse (numéro, rue, code postal, ville, pays).

**Categorie**
* `getImage()` : Retourne l'URL de l'image de la catégorie.
* `getLien()` : Génère l'URL vers la page de la catégorie.

**Produit**
* `estDisponible()` : Vérifie si le produit est approuvé et si le stock est positif.
* `aBesoinDeStock()` : Indique si le stock est faible (inférieur ou égal à 3).
* `getPrixFormate()` : Affiche le prix formaté avec la devise.
* `getLien()` : Génère l'URL vers la fiche produit.
* `getImage()` : Retourne l'URL de l'image principale.
* `getNoteMoyenne()` : Retourne la note moyenne arrondie.

**Avis**
* `getEtoiles()` : Génère le code HTML représentant la note sous forme d'étoiles.
* `getExtraitCommentaire()` : Diminue le commentaire s'il est trop long.
* `getDateRelative()` : Affiche la date au format relatif (ex: il y a 2 jours).
* `estPublie()` : Vérifie si l'avis est validé.

**PhotoProduit**
* `getSrc()` : Retourne le chemin complet de l'image.
* `estPrincipale()` : Vérifie si c'est l'image de couverture.

**Panier**
* `getTotalFormate()` : Affiche le montant total du panier formaté.
* `estVide()` : Vérifie si le panier ne contient aucun article.
* `estAbandonne($heures)` : Vérifie si le panier est inactif depuis un certain temps.

**LignePanier**
* `getSousTotal()` : Calcule le prix total de la ligne (Prix unitaire * Quantité).
* `getNomProduit()` : Retourne le nom du produit associé.
* `getLienProduit()` : Génère le lien vers le produit.

**Commande**
* `getTotalFormate()` : Affiche le total de la commande formaté.
* `estTerminee()` : Vérifie si la commande est livrée ou annulée.
* `getCouleurStatut()` : Retourne une classe CSS correspondant à l'état de la commande (pour l'interface).
* `getAdresseLivraisonComplete()` : Retourne l'adresse de livraison complète formatée.

**LigneCommande**
* `getTotal()` : Calcule le montant total de la ligne.
* `getTotalFormate()` : Affiche le montant total de la ligne formaté.