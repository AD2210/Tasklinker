TaskLinker
==========

TaskLinker est une application de gestion de projet interne pour BeWize

## Versions Utilisées

Ce projet a été developper avec Symfony 7.2.5 sous php 8.4,
toutes versions antérieures ne garanties pas le focntionnement de l'application

La base de donnée utilisé est une MariaDB 10.4.5 lors du developpement,
tout autre base SRGBR est possible, voir plus bas pour la configuration

## Installation

1 - Commencer par cloner le projet depuis le [gitHub](https://github.com/AD2210/Tasklinker) dans votre ide

2 - Executer la commande pour initialiser le projet symfony

```bash
composer install
```

3 - Parametrer votre BDD

3.1 - Vérifier dans votre php.ini que le driver dont vous avez besoin est bien activé

**exemple :** si vous utiliser Mysql, vérifier que cette ligne est decommenté
        
`extension=pdo_mysql`

3.2 - Copier le fichier _.env_ et renommer le en _.env.local_

3.3 - Parametrer votre connexion à l'aide de la ligne correspondante a votre BDD

`DATABASE_URL="mysql://!identifiant!:!motdepasse!@!ipconnexion!:!port!/!nomBDD!?serverVersion=!Version!&charset=utf8mb4`

4 - Démarer votre système de base de donnée puis créer la base à l'aide de la commande

```bash
symfony console doctrine:database:create
```

5 - Migrer le schéma de la base avec la commande

```bash
symfony console doctrine:migrations:migrate
```

6 - **Optionnel** Charger les Fixtures avec la commande

```bash
symfony console doctrine:fixtures:load 
```

## Usage

Démarer votre serveur symfony

```bash
symfony serve -d
```

## License

Ce projet est réaliser dans le cadre d'une formation OpenClassroom