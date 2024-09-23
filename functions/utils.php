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


