<?php

class User {
    private $pdo;

    public function __construct() {
        $this->pdo = getPDO();
    }

    public function create($data): void {
        // Code pour créer un nouvel utilisateur
    }

    public function login($email, $password): void {
        // Code pour authentifier un utilisateur
    }

    public function getUserById($id): void {
        // Code pour récupérer un utilisateur par son ID
    }

    // Autres méthodes pour interagir avec la table des utilisateurs
}
