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

Les données des clients ne sont pas forcément de bonne qualité ou à l’inverse intègrent des informations dont l’API Leboncoin n’a pas besoin. Cependant, pour améliorer la qualité des annonces importées, vous pouvez proposer des solutions pour intégrer davantage de données. Vous pouvez implémenter ces solutions pour prouver le concept proposé mais tout ceci est optionnel.

Une attention sera apportée à la qualité du code mais faites au plus simple avec le temps que vous avez.

## Documentation

La documentation de l'API est disponible dans le fichier `Sujet.pdf`.

## Lancement

```
bin/console command-name
```

## Installation

1. Cloner le projet git ou télécharger le dossier ZIP puis décompresser à l'emplacement de votre choix

2. Lancer la commande `composer install` pour installer les dépendances

3. Exécuter les commandes :

    * pour envoyer les annonces de job vers l'api à partir du fichier `job.xml` : 
   
   ```
   php bin/console job-executor
   ```
   
    * pour envoyer les annonces immobilières vers l'api à partir du fichier `real_estate.json` :
    
    ```
    php bin/console real-estate-executor
    ```
    
4. Pour lancer les tests unitaires du dossier `morpheus/tests` :

    ```
    php bin/phpunit
    ```

## Dependances installées pour les besoins de l'exercice

    * symfony/http-client (GeolocationHook)
    * PHPUnit (testing)

## GeolocationHook : récuperer le code postal

Pour répondre au besoin de récupérer le code postal pour les annonces immobilières (Bonus), j'ai choisi par souci de simplification de consommer une API web open source : nominatim / openmapstreet.

nominatim pour l'API et openmapstreet pour les data.

La documentation se trouve à https://nominatim.org/

Elle n'est pas aussi fiable que des webservices comme Google Map pour certaines requêtes (documenté dans le code) mais elle rest plutôt de bonne qualité.

La classe GeolocationHook via la methode publique getZipCode() permet de recupérer le code postal retourner par la réponse du serveur.
 
La requête GET est envoyé à l'adresse https://nominatim.openstreetmap.org/?<params>


