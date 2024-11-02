<?php

// Récupérer le chemin de base
// Par exemple, si le script est dans /myapp/public/index.php, le chemin de base sera /myapp
//
// Utilité : pour les liens relatifs dans les vues et le routage

function getBasePath(): string
{
    // Obtenir le chemin de base en fonction de l'emplacement du script
    $basePath = dirname($_SERVER['SCRIPT_NAME']);
    // S'assurer que le chemin de base n'est pas simplement '/' ou '\'
    if ($basePath === '/' || $basePath === '\\') {
        $basePath = '';
    }
    return $basePath;
}

//  Simplifier la Génération des URLs pour le html
function url($path = ''): string
{
    $basePath = getBasePath();
    // S'assurer que $path commence par un slash
    if ($path && $path[0] !== '/') {
        $path = '/' . $path;
    }
    return $basePath . $path;
}

// Afficher une vue avec des données
function view($view, $data = [], $layout = true): void
{
    // Extraire les variables pour les utiliser dans la vue
    extract(array: $data);

    if ($layout) {
        // Inclure le header
        include_once __DIR__ . '/../app/views/layouts/header.php';
    }
    include_once __DIR__ . '/../app/views/' . $view . '.php';
    if ($layout) {
        // Inclure le footer
        include_once __DIR__ . '/../app/views/layouts/footer.php';
    }
}

// Rediriger vers une URL
function redirect($url): void
{
    header(header: 'Location: ' . url($url));
    exit();
}

// Afficher une erreur 404
function send404($message = 'Page non trouvée'): never
{
    header(header: "HTTP/1.0 404 Not Found");
    echo $message;
    exit();
}

function send405($allowedMethods): never
{
    header(header: 'HTTP/1.1 405 Method Not Allowed');
    header(header: 'Allow: ' . implode(separator: ', ', array: $allowedMethods));
    echo 'Méthode non autorisée. Seules les méthodes suivantes sont autorisées : ' . implode(separator: ', ', array: $allowedMethods);
    exit();
}

