## DB

Reprendre le model de classe d'analyse, voir quel **model** on doit créer

Pour créer un **model** avec codeigniter, il faut voir faire une 
[migration](https://www.codeigniter.com/userguide3/libraries/migration.html). 
Donc **ne pas toucher au sql directement**.

## Partials

Une **partials** c'est une [view](https://www.codeigniter.com/userguide3/general/views.html), 
donc un bout de html quoi, qui va être réutilisé à plusieurs endroits. 

Ex :
- Un header
- Une *card* pour acheter un tapis
- un formulaire ...

Dans notre app, les partials seront dans le dossier : `src/app/Views/Partials/`, 
et les pages directement dans `src/app/Views/`.

Il va falloir qu'on ait :
- Un header 
- Un footer
- Une card pour afficher un tapis `CarpetCard`
- Un formulaire pour créer un tapis `CarpetForm` (ça sera le même formulaire que pour le modifier)
    - Pour les formulaire, on va faire du html pure.
    - On va passer le lien vers lequel le formulaire renvoie
    - On va passer l'objet à modifier (instance de class Model), objet vide si on veut le créer
    - Pour les images, on va juste faire des liens. On se casse pas les c** avec l'upload des images


