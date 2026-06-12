<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/utils.php';
require_once __DIR__ . '/../includes/logger.php';
require_once __DIR__ . '/../models/Lot.php';
require_once __DIR__ . '/../models/MouvementStock.php';
require_once __DIR__ . '/../models/Medicament.php';

verifierConnexion();

$lotModel = new Lot();
$mouvementModel = new MouvementStock();
$medicamentModel = new Medicament();

$action = isset($_GET['action']) ? sanitizeInput($_GET['action']) : 'index';

if ($action === 'index') {
    // Liste des lots actifs
    $lotModel->checkPerimes(); // Mise à jour auto des statuts
    $lots = $lotModel->getLotsActifs();
    $lotsPerimesCount = count($lotModel->getLotsPerimes());
    require __DIR__ . '/../views/stocks/lots.php';

} elseif ($action === 'historique') {
    $filters = [];
    if (!empty($_GET['type'])) $filters['type'] = sanitizeInput($_GET['type']);
    if (!empty($_GET['date_debut'])) $filters['date_debut'] = $_GET['date_debut'];
    if (!empty($_GET['date_fin'])) $filters['date_fin'] = $_GET['date_fin'];
    
    $mouvements = $mouvementModel->getHistorique($filters);
    require __DIR__ . '/../views/stocks/historique.php';

} elseif ($action === 'entree') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id_medicament = $_POST['id_medicament'];
        $quantite = (int)$_POST['quantite'];
        $motif = sanitizeInput($_POST['motif']);
        
        // Optionnel : Nouveau lot
        $nouveau_lot = isset($_POST['nouveau_lot']) && $_POST['nouveau_lot'] === '1';
        $id_lot = null;

        try {
            if ($nouveau_lot) {
                $data_lot = [
                    'id_medicament' => $id_medicament,
                    'numero_lot' => sanitizeInput($_POST['numero_lot']),
                    'date_fabrication' => $_POST['date_fabrication'],
                    'date_expiration' => $_POST['date_expiration'],
                    'quantite' => 0, // Sera incrémenté par le mouvement (trigger)
                    'prix_achat' => $_POST['prix_achat'],
                    'prix_vente' => $_POST['prix_vente']
                ];
                $lotModel->create($data_lot);
                $id_lot = Database::getInstance()->getConnection()->lastInsertId();
            } else {
                $id_lot = !empty($_POST['id_lot']) ? $_POST['id_lot'] : null;
                if (!$id_lot) {
                    throw new Exception("Le lot de destination est obligatoire.");
                }
            }

            if ($mouvementModel->enregistrer($id_medicament, 'entree', $quantite, $motif, $_SESSION['user_id'], $id_lot)) {
                logAction('Entrée Stock', "Médicament ID: $id_medicament, Quantité: $quantite");
                addFlashMessage('success', 'Entrée de stock enregistrée avec succès.');
                header('Location: ' . BASE_URL . '/index.php?page=lots&action=historique');
                exit();
            }
        } catch (Exception $e) {
            addFlashMessage('danger', 'Erreur: ' . $e->getMessage());
        }
    }
    $medicaments = $medicamentModel->getAll();
    $lots = $lotModel->getLotsActifs();
    require __DIR__ . '/../views/stocks/entree.php';

} elseif ($action === 'sortie') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id_medicament = $_POST['id_medicament'];
        $id_lot = !empty($_POST['id_lot']) ? $_POST['id_lot'] : null;

        if (!$id_lot) {
            addFlashMessage('danger', 'Le lot de prélèvement est obligatoire.');
        } else {
            $quantite = (int)$_POST['quantite'];
            $motif = sanitizeInput($_POST['motif']);
            $type = $_POST['type']; // 'sortie' ou 'retour_fournisseur'

            // Vérification disponibilité
            $lotInfo = $lotModel->getById($id_lot);
            $stock_dispo = $lotInfo['quantite'] ?? 0;

            if ($quantite > $stock_dispo) {
                addFlashMessage('danger', 'Quantité en stock insuffisante pour cette sortie.');
            } else {
                if ($mouvementModel->enregistrer($id_medicament, $type, $quantite, $motif, $_SESSION['user_id'], $id_lot)) {
                    logAction("Sortie Stock ($type)", "Médicament ID: $id_medicament, Quantité: $quantite");
                    addFlashMessage('success', 'Sortie de stock enregistrée.');
                    header('Location: ' . BASE_URL . '/index.php?page=lots&action=historique');
                    exit();
                }
            }
        }
    }
    $medicaments = $medicamentModel->getAll();
    $lots = $lotModel->getLotsActifs();
    require __DIR__ . '/../views/stocks/sortie.php';

} elseif ($action === 'perimes') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Seuls les administrateurs ont le droit de mettre au rebut
        if (!estAdmin()) {
            addFlashMessage('danger', 'Accès interdit : Seul un administrateur peut mettre des lots au rebut.');
            header('Location: ' . BASE_URL . '/index.php?page=lots&action=perimes');
            exit();
        }

        $id_lot = isset($_POST['id_lot']) ? (int)$_POST['id_lot'] : 0;
        $lotInfo = $lotModel->getById($id_lot);

        if (!$lotInfo) {
            addFlashMessage('danger', 'Lot introuvable.');
        } elseif ($lotInfo['quantite'] <= 0) {
            addFlashMessage('danger', 'Le stock de ce lot est déjà vide.');
        } else {
            $quantite = $lotInfo['quantite'];
            $id_medicament = $lotInfo['id_medicament'];
            $motif = 'Mise au rebut : Lot périmé (' . $lotInfo['numero_lot'] . ')';

            // Enregistrer le mouvement de stock de type 'rebut'
            if ($mouvementModel->enregistrer($id_medicament, 'rebut', $quantite, $motif, $_SESSION['user_id'], $id_lot)) {
                // Assurer que le statut reste 'perime' (le trigger mysql passe en 'epuise' par défaut)
                $lotModel->viderLot($id_lot);

                logAction('Mise au rebut', "Médicament: {$lotInfo['medicament_nom']}, Lot: {$lotInfo['numero_lot']}, Quantité: $quantite");
                addFlashMessage('success', "Le lot {$lotInfo['numero_lot']} de {$lotInfo['medicament_nom']} ({$quantite} unités) a été mis au rebut.");
            } else {
                addFlashMessage('danger', 'Une erreur est survenue lors de la mise au rebut.');
            }
        }
        header('Location: ' . BASE_URL . '/index.php?page=lots&action=perimes');
        exit();
    }

    $lots = $lotModel->getLotsPerimes();
    require __DIR__ . '/../views/stocks/lots_perimes.php';
}
