<?php
/**
 * Fonctions de gestion de l'authentification
 */
require_once __DIR__ . '/../config/database.php';

function estConnecte() {
    return isset($_SESSION['user_id']);
}

function estAdmin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

function verifierConnexion() {
    if (!estConnecte()) {
        header('Location: ' . BASE_URL . '/index.php?page=login');
        exit();
    }
}

function verifierAdmin() {
    verifierConnexion();
    if (!estAdmin()) {
        header('Location: ' . BASE_URL . '/index.php?page=dashboard&error=acces_refuse');
        exit();
    }
}

function login($email, $password) {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare("SELECT * FROM utilisateur WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['mot_de_passe'])) {
        if ((int)$user['statut'] !== 1) {
            return 'inactive';
        }
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_nom'] = $user['nom'];
        $_SESSION['user_prenom'] = $user['prenom'];
        $_SESSION['user_role'] = $user['role'];
        return 'success';
    }
    return 'failed';
}

function logout() {
    session_unset();
    session_destroy();
    header('Location: ' . BASE_URL . '/index.php?page=login');
    exit();
}
