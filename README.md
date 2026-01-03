## Lancer le projet

**Dépendances**

Installer `podman`, `podman-compose`

Sur linux : 

```bash
sudo apt install podman podman-compose
podman machine init
podman machine start
```

Sur mac :
```bash
brew install podman podman-compose
podman machine init
podman machine start
```

Sur windows :
```
Vasy j'ai la flemme de mettre toutes les commandes, va voir https://podman.io/docs/installation
```

**lancer le projet**

- lancer `./setup` 
- puis `podman-compose up` dans container

## précision de dev

- Pour modifier le code, **pas besoin** de push ou pull, modifier dans `src/` et ... C'est bon. Aller sur le navigateur ça marche hehe
- Je propose qu'on utilise [tailwind](https://tailwindcss.com/). Ça marche très bien avec codeigniter et les IA sont très forte.
- Pour la connection à la **db**, **TODO** (voir dans le compose.yml, les ENV ...)
- Utilisation de tickets : <br>
    C'est une fonctionnalité de gitlab qui nous permet d'avoir des todo.<br>
    (dans "*Tableau des tickets*" sur la page du projet, c'est une sorte de canban) 

    Voici le workflow : 
    1. clicker sur le ticket depuis canban
    1. changer les labels (enlever Todo et mettre Doing), vous pouvez vous assigner
    1. clicker sur : `Créer une requête de fusion` (laisser les valeures par défault et valider) <br>
        cela va créer une branch sur laquelle vous travaillerez, vous la retrouverez en dessous du titre<br>
        donc en local :
        ```bash
        git pull
        git checkout <branch_name>
        ```
    1. Faire les modification et push ...
    1. Modifier les labels (enlever Doing et mettre Done) **sur la merge request ET sur le ticket**
    1. m'envoyer un message signal et me laisser faire pour le merge
