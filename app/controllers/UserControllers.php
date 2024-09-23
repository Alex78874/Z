<?php
// app/controllers/UserController.php

class UserController {
    private $userModel;

    public function __construct() {
        require_once __DIR__ . '/../models/User.php';
        $this->userModel = new User();
    }

    public function login() {
        // Code pour afficher le formulaire de connexion ou traiter la soumission
    }

    public function register() {
        // Code pour afficher le formulaire d'inscription ou traiter la soumission
    }

    public function logout() {
        // Code pour déconnecter l'utilisateur
    }
}
