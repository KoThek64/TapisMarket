# 🏪 TapisMarket

> Marketplace en ligne spécialisée dans la vente de tapis artisanaux et modernes.

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
  <img src="https://img.shields.io/badge/License-MIT-green" alt="MIT License">
</p>

---

## 📋 Contexte du projet

**TapisMarket** est un projet académique réalisé dans le cadre de la **SAE du Semestre 3** du BUT Informatique.

| | |
|---|---|
| 🎓 **Formation** | BUT Informatique |
| 👥 **Équipe** | 5 étudiants |
| 📅 **Année** | 2025-2026 |

### 👨‍💻 Membres de l'équipe

- AIGNELOT Youenn
- BERNARD Adam
- FILMONT Félix
- LACHAISE Mattys
- PLU Niels

---

## 🛒 Présentation

**TapisMarket** est une marketplace permettant la mise en relation entre vendeurs professionnels et clients particuliers autour de produits de type tapis (artisanaux, modernes, d'intérieur, d'extérieur...).

### ✨ Fonctionnalités principales

#### 👤 Espace Client
- Création de compte et authentification
- Navigation et recherche dans le catalogue
- Consultation des fiches produits détaillées
- Gestion du panier d'achat
- Processus de commande (checkout)
- Suivi des commandes
- Gestion des adresses de livraison
- Système d'avis et de notation

#### 🏬 Espace Vendeur
- Tableau de bord vendeur
- Gestion des produits (CRUD)
- Upload de photos produits
- Gestion des commandes reçues
- Personnalisation de la boutique
- Consultation des avis clients

#### 🔧 Espace Administrateur
- Dashboard de supervision
- Gestion des utilisateurs
- Modération des produits
- Gestion des catégories
- Suivi des commandes globales
- Modération des avis

---

## 🛠️ Stack technique

| Composant | Technologie |
|-----------|-------------|
| **Backend** | PHP 8.4 |
| **Framework** | CodeIgniter 4 |
| **Base de données** | MySQL 8.0 |
| **Serveur web** | Apache |
| **Conteneurisation** | Podman / Docker |
| **Tests** | PHPUnit |
| **Documentation** | Sphinx |

---

## 📁 Architecture du projet

```
TapisMarket/
├── conteneur/              # Configuration Docker/Podman
│   ├── compose.prod.yml    # Orchestration production
│   ├── prod.env            # Variables d'environnement
│   └── app_php/            # Image PHP/Apache
│
├── src/                    # Code source de l'application
│   ├── app/
│   │   ├── Config/         # Configuration CodeIgniter
│   │   ├── Controllers/    # Contrôleurs (Admin, Client, Seller)
│   │   ├── Entities/       # Entités métier
│   │   ├── Models/         # Modèles de données
│   │   ├── Views/          # Vues (templates)
│   │   ├── Filters/        # Middlewares
│   │   └── Database/       # Migrations & Seeds
│   │
│   ├── public/             # Point d'entrée web
│   ├── tests/              # Tests unitaires
│   └── writable/           # Fichiers générés (logs, cache, uploads)
│
└── rapport/                # Documentation projet
    ├── analyse/            # Diagrammes UML (Visual Paradigm)
    └── design-patern/      # Documentation architecture
```

---

## 🚀 Installation locale

### Prérequis

- **Podman** et **podman-compose** (ou Docker/docker-compose)

### Installation

```bash
# 1. Cloner le projet
git clone <url-du-repo>
cd TapisMarket

# 2. Copier les sources
cp -R src conteneur/app_php/src

# 3. Lancer les conteneurs
podman-compose -f conteneur/compose.prod.yml --env-file conteneur/prod.env up -d

# 4. Initialiser la base de données
podman-compose -f conteneur/compose.prod.yml exec web-prod php spark migrate
podman-compose -f conteneur/compose.prod.yml exec web-prod php spark db:seed DataSeeder
```

Ouvrir [http://localhost:8080](http://localhost:8080)

---

## 📄 Licence

Ce projet est sous licence **MIT**. Voir le fichier [LICENSE](src/LICENSE) pour plus de détails.

---

<p align="center">
  Projet réalisé avec ❤️ dans le cadre du BUT Informatique
</p>
