# lancer le projet 

## dépendances 

ce projet utilise `podman`, et `podman-compose`

## lancer le projet en production

Il faut en premier temps copier `src` dans `app_php` :

```bash
cp -R src conteneur/app_php/src
```

Ensuite, il faut lancer `podman-compose` (qui se charge d'installer et de lancer les containers) :

```bash
podman-compose -f conteneur/compose.prod.yml --env-file conteneur/prod.env up -d
```

Ensuite il faut executer les migration pour mettre en place la base de donnée

```bash
podman-compose -f conteneur/compose.prod.yml exec web-prod php spark migrate
```

Si on veut utiliser des données de test, on peut lancer un `DataSeeder`


```bash
podman-compose -f conteneur/compose.prod.yml exec web-prod php spark db:seed DataSeeder
```

## régler les variables d'environnement 

Pour customiser les variables d'environnement, on peut éditer `conteneur/app_php/prod.env`

- `ENVIRONMENT` l'environnement (soit production soit development)
- `DB_ROOT_PASSWORD` le mot de passe root de la DB
- `DB_USER` l'utilisateur de la DB
- `DB_PASSWORD` le mot de paasse de l'utilisateur de la DB
- `DB_DATABASE` le nom de la DB que le site va utiliser

# Documentation

La documentation est générée avec Sphinx. Il est recommandé d'utiliser un environnement virtuel Python 3 :

## Prérequis

```bash
python3 -m venv venv
source venv/bin/activate
pip install sphinx sphinx_rtd_theme sphinxcontrib-phpdomain
```

## Générer et ouvrir la documentation

```bash
rm -rf src/user_guide/ && \
python3 -m sphinx -b html src/user_guide_src/ src/user_guide/ && \
(xdg-open src/user_guide/index.html || open src/user_guide/index.html || echo "Lien : src/user_guide/index.html")
```
