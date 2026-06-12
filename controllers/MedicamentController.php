<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/utils.php';
require_once __DIR__ . '/../includes/logger.php';
require_once __DIR__ . '/../models/Medicament.php';
require_once __DIR__ . '/../models/Categorie.php';
require_once __DIR__ . '/../models/Fournisseur.php';

verifierConnexion();

$medicamentModel = new Medicament();
$categorieModel = new Categorie();
$fournisseurModel = new Fournisseur();

$action = isset($_GET['action']) ? sanitizeInput($_GET['action']) : 'index';

if ($action === 'index') {
    $search = $_GET['q'] ?? '';
    $filter_cat = $_GET['categorie'] ?? '';
    
    $filters = [];
    if ($filter_cat) $filters['id_categorie'] = $filter_cat;

    $medicaments = $medicamentModel->getAll($filters, $search);
    $categories = $categorieModel->getAll();
    require __DIR__ . '/../views/medicaments/liste.php';

} elseif ($action === 'create') {
    $errors = [];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = [
            'nom'             => sanitizeInput($_POST['nom']),
            'id_categorie'    => $_POST['id_categorie'],
            'principe_actif'  => sanitizeInput($_POST['principe_actif'] ?? ''),
            'dosage'          => sanitizeInput($_POST['dosage'] ?? ''),
            'forme'           => sanitizeInput($_POST['forme'] ?? ''),
            'prix_achat'      => $_POST['prix_achat'],
            'prix_vente'      => $_POST['prix_vente'],
            'stock_minimum'   => $_POST['stock_minimum'] ?? 5,
            'date_fabrication'=> $_POST['date_fabrication'] ?? '',
            'date_expiration' => $_POST['date_expiration'],
            'lot_numero'      => sanitizeInput($_POST['lot_numero'] ?? ''),
            'fournisseurs'    => isset($_POST['fournisseurs']) ? $_POST['fournisseurs'] : []
        ];

        // --- Validations ---
        if (empty($data['nom'])) $errors[] = 'Le nom du médicament est obligatoire.';
        if (empty($data['id_categorie'])) $errors[] = 'La catégorie est obligatoire.';
        if (!is_numeric($data['prix_achat']) || $data['prix_achat'] < 0) $errors[] = 'Le prix d\'achat doit être un nombre positif.';
        if (!is_numeric($data['prix_vente']) || $data['prix_vente'] < 0) $errors[] = 'Le prix de vente doit être un nombre positif.';
        if (floatval($data['prix_vente']) < floatval($data['prix_achat'])) $errors[] = 'Le prix de vente ne peut pas être inférieur au prix d\'achat.';
        if (empty($data['date_expiration'])) $errors[] = 'La date d\'expiration est obligatoire.';
        if (!empty($data['date_fabrication']) && !empty($data['date_expiration']) && $data['date_fabrication'] >= $data['date_expiration']) {
            $errors[] = 'La date d\'expiration doit être postérieure à la date de fabrication.';
        }

        if (empty($errors)) {
            if ($medicamentModel->create($data)) {
                logAction('Création Médicament', "Création du médicament: " . $data['nom']);
                addFlashMessage('success', 'Médicament ajouté avec succès.');
                header('Location: ' . BASE_URL . '/index.php?page=medicaments');
                exit();
            } else {
                $errors[] = 'Erreur lors de l\'enregistrement en base de données.';
            }
        }

        // En cas d'erreur, repeupler le formulaire avec les données saisies
        if (!empty($errors)) {
            foreach ($errors as $err) {
                addFlashMessage('danger', $err);
            }
            $medicament = $data; // repopule le form
        }
    }
    
    $categories = $categorieModel->getAll();
    $fournisseurs = $fournisseurModel->getAll();
    require __DIR__ . '/../views/medicaments/form.php';


} elseif ($action === 'edit') {
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    $medicament = $medicamentModel->getById($id);
    
    if (!$medicament) {
        addFlashMessage('danger', 'Médicament introuvable.');
        header('Location: ' . BASE_URL . '/index.php?page=medicaments');
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $errors = [];
        $data = [
            'nom'             => sanitizeInput($_POST['nom']),
            'id_categorie'    => $_POST['id_categorie'],
            'principe_actif'  => sanitizeInput($_POST['principe_actif'] ?? ''),
            'dosage'          => sanitizeInput($_POST['dosage'] ?? ''),
            'forme'           => sanitizeInput($_POST['forme'] ?? ''),
            'prix_achat'      => $_POST['prix_achat'],
            'prix_vente'      => $_POST['prix_vente'],
            'stock_minimum'   => $_POST['stock_minimum'] ?? 5,
            'date_fabrication'=> $_POST['date_fabrication'] ?? '',
            'date_expiration' => $_POST['date_expiration'],
            'lot_numero'      => sanitizeInput($_POST['lot_numero'] ?? ''),
            'fournisseurs'    => isset($_POST['fournisseurs']) ? $_POST['fournisseurs'] : []
        ];

        // --- Validations ---
        if (empty($data['nom'])) $errors[] = 'Le nom du médicament est obligatoire.';
        if (empty($data['id_categorie'])) $errors[] = 'La catégorie est obligatoire.';
        if (!is_numeric($data['prix_achat']) || $data['prix_achat'] < 0) $errors[] = 'Le prix d\'achat doit être un nombre positif.';
        if (!is_numeric($data['prix_vente']) || $data['prix_vente'] < 0) $errors[] = 'Le prix de vente doit être un nombre positif.';
        if (floatval($data['prix_vente']) < floatval($data['prix_achat'])) $errors[] = 'Le prix de vente ne peut pas être inférieur au prix d\'achat.';
        if (empty($data['date_expiration'])) $errors[] = 'La date d\'expiration est obligatoire.';
        if (!empty($data['date_fabrication']) && !empty($data['date_expiration']) && $data['date_fabrication'] >= $data['date_expiration']) {
            $errors[] = 'La date d\'expiration doit être postérieure à la date de fabrication.';
        }

        if (empty($errors)) {
            if ($medicamentModel->update($id, $data)) {
                logAction('Modification Médicament', "Modification du médicament ID: $id");
                addFlashMessage('success', 'Médicament mis à jour avec succès.');
                header('Location: ' . BASE_URL . '/index.php?page=medicaments');
                exit();
            } else {
                $errors[] = 'Erreur lors de la mise à jour en base de données.';
            }
        }

        if (!empty($errors)) {
            foreach ($errors as $err) addFlashMessage('danger', $err);
            // Repeupler avec les nouvelles valeurs saisies (mais conserver l'id)
            $data['id'] = $id;
            $data['fournisseurs'] = $data['fournisseurs'];
            $medicament = array_merge($medicament, $data);
        }
    }
    
    $categories = $categorieModel->getAll();
    $fournisseurs = $fournisseurModel->getAll();
    require __DIR__ . '/../views/medicaments/form.php';


} elseif ($action === 'delete') {
    verifierAdmin(); // Seul l'admin peut supprimer
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    
    if ($medicamentModel->delete($id)) {
        logAction('Suppression Médicament', "Désactivation du médicament ID: $id");
        addFlashMessage('success', 'Médicament supprimé avec succès.');
    } else {
        addFlashMessage('danger', 'Erreur lors de la suppression.');
    }
    header('Location: ' . BASE_URL . '/index.php?page=medicaments');
    exit();
}
