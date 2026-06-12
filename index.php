<?php
/**
 * Routeur principal
 */
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/utils.php';
require_once __DIR__ . '/includes/logger.php';

$page = isset($_GET['page']) ? sanitizeInput($_GET['page']) : 'public';
$action = isset($_GET['action']) ? sanitizeInput($_GET['action']) : 'index';

// Pages accessibles sans connexion
$public_pages = ['public', 'login'];

if (!in_array($page, $public_pages)) {
    verifierConnexion();
}

// Routage basique
switch ($page) {
    case 'login':
        require __DIR__ . '/controllers/AuthController.php';
        break;
    case 'public':
        require __DIR__ . '/controllers/PublicController.php';
        break;
    case 'dashboard':
        require __DIR__ . '/controllers/DashboardController.php';
        break;
    case 'medicaments':
        require __DIR__ . '/controllers/MedicamentController.php';
        break;
    case 'categories':
        require __DIR__ . '/controllers/CategorieController.php';
        break;
    case 'fournisseurs':
        require __DIR__ . '/controllers/FournisseurController.php';
        break;
    case 'lots':
        require __DIR__ . '/controllers/StockController.php'; // Gestion des lots/stocks
        break;
    case 'clients':
        require __DIR__ . '/controllers/ClientController.php';
        break;
    case 'ventes':
        require __DIR__ . '/controllers/VenteController.php';
        break;
    case 'rapports':
        require __DIR__ . '/controllers/RapportController.php';
        break;
    case 'audit':
        require __DIR__ . '/controllers/AuditController.php';
        break;
    case 'utilisateurs':
        require __DIR__ . '/controllers/UtilisateurController.php';
        break;
    case 'logout':
        logout();
        break;
    default:
        // Page 404
        header("HTTP/1.0 404 Not Found");
        echo "Page non trouvée.";
        exit;
}
