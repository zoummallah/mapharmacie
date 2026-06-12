<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/logger.php';

if (estConnecte() && $action !== 'logout') {
    header('Location: ' . BASE_URL . '/index.php?page=dashboard');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitizeInput($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = "Veuillez remplir tous les champs.";
    } else {
        $loginResult = login($email, $password);
        if ($loginResult === 'success') {
            logAction('Connexion réussie', "Utilisateur connecté: $email");
            header('Location: ' . BASE_URL . '/index.php?page=dashboard');
            exit();
        } else {
            // Pour l'audit, on utilise un ID null puisqu'il n'est pas connecté
            $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare("INSERT INTO historique_action (id_utilisateur, action, entite, details) VALUES (NULL, 'Echec Connexion', 'system', ?)");
            
            if ($loginResult === 'inactive') {
                $stmt->execute(["Tentative de connexion (compte désactivé) pour email: $email"]);
                $error = "Votre compte a été désactivé. Veuillez contacter l'administrateur.";
            } else {
                $stmt->execute(["Tentative échouée pour email: $email"]);
                $error = "Email ou mot de passe incorrect.";
            }
        }
    }
}

require __DIR__ . '/../views/auth/login.php';
