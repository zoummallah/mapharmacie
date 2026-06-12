<?php
/**
 * Contrôleur Utilisateur pour la gestion des comptes
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/utils.php';
require_once __DIR__ . '/../includes/logger.php';
require_once __DIR__ . '/../models/Utilisateur.php';

// Sécurité : réservé uniquement aux administrateurs
verifierAdmin();

$utilisateurModel = new Utilisateur();
$action = isset($_GET['action']) ? sanitizeInput($_GET['action']) : 'index';

if ($action === 'index') {
    $utilisateurs = $utilisateurModel->getAll();
    require __DIR__ . '/../views/utilisateurs/liste.php';

} elseif ($action === 'create') {
    $error = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nom = sanitizeInput($_POST['nom'] ?? '');
        $prenom = sanitizeInput($_POST['prenom'] ?? '');
        $email = sanitizeInput($_POST['email'] ?? '');
        $password = $_POST['mot_de_passe'] ?? '';
        $role = sanitizeInput($_POST['role'] ?? 'pharmacien');

        if (empty($nom) || empty($prenom) || empty($email) || empty($password)) {
            $error = "Veuillez remplir tous les champs obligatoires.";
        } else {
            try {
                $data = [
                    'nom' => $nom,
                    'prenom' => $prenom,
                    'email' => $email,
                    'mot_de_passe' => $password,
                    'role' => $role
                ];
                if ($utilisateurModel->create($data)) {
                    logAction('Création Utilisateur', "Utilisateur créé: $email ($role)");
                    addFlashMessage('success', "L'utilisateur a été créé avec succès.");
                    header('Location: ' . BASE_URL . '/index.php?page=utilisateurs');
                    exit();
                } else {
                    $error = "Une erreur est survenue lors de la création.";
                }
            } catch (PDOException $e) {
                if ($e->getCode() == 23000) {
                    $error = "Cette adresse email est déjà utilisée.";
                } else {
                    $error = "Erreur de base de données : " . $e->getMessage();
                }
            }
        }
    }
    require __DIR__ . '/../views/utilisateurs/form.php';

} elseif ($action === 'edit') {
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    $utilisateur = $utilisateurModel->getById($id);
    if (!$utilisateur) {
        addFlashMessage('danger', "Utilisateur introuvable.");
        header('Location: ' . BASE_URL . '/index.php?page=utilisateurs');
        exit();
    }

    $isCurrentUser = ($id === (int)$_SESSION['user_id']);
    $error = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nom = sanitizeInput($_POST['nom'] ?? '');
        $prenom = sanitizeInput($_POST['prenom'] ?? '');
        $email = sanitizeInput($_POST['email'] ?? '');
        $role = sanitizeInput($_POST['role'] ?? 'pharmacien');
        $statut = isset($_POST['statut']) ? (int)$_POST['statut'] : 1;
        $password = $_POST['mot_de_passe'] ?? '';
        $ancien_password = $_POST['ancien_mot_de_passe'] ?? '';

        if (empty($nom) || empty($prenom) || empty($email)) {
            $error = "Veuillez remplir tous les champs obligatoires.";
        } else {
            // Empêcher l'admin de se désactiver lui-même ou de changer son propre rôle
            if ($isCurrentUser && ($statut === 0 || $role !== 'admin')) {
                $error = "Vous ne pouvez pas désactiver votre propre compte ou modifier votre rôle d'administrateur.";
            } else {
                $password_valide = true;
                if (!$isCurrentUser && !empty($password)) {
                    if (empty($ancien_password)) {
                        $error = "L'ancien mot de passe du collaborateur est requis pour modifier son mot de passe.";
                        $password_valide = false;
                    } else {
                        $db = Database::getInstance()->getConnection();
                        $stmt = $db->prepare("SELECT mot_de_passe FROM utilisateur WHERE id = ?");
                        $stmt->execute([$id]);
                        $hashed_password = $stmt->fetchColumn();

                        if (!$hashed_password || !password_verify($ancien_password, $hashed_password)) {
                            $error = "L'ancien mot de passe fourni pour ce collaborateur est incorrect.";
                            $password_valide = false;
                        }
                    }
                }

                if ($password_valide) {
                    try {
                        $data = [
                            'nom' => $nom,
                            'prenom' => $prenom,
                            'email' => $email,
                            'role' => $role,
                            'statut' => $statut,
                            'mot_de_passe' => $password
                        ];
                        if ($utilisateurModel->update($id, $data)) {
                            logAction('Modification Utilisateur', "Utilisateur mis à jour: $email (ID: $id)");
                            addFlashMessage('success', "L'utilisateur a été mis à jour avec succès.");
                            header('Location: ' . BASE_URL . '/index.php?page=utilisateurs');
                            exit();
                        } else {
                            $error = "Une erreur est survenue lors de la mise à jour.";
                        }
                    } catch (PDOException $e) {
                        if ($e->getCode() == 23000) {
                            $error = "Cette adresse email est déjà utilisée par un autre compte.";
                        } else {
                            $error = "Erreur de base de données : " . $e->getMessage();
                        }
                    }
                }
            }
        }
    }
    require __DIR__ . '/../views/utilisateurs/form.php';

} elseif ($action === 'delete') {
    // Désactiver un utilisateur
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    if ($id === (int)$_SESSION['user_id']) {
        addFlashMessage('danger', "Vous ne pouvez pas désactiver votre propre compte.");
    } else {
        if ($utilisateurModel->delete($id)) {
            logAction('Désactivation Utilisateur', "Utilisateur désactivé (ID: $id)");
            addFlashMessage('success', "L'utilisateur a été désactivé.");
        } else {
            addFlashMessage('danger', "Impossible de désactiver l'utilisateur.");
        }
    }
    header('Location: ' . BASE_URL . '/index.php?page=utilisateurs');
    exit();

} elseif ($action === 'activate') {
    // Activer un utilisateur
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    
    // On va charger le modèle et faire l'update
    $user = $utilisateurModel->getById($id);
    if ($user) {
        // Option 1: On peut modifier le modèle Utilisateur pour ajouter activate()
        // Option 2: Utiliser update() existant
        $data = [
            'nom' => $user['nom'],
            'prenom' => $user['prenom'],
            'email' => $user['email'],
            'role' => $user['role'],
            'statut' => 1,
            'mot_de_passe' => '' // pas de changement
        ];
        
        if ($utilisateurModel->update($id, $data)) {
            logAction('Activation Utilisateur', "Utilisateur réactivé (ID: $id)");
            addFlashMessage('success', "L'utilisateur a été réactivé.");
        } else {
            addFlashMessage('danger', "Impossible de réactiver l'utilisateur.");
        }
    } else {
        addFlashMessage('danger', "Utilisateur introuvable.");
    }
    header('Location: ' . BASE_URL . '/index.php?page=utilisateurs');
    exit();
}
