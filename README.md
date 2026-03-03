<h1 align="center">🏪 TapisMarket</h1>
<p align="center"><em>Marketplace en ligne spécialisée dans la vente de tapis artisanaux et modernes.</em></p>

<p align="center">
  <a href="https://tapismarket.up.railway.app">
    <img src="https://img.shields.io/badge/🌐_Voir_le_site-TapisMarket-C9A227?style=for-the-badge" alt="Voir le site">
  </a>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/PHP-8.4-777BB4?logo=php&logoColor=white" alt="PHP 8.4">
  <img src="https://img.shields.io/badge/CodeIgniter-4-EF4223?logo=codeigniter&logoColor=white" alt="CodeIgniter 4">
  <img src="https://img.shields.io/badge/MySQL-8.0-4479A1?logo=mysql&logoColor=white" alt="MySQL 8.0">
  <img src="https://img.shields.io/badge/Hosted_on-Railway-0B0D0E?logo=railway&logoColor=white" alt="Railway">
  <img src="https://img.shields.io/badge/License-MIT-22c55e" alt="MIT License">
</p>

---

## 📋 Contexte du projet

**TapisMarket** est un projet académique réalisé dans le cadre de la **SAE du Semestre 3** du BUT Informatique.

| | |
|---|---|
| **Formation** | BUT Informatique |
| **Équipe** | 5 étudiants |
| **Année** | 2025-2026 |

### Membres de l'équipe

- AIGNELOT Youenn
- BERNARD Adam
- FILMONT Félix
- LACHAISE Mattys
- PLU Niels

---

## 🛒 Présentation

**TapisMarket** est une marketplace permettant la mise en relation entre vendeurs professionnels et clients particuliers autour de produits de type tapis (artisanaux, modernes, d'intérieur, d'extérieur...).

### Fonctionnalités principales

#### Espace Client
- Création de compte et authentification
- Navigation et recherche dans le catalogue par catégorie
- Consultation des fiches produits détaillées
- Gestion du panier d'achat
- Processus de commande (checkout) avec choix du mode de livraison
- Suivi des commandes (PAID → PREPARING → SHIPPED → DELIVERED)
- Gestion des adresses de livraison
- Système d'avis et de notation

#### Espace Vendeur
- Tableau de bord avec statistiques
- Gestion des produits (CRUD)
- Upload et gestion des photos produits
- Gestion des commandes reçues
- Personnalisation de la boutique (nom, description)
- Consultation des avis clients

#### Espace Administrateur
- Dashboard de supervision global
- Gestion et validation des comptes vendeurs
- Modération des produits (APPROVED / PENDING_VALIDATION)
- Gestion des catégories
- Suivi de toutes les commandes
- Modération des avis (PUBLISHED / REFUSED)

---

## 🛠️ Stack technique

| Composant | Technologie |
|-----------|-------------|
| **Backend** | PHP 8.4 |
| **Framework** | CodeIgniter 4 |
| **Base de données** | MySQL 8.0 |
| **Serveur web** | Apache |
| **Conteneurisation** | Podman / Docker |
| **Tests** | PHPUnit 10 |
| **Documentation** | Sphinx |

---

## 📁 Architecture du projet

```
TapisMarket/
├── conteneur/                  # Configuration Docker/Podman
│   ├── compose.dev.yml         # Orchestration développement (app + MySQL + PHPMyAdmin)
│   ├── compose.prod.yml        # Orchestration production
│   ├── dev.env                 # Variables d'environnement développement
│   ├── prod.env                # Variables d'environnement production
│   └── app_php/                # Image PHP/Apache
│
├── src/                        # Code source de l'application
│   ├── app/
│   │   ├── Config/             # Configuration CodeIgniter
│   │   ├── Controllers/        # Contrôleurs (Admin, Client, Seller + partagés)
│   │   ├── Entities/           # Entités métier (User, Product, Order…)
│   │   ├── Models/             # Modèles de données (14 modèles)
│   │   ├── Views/              # Vues organisées par rôle
│   │   ├── Filters/            # Middlewares (auth, validation vendeur)
│   │   ├── Helpers/            # Fonctions utilitaires (auth, cart, alert, icon)
│   │   ├── Enums/              # Enums PHP (UserRole, ShippingType)
│   │   ├── Libraries/          # Librairies (patron Template Method – livraison)
│   │   └── Database/           # Migrations & Seeds
│   │
│   ├── public/                 # Point d'entrée web (index.php)
│   ├── tests/                  # Tests PHPUnit
│   └── writable/               # Fichiers générés (logs, cache, uploads)
│       └── seed_images/        # Images sources pour le seeding (à créer)
│
├── rapport/                    # Documentation projet
│   ├── analyse/                # Diagrammes UML (Visual Paradigm)
│   └── design-patern/          # Documentation architecture
│
├── Dockerfile                  # Image de production (Railway)
└── dev.sh                      # Script de démarrage rapide en développement
```

---

## 🚀 Installation locale

### Prérequis

- **Podman** et **podman-compose** (ou Docker / docker-compose)

### Développement

Le script `dev.sh` automatise le démarrage complet :

```bash
# 1. Cloner le projet
git clone <url-du-repo>
cd TapisMarket

# 2. Lancer l'environnement de développement
bash dev.sh
```

Ce script :
1. Démarre les conteneurs (application + MySQL + PHPMyAdmin)
2. Installe les dépendances Composer si absent
3. Applique les migrations
4. Alimente la base avec le DataSeeder

| Service | URL |
|---------|-----|
| Application | http://localhost:8080 |
| PHPMyAdmin | http://localhost:8082 |

### Production

```bash
# 1. Cloner le projet
git clone <url-du-repo>
cd TapisMarket

# 2. Copier les sources dans le conteneur
cp -R src conteneur/app_php/src

# 3. Lancer les conteneurs de production
podman-compose -f conteneur/compose.prod.yml --env-file conteneur/prod.env up -d

# 4. Initialiser la base de données
podman-compose -f conteneur/compose.prod.yml exec web-prod php spark migrate
podman-compose -f conteneur/compose.prod.yml exec web-prod php spark db:seed DataSeeder
```

Ouvrir [http://localhost:8080](http://localhost:8080)

### Variables d'environnement

Les fichiers `conteneur/dev.env` et `conteneur/prod.env` définissent les variables de configuration :

| Variable | Description |
|----------|-------------|
| `ENVIRONMENT` | Environnement CI4 (`development` / `production`) |
| `DB_ROOT_PASSWORD` | Mot de passe root MySQL |
| `DB_USER` | Utilisateur MySQL |
| `DB_PASSWORD` | Mot de passe MySQL |
| `DB_DATABASE` | Nom de la base de données |

---

## 👤 Comptes de test (DataSeeder)

Le mot de passe est **`123456`** pour tous les comptes générés par le seeder.

| Rôle | Email | Mot de passe |
|------|-------|--------------|
| **Administrateur** | `admin@tapis.com` | `123456` |
| **Vendeur** (validé) | `seller0@mail.com` | `123456` |
| **Vendeurs** (autres) | `seller1@mail.com` … `seller9@mail.com` | `123456` |
| **Clients** | `client0@mail.com` … `client29@mail.com` | `123456` |

> **Images produits :** Pour que les produits aient des images lors du seeding, créer le dossier `src/writable/seed_images/` et y déposer des fichiers `.jpg`, `.jpeg`, `.png` ou `.webp` avant de lancer `db:seed DataSeeder`.

---

## ⚡ Commandes utiles

```bash
# Migrations
php spark migrate

# Jeu de données standard (~10 vendeurs, 30 clients, ~80 produits)
php spark db:seed DataSeeder

# Jeu de données volumeux (pour tests de performance)
php spark db:seed BigDataSeeder

# Réinitialiser la base
php spark migrate:refresh --seed DataSeeder
```

---

## 🏗️ Patron de conception — Template Method

Le calcul des frais de livraison est implémenté via le **patron Template Method** dans `src/app/Libraries/TemplateMethod/Shipping/`.

| Type | Classe |
|------|--------|
| Standard | `StandardShipping` |
| Express | `ExpressShipping` |
| Gratuite | `FreeShipping` |
| Internationale | `InternationalShipping` |

Toutes les variantes héritent de `ShippingTemplateMethod` qui définit l'algorithme commun de calcul.

---

## 🗄️ Base de données

La base de données est initialisée par une unique migration (`InitialiseMarketplace`) créant 13 tables :

`users` · `customers` · `sellers` · `administrators` · `addresses` · `categories` · `products` · `product_photos` · `carts` · `cart_items` · `orders` · `order_items` · `reviews`

Les entités clés utilisent des champs enum pour le suivi d'état :

| Entité | États |
|--------|-------|
| **Produits** | `PENDING_VALIDATION` → `APPROVED` / `REFUSED` |
| **Vendeurs** | `PENDING_VALIDATION` → `VALIDATED` / `REFUSED` |
| **Commandes** | `PAID` → `PREPARING` → `SHIPPED` → `DELIVERED` (ou `CANCELLED`) |
| **Avis** | `PENDING` → `PUBLISHED` / `REFUSED` |

---

<p align="center">
  Projet réalisé avec ❤️ dans le cadre du BUT Informatique
</p>
