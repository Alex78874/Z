<?php

class User extends Model {
    protected $table = 'User';

    public function __construct() {
        parent::__construct(); // Appel au constructeur de la classe parente
    }

    public function createUser($username, $email, $hashedPassword): bool {
        // Utilisation de la méthode `create` de la classe parente pour insérer l'utilisateur
        return $this->create([
            'username' => $username,
            'email' => $email,
            'password' => $hashedPassword
        ]);
    }

    public function getUserById($id): mixed {
        // Utilisation de la méthode `getById` de la classe parente pour obtenir un utilisateur par son ID
        return $this->getById($id);
    }

    public function getUserByEmail($email): mixed {
        // Requête personnalisée pour obtenir un utilisateur par son email
        $stmt = $this->pdo->prepare('SELECT * FROM User WHERE email = :email');
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }

    public function isUsernameAvailable($username): bool {
        // Vérifie si le nom d'utilisateur est disponible
        $stmt = $this->pdo->prepare('SELECT * FROM User WHERE username = :username');
        $stmt->execute(['username' => $username]);
        return !$stmt->fetch();
    }

    public function updateUser($id, $data): bool {
        // Utilisation de la méthode `update` de la classe parente pour mettre à jour un utilisateur
        return $this->update($id, $data);
    }

    public function deleteUser($id): bool {
        // Utilisation de la méthode `delete` de la classe parente pour supprimer un utilisateur
        return $this->delete($id);
    }

    // Autres méthodes spécifiques à User peuvent être ajoutées ici
}
