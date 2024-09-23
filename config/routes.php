<?php
// config/routes.php

/**
 * Ce fichier retourne un tableau de routes utilisées par le routeur pour
 * déterminer quel contrôleur et quelle action doivent être appelés en fonction de l'URL demandée.
 *
 * **Structure d'une route :**
 * Chaque route est définie par un tableau associatif avec les clés suivantes :
 *
 * - **'path'** : (string) Le chemin de l'URL, pouvant contenir des parties dynamiques capturées avec des expressions régulières.
 *   - Les parties dynamiques sont définies en utilisant des parenthèses dans l'expression régulière.
 *   - Par exemple, '(\d+)' capture un ou plusieurs chiffres (entier).
 *   - '(.+)' capture une ou plusieurs occurrences de n'importe quel caractère (chaîne de caractères).
 *
 * - **'controller'** : (string) Le nom du contrôleur à appeler (doit correspondre au nom de la classe du contrôleur).
 *
 * - **'action'** : (string) Le nom de la méthode du contrôleur à exécuter.
 *
 * - **'params'** : (array) (optionnel) Un tableau des noms de paramètres capturés dans l'URL.
 *   - Les noms dans 'params' correspondent aux parties dynamiques capturées dans 'path', dans le même ordre.
 *
 * **Exemple de création d'une route :**
 *
 * ```php
 * [
 *     'path' => '/article/(\d+)/comment/(\d+)',
 *     'controller' => 'CommentController',
 *     'action' => 'view',
 *     'params' => ['article_id', 'comment_id'],
 * ],
 * ```
 *
 * **Explications :**
 *
 * - **'path'** : '/article/(\d+)/comment/(\d+)'
 *   - '/article/' : partie statique de l'URL.
 *   - '(\d+)' : première partie dynamique qui capture un entier (ID de l'article).
 *   - '/comment/' : autre partie statique.
 *   - '(\d+)' : deuxième partie dynamique qui capture un entier (ID du commentaire).
 *
 * - **'params'** : ['article_id', 'comment_id']
 *   - 'article_id' correspond à la première capture '(\d+)'.
 *   - 'comment_id' correspond à la deuxième capture '(\d+)'.
 *
 * **Remarques importantes :**
 *
 * - **Ordre des routes** : Les routes sont évaluées dans l'ordre où elles apparaissent dans le tableau.
 *   Placez les routes les plus spécifiques avant les routes plus génériques pour éviter les conflits.
 *
 * - **Expressions régulières** : Assurez-vous que les expressions régulières utilisées dans 'path' sont correctes
 *   et correspondent au format attendu de l'URL.
 *
 * - **Paramètres optionnels** : Si vous souhaitez avoir des paramètres optionnels, vous pouvez adapter
 *   l'expression régulière en conséquence, par exemple en utilisant '?':
 *   - '(/(\d+))?' rend la partie '/(\d+)' optionnelle.
 *
 * - **Méthodes HTTP** : Si vous souhaitez gérer différentes méthodes HTTP (GET, POST, etc.), vous pouvez étendre
 *   la structure de la route pour inclure un champ 'methods', bien que ce ne soit pas présent dans cet exemple.
 */

return [
    // Route statique pour la page d'accueil
    [
        'path' => '/', // URL : '/'
        'controller' => 'HomeController', // Contrôleur appelé : HomeController
        'action' => 'index', // Méthode du contrôleur : index()
    ],

    // Route avec un paramètre dynamique (exemple : '/user/42')
    [
        'path' => '/user/(\d+)', // URL avec un paramètre ID utilisateur
        'controller' => 'UserController', // Contrôleur appelé : UserController
        'action' => 'profile', // Méthode du contrôleur : profile($id)
        'params' => ['id'], // Nom du paramètre capturé : $id
    ],

    // Route pour la page de connexion
    [
        'path' => '/login', // URL : '/login'
        'controller' => 'UserController', // Contrôleur appelé : UserController
        'action' => 'login', // Méthode du contrôleur : login()
    ],

    // Route pour la page d'inscription
    [
        'path' => '/register', // URL : '/register'
        'controller' => 'UserController', // Contrôleur appelé : UserController
        'action' => 'register', // Méthode du contrôleur : register()
    ],

    // Route pour créer un nouveau tweet
    [
        'path' => '/tweet/create', // URL : '/tweet/create'
        'controller' => 'TweetController', // Contrôleur appelé : TweetController
        'action' => 'create', // Méthode du contrôleur : create()
    ],

    // Route pour afficher un tweet spécifique (exemple : '/tweet/123')
    [
        'path' => '/tweet/(\d+)', // URL avec un paramètre ID du tweet
        'controller' => 'TweetController', // Contrôleur appelé : TweetController
        'action' => 'view', // Méthode du contrôleur : view($id)
        'params' => ['id'], // Nom du paramètre capturé : $id
    ],

    // Vous pouvez ajouter d'autres routes ici en suivant le même format
];
