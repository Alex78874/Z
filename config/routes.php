<?php

/**
 * Ce fichier définit les routes utilisées par le routeur pour déterminer le contrôleur et l'action à exécuter selon l'URL.
 *
 * **Structure d'une route :**
 * - **'path'** : (string) Chemin de l'URL, avec des parties dynamiques capturées par des expressions régulières (ex. '(\d+)' pour capturer un entier).
 * - **'controller'** : (string) Nom du contrôleur.
 * - **'action'** : (string) Méthode du contrôleur à exécuter.
 * - **'params'** : (array) (optionnel) Paramètres capturés dans 'path', dans l'ordre.
 * - **'methods'** : (array) (optionnel) Méthodes HTTP autorisées (GET, POST, etc.).
 *
 * **Exemple :**
 * ```php
 * [
 *   'path' => '/article/(\d+)',
 *   'controller' => 'ArticleController',
 *   'action' => 'view',
 *   'params' => ['id'],
 *   'methods' => ['GET'],
 * ]
 * ```
 *
 * **Remarques :**
 * - Les routes sont évaluées dans l'ordre où elles apparaissent.
 * - Les expressions régulières dans 'path' doivent correspondre à l'URL attendue.
 * - Des paramètres optionnels peuvent être définis avec des expressions régulières appropriées.
 */

return [
    // Route statique pour la page d'accueil
    [
        'path' => '/', // URL : '/'
        'controller' => 'HomeController', // Contrôleur appelé : HomeController
        'action' => 'index', // Méthode du contrôleur : index()
    ],

    // Route statique pour la page de connexion
    [
        'path' => '/login',
        'controller' => 'AuthController',
        'action' => 'index_login',
    ],

    // Route statique pour la page d'inscription
    [
        'path' => '/register',
        'controller' => 'AuthController',
        'action' => 'index_register',
    ],

    // Route pour récupérer un utilisateur
    [
        'path' => '/user/(\d+)', // URL avec un paramètre ID utilisateur
        'controller' => 'UserController', // Contrôleur appelé : UserController
        'action' => 'profile', // Méthode du contrôleur : profile($id)
        'methods' => ['GET'],
        'params' => ['id'],
    ],

    // Route pour la page d'inscription
    [
        'path' => '/register',
        'controller' => 'AuthController',
        'action' => 'register',
        'methods' => ['POST'],
        'middlewares' => [
            function (): ValidationMiddleware {
                return new ValidationMiddleware(
                    rules: [
                        'email' => 'required|email',
                        'password' => 'required|min:6',
                    ],
                    messages: [
                        'email' => [
                            'required' => 'L\'email est requis.',
                            'email' => 'L\'email doit être valide.',
                        ],
                        'password' => [
                            'required' => 'Le mot de passe est requis.',
                            'min' => 'Le mot de passe doit comporter au moins 6 caractères.',
                        ],
                    ]
                );
            }
        ],
    ],

    // Route pour la page de connexion
    [
        'path' => '/login',
        'controller' => 'AuthController',
        'action' => 'login',
        'methods' => ['POST'],
        'middlewares' => [
            function (): ValidationMiddleware {
                return new ValidationMiddleware(
                    rules: [
                        'email' => 'required|email',
                        'password' => 'required',
                    ],
                    messages: [
                        'email' => [
                            'required' => 'L\'email est requis.',
                            'email' => 'L\'email doit être valide.',
                        ],
                        'password' => [
                            'required' => 'Le mot de passe est requis.',
                        ],
                    ]
                );
            }
        ],
    ],

    // Route pour créer un nouveau tweet
    [
        'path' => '/tweet/create',
        'controller' => 'TweetController',
        'action' => 'create',
        'methods' => ['POST'],
    ],

    // Route pour afficher un tweet spécifique
    [
        'path' => '/tweet/(\d+)',
        'controller' => 'TweetController',
        'action' => 'view',
        'methods' => ['GET'],
        'params' => ['id'],
    ],
];
