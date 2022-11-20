To Do & Co
====

### Indice qualité
1. L'application a été évaluée sur Codacy. La note attribuée au projet est la suivante :

https://app.codacy.com/project/badge/Grade/3cc71cd3304943e19d69c1c998819aea

### Installation

1. Clonez ou téléchargez le repository.
1. Modifiez le fichier .env avec vos paramètres de BDD.
1. Ouvrez la console dans le repertoire racine.
1. Executez dans la console « composer install »  pour installer toutes les dependances.

1.	Exécutez dans le dossier que vous avez téléchargé « php bin/console symfony server:start » pour démarrer le serveur local. 
1.	Votre projet devrait être accessible à l’adresse localhost:8000 (le numéro du port est indiqué dans la console lorsque vous lancez le serveur)

### Tests

Pour lancer les tests :
1. Lancez la console à la racine du projet
1. Entrez « vendor/bin/phpunit --coverage-html tests/test-coverage »

1.	Le rapport de coverage est accessible dans le dossier public/test-coverage


### Pour contribuer

Consultez [CONTRIBUTING.md](https://github.com/JeanBosch/ToDo/blob/master/CONTRIBUTING.md)

### Built with :

- Symfony 5.4
- PHPUnit 9
