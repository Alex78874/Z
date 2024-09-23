<?php

class User {
    private $pdo;

    public function __construct() {
        $this->pdo = getPDO();
    }

    public function create($data) {
        // Code pour créer un nouvel utilisateur
    }

    public function login($email, $password) {
        // Code pour authentifier un utilisateur
    }

    // Autres méthodes pour interagir avec la table des utilisateurs
}
