# 🏪 TapisMarket

> Marketplace en ligne spécialisée dans la vente de tapis artisanaux et modernes.

<p align="center">
  <img src="https://img.shields.io/badge/PHP-8.4-777BB4?logo=php&logoColor=white" alt="PHP 8.4">
  <img src="https://img.shields.io/badge/CodeIgniter-4-EF4223?logo=codeigniter&logoColor=white" alt="CodeIgniter 4">
  <img src="https://img.shields.io/badge/MySQL-8.0-4479A1?logo=mysql&logoColor=white" alt="MySQL 8.0">
  <img src="https://img.shields.io/badge/Docker-Podman-892CA0?logo=podman&logoColor=white" alt="Podman">
  <img src="https://img.shields.io/badge/License-MIT-green" alt="MIT License">
</p>

---

## 📋 Contexte du projet

**TapisMarket** est un projet académique réalisé dans le cadre de la **SAE du Semestre 3** du BUT Informatique.

| | |
|---|---|
| 🎓 **Formation** | BUT Informatique - Semestre 3 |
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

## 🚀 Déploiement en production

### Prérequis

- **Podman** et **podman-compose** (ou Docker/docker-compose)

### Installation

**1. Cloner le projet**

```bash
git clone <url-du-repo>
cd TapisMarket
```

**2. Copier les sources dans le conteneur**

```bash
cp -R src conteneur/app_php/src
```

**3. Configurer les variables d'environnement**

Éditer le fichier `conteneur/prod.env` :

```env
ENVIRONMENT=production
DB_ROOT_PASSWORD=votre_mot_de_passe_root
DB_USER=votre_utilisateur
DB_PASSWORD=votre_mot_de_passe
DB_DATABASE=tapismarket
```

**4. Lancer les conteneurs**

```bash
podman-compose -f conteneur/compose.prod.yml --env-file conteneur/prod.env up -d
```

**5. Initialiser la base de données**

```bash
# Exécuter les migrations
podman-compose -f conteneur/compose.prod.yml exec web-prod php spark migrate

# (Optionnel) Charger des données de démonstration
podman-compose -f conteneur/compose.prod.yml exec web-prod php spark db:seed DataSeeder
```

**6. Accéder à l'application**

Ouvrir [http://localhost:8080](http://localhost:8080) dans votre navigateur.

### Arrêt des services

```bash
podman-compose -f conteneur/compose.prod.yml down
```

---

## 📖 Documentation technique

La documentation est générée avec **Sphinx**.

```bash
# Créer un environnement virtuel Python
python3 -m venv venv
source venv/bin/activate

# Installer les dépendances
pip install sphinx sphinx_rtd_theme sphinxcontrib-phpdomain

# Générer et ouvrir la documentation
python3 -m sphinx -b html src/user_guide_src/ src/user_guide/
xdg-open src/user_guide/index.html
```

---

## 📄 Licence

Ce projet est sous licence **MIT**. Voir le fichier [LICENSE](src/LICENSE) pour plus de détails.

---

<p align="center">
  Projet réalisé avec ❤️ dans le cadre du BUT Informatique
</p>
