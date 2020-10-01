# Test technique Morpheus Branchement

## Sujet

L’équipe Morpheus a signé des contrats avec deux clients.

Le premier nous donne un fichier JSON d’annonces immo que nous devons importer sur leboncoin. Les libellés des champs sont plutôt explicites dans le fichier. Cependant, certains champs ne sont pas nécessaires pour Leboncoin.

Le second est un fichier XML d’annonces emploi d’une agence d’Intérim. On nous demande de concaténer les champs description_poste et description_entreprise pour pouvoir afficher toutes ces informations dans le corps de l’annonce. 
Ces champs comportent des balises HTML mais Leboncoin n’accepte pas le HTML. Il faut donc nettoyer ces champs tout en gardant un rendu compréhensible (garder les sauts de ligne et les listes).

Avec le projet Symfony fournit, traitez et transformez les deux fichiers d’annonces pour qu’ils puissent être acceptés par l’API documentée ci-dessous.

Des tests unitaires sont nécessaires concernant les Hooks et leurs fonctions. 

## Bonus

Le code postal n’est pas présent dans les données XML mais on aimerait tout de même l’avoir. Il vous est demandé de proposer une solution technique pour récupérer le code postal mais l’implémentation est optionnelle.

## Rendu

Vous devez rendre un projet Symfony fonctionnel qui transforme des annonces pour un envoi vers l’API Leboncoin. Vous pouvez aussi rendre un court rapport de quelques lignes décrivant certains problèmes à résoudre ou des pistes d’amélioration.

Les données des clients ne sont pas forcément de bonne qualité ou à l’inverse intègrent des informations dont l’API Leboncoin n’a pas besoin. 

Cependant, pour améliorer la qualité des annonces importées, vous pouvez proposer des solutions pour intégrer davantage de données. Vous pouvez implémenter ces solutions pour prouver le concept proposé mais tout ceci est optionnel.

Une attention sera apportée à la qualité du code mais faites au plus simple avec le temps que vous avez.

## Documentation

La documentation de l'API est disponible dans le fichier `Sujet.pdf`.

## Lancement

```
bin/console command-name
```

----------------------------------------------------
----------------------------------------------------

# Abdelhamid Benmeziane

## Installation

1. Cloner le projet git ou télécharger le dossier ZIP puis décompresser à l'emplacement de votre choix

2. Lancer la commande `composer install` pour installer les dépendances

3. Exécuter les commandes :

    * pour envoyer les annonces de Job vers l'Api à partir du fichier `job.xml` : 
   
   ```
   php bin/console job-executor
   ```
   
    * pour envoyer les annonces immobilières vers l'Api à partir du fichier `real_estate.json` :
    
    ```
    php bin/console real-estate-executor
    ```
    
4. Pour lancer les tests unitaires du dossier `morpheus/tests` :

    ```
    php bin/phpunit
    ```

## Dépendances installées pour les besoins de l'exercice

    * symfony/http-client (GeolocationHook)
    * PHPUnit (testing)

## GeolocationHook : récupérer le code postal

Pour répondre au besoin de récupérer le code postal pour les annonces immobilières (Bonus), j'ai choisi par souci de simplification de consommer une API web open source : nominatim / openmapstreet.

nominatim pour l'API et openmapstreet pour les data.

La documentation se trouve à https://nominatim.org/

Elle n'est pas aussi fiable que des webservices comme Google Map pour certaines requêtes (documenté dans le code) mais elle rest plutôt de bonne qualité.

La classe GeolocationHook via la methode publique getZipCode() permet de récupérer le code postal retourné par la réponse du serveur.
 
La requête GET est envoyée à l'adresse https://nominatim.openstreetmap.org/?<params>


## Sécurité

C'est un sujet large (XSS, CSRF, SQL, FORCE BRUTE....), ici il n'y a ni base de données ni formulaires pour le moment.

Cependant certaines données proviennent de fichiers non propriétaire ou de webservices. J'ai donc eu recours à des "inbuilt functions" de PHP afin d'échapper les caractères spéciaux pour eviter l'injectinon de scripts et nettoyer les données principalement avec `filter_vars( $input, FILTER_SANITIZE_TYPE )`.
J'ai également implémenter une fonction (qui pourrait devenir un service ou hook) pour nettoyer les éléments d'un tableau (array).

## Tests

Comme demandé dans le sujet, les tests ont été réalisés uniquement pour les Hooks.

Faute de temps, la couverture n'est pas à 100% sur les fonctions des Class concernées mais quelques tests intéressants ont été réalisés.

Pour ce faire, j'ai utilisé PHPunit.

Pour l'installer si vous ne l'avez pas déjà fait:

```
composer require --dev symfony/phpunit-bridge
```

Pour lancer les tests : 

```
php bin/phpunit
```


## Problèmes rencontrés

Cela concernait principalement la concordance des données entre l'Api et la documentation (champs requis ou non, le type de donnés accéptée).

Je me suis conformé à l'Api car il a faut bien choisir entre la modification de l'api ou ne pas se conformer à la documentation (dans ce cas précis).

Dans la réalité, la plupart du temps, c'est plutôt la documentation qui n'est pas à jour.

De plus si nous consommons une API web il n'est pas possible de la modifier, il faudra donc se fier à la reponse du serveur en ca d'erreurs. 


## TODO

- Améliorer la gestion des erreurs
- Formatter et concatener plus de données pour améliorer la qualité des annonces
- Revoir les règles de l'Api ( par exemple les limites, les types de données... )
- Créer une interface graphique pour des utilisateurs internes
- Automatiser certaines tâches grâce à la gestion des événements ( EventDispatcher || SplObserver ) par exemple lorsqu'un fichier est chargé dans le dossier /data


## Conclusion

Exercice très intéressant pour se familiariser avec Symfony, PHP 7 et ses nouveautés, la manipulation de fichiers XML et JSON et les données, la créations de Hooks, les webservices et bien plus.

J'espère que mon application vous a plu et que vous aurez des retours constructifs à me faire pour m'améliorer. 

Je vous donne rendez-vous dans quelques jours ( le temps de documenter et d'expliquer ) pour un autre exercice Symfony reposant cette fois sur Doctrine ORM, Twig, Webpack Encore, Alice Fixtures... :

[CRUD'IT HauteSchool](https://github.com/ahmidbbc/crudIT)

Vos retours sont les bienvenus à [ahmidbbc@gmail.com](mailto:ahmidbbc@gmail.com)


Merci d'avoir pris le temps de décortiquer mon code et de tester mon application !

Enjoy ! :smile: :metal:




