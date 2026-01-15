AIGNELOT Youenn  
BERNARD Adam  
FILMONT Felix  
LACHAISE Mattys  
PLU Niels

# Rapport R3.02 : Optimisation, Qualité et Dimensionnement - SAE 3.01

Ce rapport technique analyse la qualité logicielle et 
l'efficacité de notre Marketplace

---

## Table des matières

1. [Mise en place de méthodes pour montrer que l'application est correcte](#1-mise-en-place-de-méthodes-pour-montrer-que-lapplication-est-correcte)
2. [Montrer que l’application n’est pas coûteuse en temps d’exécution et en mémoire](#2-montrer-que-lapplication-nest-pas-coûteuse-en-temps-dexécution-et-en-mémoire)
3. [Conclusion](#3-conclusion)

---

## 1. Mise en place de méthodes pour montrer que l’application est correcte

Nous avons justifié la correction de l'application par des choix de conception stricts (Design by Contract) et des mécanismes de vérification embarqués.

### 1.1 Fonctionnalités attendues et Préconditions

Le tableau ci-dessous liste les fonctionnalités critiques, leurs
 conditions d'exécution (Préconditions) et l'état final du système (Post-conditions).

| Fonctionnalité | Pré-conditions (Conditions nécessaires) | Résultat attendu (Post-conditions) |
| :--- | :--- | :--- |
| **Achat Produit** | 1. Produit actif (`status=APPROVED`).<br>2. Stock >= Quantité demandée.<br>3. Paiement simulé validé. | - Stock décrémenté en base de données.<br>- Commande créée avec le prix figé.<br>- Panier vidé. |
| **Inscription Vendeur** | 1. Email unique.<br>2. Numéro SIRET unique et valide (format 13 chiffres).<br> 3. Mot de passe valide (format +8 cara)| - Compte créé en statut `PENDING`.<br>- Accès limité (pas de vente) avant validation Admin. |
| **Upload Photo** | 1. Fichier image valide/format (MIME).<br>2. Produit appartient au vendeur connecté. | - Image redimensionnée et stockée.<br>- Lien ajouté en base de données. |

Voici d'autres exemples de fonctionnalités ainsi que leurs Pré-conditions et Post-conditions :

### Gestion des utilisateurs

| Fonctionnalité | Pré-conditions | Résultat attendu |
|----------------|---------------|------------------|
| **Inscription Client** | Email valide et unique, mot de passe >= 8 caractères, nom/prénom >= 2 caractères | Compte créé avec rôle CUSTOMER |
| **Inscription Vendeur** | Mêmes conditions + SIRET valide (14 chiffres), nom de boutique | Compte créé avec statut PENDING_VALIDATION |
| **Connexion** | Email existant, mot de passe correct, rôle correspondant | Session créée avec user_id et role |
| **Déconnexion** | Utilisateur connecté | Session détruite |

**Validation dans le code** (UserModel.php) :  
```php
protected $validationRules = [
    'email'     => 'required|valid_email|is_unique[users.email]',
    'lastname'  => 'required|min_length[2]',
    'firstname' => 'required|min_length[2]',
    'password'  => 'required|min_length[8]',
    'role'      => 'in_list[ADMIN,SELLER,CUSTOMER]'
];
```

#### Gestion du panier

| Fonctionnalité | Préconditions | Résultat attendu |
|----------------|---------------|------------------|
| **Ajout au panier** | Produit existant et approuvé, stock suffisant | Article ajouté, total recalculé |
| **Modification quantité** | Article dans le panier, nouvelle quantité <= stock | Quantité mise à jour |
| **Suppression article** | Article présent dans le panier | Article retiré, total recalculé |
| **Fusion panier invité** | Utilisateur se connecte avec panier invité | Panier session fusionné avec panier BDD |

**Vérification de stock** (ProductModel.php) :  
```php
public function hasSufficientStock(int $productId, int $quantity): bool
{
    $product = $this->find($productId);
    return $product && $product->stock_available >= $quantity;
}
```

#### Gestion des commandes

| Fonctionnalité | Préconditions | Résultat attendu |
|----------------|---------------|------------------|
| **Création commande** | Panier non vide, utilisateur connecté, adresse valide | Commande créée, stock décrémenté, panier vidé |
| **Validation paiement** | Numéro carte 16 chiffres, date expiration MM/YY, CVC 3-4 chiffres | Commande passée au statut PAID |

**Transaction sécurisée** (OrderModel.php) :  
```php
public function createOrderFromCart(int $customerId, array $orderData, $cart, array $items)
{
    $this->db->transStart();
    // 1. Création de la commande
    // 2. Transfert des articles et mise à jour des stocks
    // 3. Vidage du panier
    $this->db->transComplete();
    return $this->db->transStatus() ? $orderId : false;
}
```

#### Gestion des produits

| Fonctionnalité | Préconditions | Résultat attendu |
|----------------|---------------|------------------|
| **Création produit** | Vendeur validé, titre >= 3 cara., prix > 0, stock >= 0 | Produit créé avec statut PENDING_VALIDATION |
| **Modification produit** | Produit appartient au vendeur | Si champs sensibles modifiés --> retour en PENDING |
| **Validation admin** | Produit en PENDING_VALIDATION | Statut --> APPROVED |

**Validation des produits** (ProductModel.php) :
```php
protected $validationRules = [
    'title'           => 'required|min_length[3]|max_length[150]',
    'price'           => 'required|decimal|greater_than[0]',
    'stock_available' => 'required|integer|greater_than_equal_to[0]',
    'category_id'     => 'required|integer',
];
```

### 1.2 Justification par les Assertions et Tests

Pour garantir la correction du logiciel, nous avons mis en place des mécanismes d'assertion (au sens large) garantissant le respect des contrats :  



* **Assertions Code :** 
Nous avons des assertions dans tous les modèles sous la forme suivante stocké 
à chaque fois dans `$validationsRules` (Validation des entrées (Pré-conditions)):

```php
protected $validationRules = [
        'product_id'        => 'required|integer',
        'customer_id'       => 'required|integer',
        'rating'            => 'required|integer|greater_than_equal_to[1]|less_than_equal_to[5]',
        'comment'           => 'required|min_length[5]|max_length[1000]',
        'moderation_status' => 'in_list[PUBLISHED,REFUSED]', 
    ];
```

* **Métier :** Nous avons utilisé des assertions à de nombreux endroits parfois 
déguisé en fonction comme par exemple la méthode `Product::isAvailable()`.
Elle agit comme une assertion métier centrale. Si elle retourne `false`, 
aucune vue d'achat n'est générée et le contrôleur `Cart::add` rejette la requête (Contrôles d'état (Guard Clauses)).

```php
public function isAvailable(): bool
    {
        return (
            $this->attributes['product_status'] === STATUS_APPROVED && 
            $this->attributes['stock_available'] > 0
        );
    }
```

* **Assertions SGBD (Intégrité des données (Invariants)) :**  
Exemple pour la table `PRODUCT` :
	*   Contraintes `PRIMARY KEY` garde l'unicité des données (1).
    *   Contraintes `FOREIGN KEY` bloquant les orphelins (3-4).
    *   Contraintes `UNIQUE` empêchant la duplication d'alias (2).

```php
1. $this->forge->addKey('id', true);
2. $this->forge->addUniqueKey('alias');
3. $this->forge->addForeignKey('seller_id', 'sellers', 'user_id', 'CASCADE', 'CASCADE');
4. $this->forge->addForeignKey('category_id', 'categories', 'id', 'RESTRICT', 'CASCADE');
```

* **AuthFilter** : Contrôle d'accès basé sur les rôles

```php
public function before(RequestInterface $request, $arguments = null)
{
    $role = user_role();
    if ($role === null) {
        return redirect()->to('/auth/login')->with('error', 'Vous devez être connecté.');
    }
    if (!empty($arguments) && !in_array($role->value, $arguments)) {
        return redirect()->to('/auth/login')->with('error', 'Accès non autorisé.');
    }
}
```

---

## 2. Montrer que l’application n’est pas coûteuse en temps d’exécution et en mémoire

### 2.1 Traitement optimal des données

*   **Normalisation 3NF :** 
    *   La base respecte la 3NF (Tables `addresses`, `sellers`, `customers` séparées).
    *   *Gain :* Réduction de la redondance des données, économie d'espace disque et de RAM lors des `SELECT`.
*   **Procédures Ad Hoc :**
    *   Nous avons développé une procédure spécifique de traitement d'image : `ProductImageManager`. Elle ne se contente pas de stocker, elle **transforme** (Resize 1024px + Compression). C'est un traitement préventif qui réduit le coût de lecture futur.
    *   Traitement par lot (Batching) utilisé dans le Seeder pour insérer 1000 lignes à la fois, évitant la saturation mémoire PHP.
*   **Choix du SGBD (MySQL 8 + InnoDB) :**
    *   Utilisation du verrouillage niveau ligne (*Row-Level Locking*) plutôt 
que niveau table. Cela permet à 100 clients d'acheter 100 produits différents 
simultanément sans s'attendre mutuellement.

#### Résumé de la BD :
| Table | Clé Primaire | Clés Étrangères | Description |
|-------|--------------|-----------------|-------------|
| `users` | `id` | - | Utilisateurs (email unique) |
| `customers` | `user_id` | --> users(id) | Extension client |
| `sellers` | `user_id` | --> users(id) | Extension vendeur (SIRET unique) |
| `administrators` | `user_id` | --> users(id) | Extension admin |
| `addresses` | `id` | --> users(id) | Adresses multiples par utilisateur |
| `categories` | `id` | - | Catégories de produits |
| `products` | `id` | --> sellers(user_id), --> categories(id) | Produits (alias unique) |
| `product_photos` | `id` | --> products(id) | Photos multiples par produit |
| `carts` | `id` | --> customers(user_id) | Panier unique par client |
| `cart_items` | `id` | --> carts(id), --> products(id) | Lignes de panier |
| `orders` | `id` | --> customers(user_id) | Commandes (référence unique) |
| `order_items` | `id` | --> orders(id), --> products(id) | Lignes de commande |
| `reviews` | `id` | --> customers(user_id), --> products(id) | Avis (unique par client/produit) |

#### Respect de la 3NF

1. **1NF** : Toutes les colonnes contiennent des valeurs atomiques
2. **2NF** : Pas de dépendance partielle (clés primaires simples ou composées correctement)
3. **3NF** : Pas de dépendance transitive :
   - Les informations utilisateur sont dans `users`, pas dupliquées dans `orders`
   - L'adresse de livraison est dénormalisée dans `orders` (snapshot au moment de la commande)
   - Les prix unitaires sont copiés dans `order_items` (historique des prix)

### 2.2 Absorption de la montée en charge (Stress Test)

Pour prouver que le serveur absorbe la charge :
*   **Exécution du BigDataSeeder :** Injection de **100 000 produits** et **80 000 commandes**.
*   **Résultat :** Le temps de génération de la page catalogue est resté sous les **200ms**.
*   **Justification :** L'usage de la pagination (`Pager` CodeIgniter) et des index SQL (`INDEX(product_status, category_id)`) garantit que le coût en temps reste convenable quelle que soit la volumétrie totale.

### 2.3 Identification des points consommateurs de temps

Nous avons listé les points critiques et leurs solutions :

#### Points critiques identifiés

| Point | Risque | Début de Solutions |
|-------|--------|--------------------|
| Catalogue avec filtres complexes | Requêtes lentes sur gros volumes | Index composites, cache |
| Upload d'images | Consommation mémoire/disque | Limites de taille, formats optimisés |
| Recherche textuelle | Full-scan potentiel | Index sur `title`, `short_description` |
| Calcul du panier | Recalcul à chaque modification | Total stocké et mis à jour incrémentalement |
| Sessions utilisateur | Fichiers multiples | Expiration à 7200s, nettoyage automatique |

### 2.4 Situations de Montée en Charge

*   **Situation identifiée :** Nouvelle mode autour des tapis

### 2.5 Organisation des données et Déploiement

```
src/
├── app/
│   ├── Config/          # Configuration centralisée
│   ├── Controllers/     # Logique métier par rôle
│   │   ├── Admin/       # Administration
│   │   ├── Client/      # Espace client
│   │   └── Seller/      # Espace vendeur
│   ├── Database/
│   │   ├── Migrations/  # Schéma versionné
│   │   └── Seeds/       # Données de test
│   ├── Entities/        # Objets métier
│   ├── Enums/           # Types énumérés
│   ├── Filters/         # Middleware de sécurité
│   ├── Models/          # Accès aux données
│   ├── Traits/          # Code réutilisable
│   └── Views/           # Templates par section
├── public/              # Point d'entrée web
│   └── uploads/         # Fichiers uploadés
├── tests/               # Tests automatisés
└── writable/            # Données temporaires
    ├── cache/
    ├── logs/
    └── session/
```

### 2.6 Plan de Dimensionnement (Stockage et Temps) (utilisation de l'IA car aucune connaissance)

Pour garantir une application optimale dans le temps :

*   **Calcul de Dimensionnement (Preuve) :**
    *   Production estimée : 50 000 produits/an.
    *   Poids images : $50 000 \times 3 \text{ photos} \times 150 \text{ Ko} \approx 22.5 \text{ Go}$.
    *   Données SQL : $\approx 200 \text{ Mo}$.
*   **Prévisionnel Matériel :**
    *   Prévoir un serveur avec **50 Go de stockage SSD**. Le SSD est crucial pour assurer que le temps d'accès aux 22 Go d'images reste négligeable (latence < 0.1ms).

### 2.7 Analyse de l'impact de l'hébergement (utilisation de l'IA car aucune connaissance)

Le choix de l'hébergement final impacte directement le temps de réponse (TTFB) :

*   **Disque (HDD vs SSD) :** Sur un HDD classique, charger une page avec 20 produits (donc 20 accès fichiers aléatoires) prendrait ~200ms juste en latence disque. Sur SSD, cela prend < 2ms. **Le SSD est obligatoire.**
*   **CPU :** Influence faible car PHP 8 est optimisé (Opcache).
*   **Réseau :** Pour absorber les pics, une bande passante de **1 Gbps** est nécessaire.

---

## 3. Conclusion

Ce rapport démontre que notre application Marketplace est conçue avec les bonnes pratiques de développement :

1. **Correction fonctionnelle** : Validations systématiques, gestion des erreurs, transactions ACID
2. **Efficacité** : Normalisation 3NF, index optimisés, pagination, cache
3. **Scalabilité** : Architecture containerisée, configuration modulaire


Nous considerons donc notre application comme **correcte, robuste et performante**.


