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
    // Route pour afficher la page d'accueil
    [
        'path' => '/',
        'controller' => 'HomeController',
        'action' => 'index',
    ],

    // Route pour afficher la page de connexion
    [
        'path' => '/login',
        'controller' => 'AuthController',
        'action' => 'login',
    ],

    // Route pour se connecter
    [
        'path' => '/login',
        'controller' => 'AuthController',
        'action' => 'login',
        'methods' => ['POST'],
    ],

    // Route pour afficher la page d'inscription
    [
        'path' => '/register',
        'controller' => 'AuthController',
        'action' => 'register',
    ],

    // Route pour s'inscrire
    [
        'path' => '/register',
        'controller' => 'AuthController',
        'action' => 'register',
        'methods' => ['POST'],
    ],

    // Route pour se déconnecter
    [
        'path' => '/logout',
        'controller' => 'AuthController',
        'action' => 'logout',
    ],

    // Route pour récupérer un utilisateur
    [
        'path' => '/user/(\d+)', // URL avec un paramètre ID utilisateur
        'controller' => 'UserController', // Contrôleur appelé : UserController
        'action' => 'profile', // Méthode du contrôleur : profile($id)
        'methods' => ['GET'],
        'params' => ['id'],
    ],

    // Route pour créer un nouveau post
    [
        'path' => '/post',
        'controller' => 'PostController',
        'action' => 'create',
        'methods' => ['POST'],
    ],

    // Route pour afficher un post spécifique
    [
        'path' => '/post/(\d+)',
        'controller' => 'PostController',
        'action' => 'view',
        'methods' => ['GET'],
        'params' => ['id'],
    ],
];
