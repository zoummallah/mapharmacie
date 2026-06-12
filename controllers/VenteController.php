<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/utils.php';
require_once __DIR__ . '/../includes/logger.php';
require_once __DIR__ . '/../models/Vente.php';
require_once __DIR__ . '/../models/Lot.php';
require_once __DIR__ . '/../models/Medicament.php';
require_once __DIR__ . '/../models/Client.php';
require_once __DIR__ . '/../models/Ordonnance.php';

verifierConnexion();

$venteModel = new Vente();
$lotModel = new Lot();
$medicamentModel = new Medicament();
$clientModel = new Client();
$ordonnanceModel = new Ordonnance();

$action = isset($_GET['action']) ? sanitizeInput($_GET['action']) : 'pos';

// Initialisation du panier en session
if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

if ($action === 'pos') {
    $lots = $lotModel->getLotsActifs();
    $clients = $clientModel->getAll();
    require __DIR__ . '/../views/ventes/pos.php';

} elseif ($action === 'add_panier') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id_lot = (int)$_POST['id_lot'];
        $quantite = (int)$_POST['quantite'];

        $lot = $lotModel->getById($id_lot);
        if ($lot && $lot['quantite'] >= $quantite) {
            $item_id = $id_lot; // Identifiant unique dans le panier
            
            if (isset($_SESSION['panier'][$item_id])) {
                $nouvelle_qte = $_SESSION['panier'][$item_id]['quantite'] + $quantite;
                if ($nouvelle_qte <= $lot['quantite']) {
                    $_SESSION['panier'][$item_id]['quantite'] = $nouvelle_qte;
                } else {
                    addFlashMessage('danger', 'Quantité maximale en stock atteinte.');
                }
            } else {
                $_SESSION['panier'][$item_id] = [
                    'id_lot' => $id_lot,
                    'id_medicament' => $lot['id_medicament'],
                    'nom' => $lot['medicament_nom'],
                    'numero_lot' => $lot['numero_lot'],
                    'prix_unitaire' => $lot['prix_vente'],
                    'quantite' => $quantite
                ];
            }
        } else {
            addFlashMessage('danger', 'Stock insuffisant pour ce lot.');
        }
    }
    header('Location: ' . BASE_URL . '/index.php?page=ventes&action=pos');
    exit();

} elseif ($action === 'remove_panier') {
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    if (isset($_SESSION['panier'][$id])) {
        unset($_SESSION['panier'][$id]);
    }
    header('Location: ' . BASE_URL . '/index.php?page=ventes&action=pos');
    exit();

} elseif ($action === 'clear_panier') {
    $_SESSION['panier'] = [];
    header('Location: ' . BASE_URL . '/index.php?page=ventes&action=pos');
    exit();

} elseif ($action === 'valider') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (empty($_SESSION['panier'])) {
            addFlashMessage('warning', 'Le panier est vide.');
            header('Location: ' . BASE_URL . '/index.php?page=ventes&action=pos');
            exit();
        }

        $id_client = null;
        $type_client = $_POST['type_client'] ?? 'anonyme';
        
        if ($type_client === 'existant') {
            $id_client = !empty($_POST['id_client']) ? (int)$_POST['id_client'] : null;
        } elseif ($type_client === 'nouveau') {
            $nom = sanitizeInput($_POST['nouveau_client_nom'] ?? '');
            $tel = sanitizeInput($_POST['nouveau_client_telephone'] ?? '');
            if (!empty($nom)) {
                $clientModel->create(['nom' => $nom, 'telephone' => $tel, 'email' => '', 'adresse' => '']);
                $id_client = Database::getInstance()->getConnection()->lastInsertId();
            }
        }

        $id_ordonnance = null;
        $type_ordonnance = $_POST['type_ordonnance'] ?? 'sans';

        // Gestion de l'ordonnance
        if ($type_ordonnance === 'avec' && !empty($_POST['numero_ordonnance']) && !empty($_POST['medecin_prescripteur'])) {
            $fichier_chemin = null;
            if (isset($_FILES['fichier_ordonnance']) && $_FILES['fichier_ordonnance']['error'] === UPLOAD_ERR_OK) {
                if (!is_dir(ORDONNANCES_DIR)) {
                    mkdir(ORDONNANCES_DIR, 0777, true);
                }
                $ext = pathinfo($_FILES['fichier_ordonnance']['name'], PATHINFO_EXTENSION);
                $fichier_chemin = uniqid() . '.' . $ext;
                move_uploaded_file($_FILES['fichier_ordonnance']['tmp_name'], ORDONNANCES_DIR . $fichier_chemin);
            }

            $ordData = [
                'numero_ordonnance' => sanitizeInput($_POST['numero_ordonnance']),
                'date_emission' => $_POST['date_emission'],
                'date_validite' => !empty($_POST['date_validite']) ? $_POST['date_validite'] : null,
                'medecin_prescripteur' => sanitizeInput($_POST['medecin_prescripteur']),
                'id_client' => $id_client, // Peut désormais être null
                'fichier_ordonnance' => $fichier_chemin
            ];
            $ordonnanceModel->create($ordData);
            $id_ordonnance = Database::getInstance()->getConnection()->lastInsertId();
        }

        $generer_facture = isset($_POST['generer_facture']) ? 1 : 0;
        
        $id_vente = $venteModel->validerVente($_SESSION['panier'], $_SESSION['user_id'], $id_client, $id_ordonnance);

        if ($id_vente) {
            $_SESSION['panier'] = []; // Vider le panier
            logAction('Vente', "Vente ID: $id_vente validée.");
            addFlashMessage('success', 'Vente enregistrée avec succès.');
            
            if ($generer_facture) {
                header('Location: ' . BASE_URL . '/index.php?page=ventes&action=pos&download_pdf=' . $id_vente);
            } else {
                header('Location: ' . BASE_URL . '/index.php?page=ventes&action=pos');
            }
            exit();
        } else {
            addFlashMessage('danger', 'Erreur lors de la validation de la vente (Vérifiez les stocks).');
            header('Location: ' . BASE_URL . '/index.php?page=ventes&action=pos');
            exit();
        }
    }

} elseif ($action === 'historique') {
    $filters = [];
    if (!empty($_GET['date_debut'])) $filters['date_debut'] = $_GET['date_debut'];
    if (!empty($_GET['date_fin'])) $filters['date_fin'] = $_GET['date_fin'];
    
    $ventes = $venteModel->getHistorique($filters);
    require __DIR__ . '/../views/ventes/historique.php';

} elseif ($action === 'details') {
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    $vente = $venteModel->getById($id);
    $lignes = $venteModel->getDetails($id);
    require __DIR__ . '/../views/ventes/details.php';

} elseif ($action === 'facture') {
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    $vente = $venteModel->getById($id);
    $lignes = $venteModel->getDetails($id);

    if (!$vente) {
        http_response_code(404);
        die('Vente introuvable.');
    }

    // Désactiver l'affichage des erreurs PHP : tout warning/notice qui
    // s'échapperait corromprait le flux binaire PDF et provoquerait
    // l'erreur FPDF "Some data has already been output".
    @ini_set('display_errors', '0');
    @ini_set('display_startup_errors', '0');

    // Vider tous les tampons ouverts par le routeur/session avant le PDF
    while (ob_get_level() > 0) {
        ob_end_clean();
    }

    // Génération PDF via FPDF — la vue gère elle-même l'envoi des headers
    require __DIR__ . '/../views/ventes/facture_pdf.php';
    exit(); // Empêche tout output HTML résiduel après le PDF
}
