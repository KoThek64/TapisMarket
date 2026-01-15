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
podman-compose -f conteneur/compose.prod.yml up -d
```

Ensuite il faut executer les migration pour mettre en place la base de donnée

```bash
podman-compose -f conteneur/compose.prod.yml exec web-prod php spark migrate
```

Si on veut utiliser des données de test, on peut lancer un `DataSeeder`


```bash
podman-compose -f conteneur/compose.prod.yml exec web-prod php spark db:seed DataSeeder
```
