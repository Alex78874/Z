<?php

class Admin extends User {
    protected $table = 'Admin';

    public function __construct() {
        parent::__construct(); // Appel au constructeur de la classe parente
    }

    public function createAdmin($username, $email, $hashedPassword): bool {
        // Utilisation de la méthode `create` de la classe parente pour insérer l'utilisateur
        return $this->create([
            'username' => $username,
            'email' => $email,
            'password' => $hashedPassword
        ]);
    }

    public function getAdminById($id): mixed {
        // Utilisation de la méthode `getById` de la classe parente pour obtenir un utilisateur par son ID
        return $this->getById($id);
    }

    public function getAdminByEmail($email): mixed {
        // Requête personnalisée pour obtenir un utilisateur par son email
        $stmt = $this->pdo->prepare('SELECT * FROM Admin WHERE email = :email');
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }

    public function isAdminUsernameAvailable($username): bool {
        // Vérifie si le nom d'utilisateur est disponible
        $stmt = $this->pdo->prepare('SELECT * FROM Admin WHERE username = :username');
        $stmt->execute(['username' => $username]);
        return !$stmt->fetch();
    }

    public function updateAdmin($id, $data): bool {
        // Utilisation de la méthode `update` de la classe parente pour mettre à jour un utilisateur
        return $this->update($id, $data);
    }

    public function deleteAdmin($id): bool {
        // Utilisation de la méthode `delete` de la classe parente pour supprimer un utilisateur
        return $this->delete($id);
    }

    // Autres méthodes spécifiques à Admin peuvent être ajoutées ici
}
