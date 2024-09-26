<?php

class User {
    private $pdo;

    public function __construct() {
        $this->pdo = getPDO();
    }

    public function createUser($username, $email, $hashedPassword): bool {
        $stmt = $this->pdo->prepare(query: 'INSERT INTO User (username, email, password) VALUES (:username, :email, :password)');
        $stmt->execute(params: [
            'username' => $username,
            'email' => $email,
            'password' => $hashedPassword
        ]);
        return $stmt->rowCount() > 0;
    }

    public function getUserById($id): mixed {
        // Code pour récupérer un utilisateur par son ID
        $stmt = $this->pdo->prepare(query: 'SELECT * FROM User WHERE id = :id');
        $stmt->execute(params: ['id' => $id]);
        return $stmt->fetch();
    }

    public function getUserByEmail($email): mixed {
        // Code pour récupérer un utilisateur par son email
        $stmt = $this->pdo->prepare(query: 'SELECT * FROM User WHERE email = :email');
        $stmt->execute(params: ['email' => $email]);
        return $stmt->fetch();
    }

    public function isUsernameAvailable($username): bool {
        // Code pour vérifier si un nom d'utilisateur est disponible
        $stmt = $this->pdo->prepare(query: 'SELECT * FROM User WHERE username = :username');
        $stmt->execute(params: ['username' => $username]);
        return !$stmt->fetch();
    }

    public function update($id, $data): bool {
        // Code pour mettre à jour un utilisateur
        $stmt = $this->pdo->prepare(query: 'UPDATE User SET name = :name, email = :email WHERE id = :id');
        $stmt->execute(params: ['name' => $data['name'], 'email' => $data['email'], 'id' => $id]);
        return $stmt->rowCount() > 0;
    }

    public function delete($id): bool {
        // Code pour supprimer un utilisateur
        $stmt = $this->pdo->prepare(query: 'DELETE FROM User WHERE id = :id');
        $stmt->execute(params: ['id' => $id]);
        return $stmt->rowCount() > 0;
    }

    // Autres méthodes pour interagir avec la table des utilisateurs
}
