TodoList - Symfony v4.4
===============================================

> **Note** : Cette installation est valable uniquement pour un **environnement de développement** et suppose que vous ayez déjà cloné le projet.

Installation des dépendances
----------------------------
- Installer les dépendances PHP : `composer install`
- Installer les dépendances JS : `yarn install`

Installation de la base de données
----------------------------------
- Copier le fichier ".env" à la racine du projet et renommer le en ".env.local"
- Modifier la variable DATABASE_URL suivant vos besoins.
- Créer la base de données : `php bin/console doctrine:database:create`
- Mettre à jour la base de données : `php bin/console doctrine:schema:update --force`

Importation des fixtures
------------------------
- Executer `php bin/console doctrine:fixtures:load`