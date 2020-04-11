TodoList - Comment participer au projet
==================================================

Workflow
--------
- Chaque membre rattaché au projet possédera des droits restreints en tant que reporter : le push vers le dépôt principal est impossible.
- Chacun devra travailler sur un fork personnel du dépôt principal.
- La fusion vers le dépôt principal devra s'effectuer par des merge request sur la branche develop soumises à approbation par le propriétaire responsable.

Dépôt "Fork"
------------
- Faites une demande par mail pour être ajouter au projet en tant que collaborateur.
- Créer un fork personnel à partir du dépôt principal : https://github.com/PyroFiire/TodoList-v4.4
- Après le fork du dépôt vers votre espace personnel et le clone sur votre machine, pensez à ajouter la liaison vers le main que vous nommerez "upstream" : `git remote add upstream https://github.com/PyroFiire/TodoList-v4.4.git`
- Mettez à jour la branche develop avec un `git pull upstream develop` et procéder à l'installation du projet, voir README.md

Contribuer au projet
--------------------
- Sur github, créer une issue qui décrit le travail que vous allez effectuer
- Créer une nouvelle branche feature/name ou bugfix/name à partir de develop
- Rédiger votre code et écriver les tests
- Controller les tests : `yarn phpunit`
- Utiliser les outils de qualités de code : `yarn fix` et `yarn analyse`
- Commitez et pushé votre travail sur votre fork personnel
- Sur Github, créer une merge request de votre nouvel branche vers la branche develop du débot principal.


