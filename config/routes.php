<?php
$routes = [
    '/' => ['controller' => 'HomeController', 'action' => 'index'],
    '/login' => ['controller' => 'UserController', 'action' => 'login'],
    '/register' => ['controller' => 'UserController', 'action' => 'register'],
    '/logout' => ['controller' => 'UserController', 'action' => 'logout'],
    '/tweet/create' => ['controller' => 'TweetController', 'action' => 'create'],
    '/tweet/list' => ['controller' => 'TweetController', 'action' => 'list'],
    // Ajoutez d'autres routes selon vos besoins
];
