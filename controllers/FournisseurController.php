<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/utils.php';
require_once __DIR__ . '/../includes/logger.php';
require_once __DIR__ . '/../models/Fournisseur.php';

verifierConnexion();

$fournisseurModel = new Fournisseur();
$action = isset($_GET['action']) ? sanitizeInput($_GET['action']) : 'index';

if ($action === 'index') {
    $fournisseurs = $fournisseurModel->getAll();
    require __DIR__ . '/../views/fournisseurs/liste.php';

} elseif ($action === 'create') {
    verifierAdmin(); // Seul l'admin peut ajouter
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = [
            'nom' => sanitizeInput($_POST['nom']),
            'contact_nom' => sanitizeInput($_POST['contact_nom']),
            'adresse' => sanitizeInput($_POST['adresse']),
            'email' => sanitizeInput($_POST['email']),
            'telephone' => sanitizeInput($_POST['telephone'])
        ];
        
        if ($fournisseurModel->create($data)) {
            logAction('Création Fournisseur', "Fournisseur: " . $data['nom']);
            addFlashMessage('success', 'Fournisseur ajouté.');
            header('Location: ' . BASE_URL . '/index.php?page=fournisseurs');
            exit();
        }
    }
    require __DIR__ . '/../views/fournisseurs/form.php';

} elseif ($action === 'edit') {
    verifierAdmin();
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    $fournisseur = $fournisseurModel->getById($id);
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = [
            'nom' => sanitizeInput($_POST['nom']),
            'contact_nom' => sanitizeInput($_POST['contact_nom']),
            'adresse' => sanitizeInput($_POST['adresse']),
            'email' => sanitizeInput($_POST['email']),
            'telephone' => sanitizeInput($_POST['telephone'])
        ];
        
        if ($fournisseurModel->update($id, $data)) {
            logAction('Modification Fournisseur', "Fournisseur ID: $id");
            addFlashMessage('success', 'Fournisseur mis à jour.');
            header('Location: ' . BASE_URL . '/index.php?page=fournisseurs');
            exit();
        }
    }
    require __DIR__ . '/../views/fournisseurs/form.php';

} elseif ($action === 'delete') {
    verifierAdmin();
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    if ($fournisseurModel->delete($id)) {
        logAction('Suppression Fournisseur', "Fournisseur ID: $id");
        addFlashMessage('success', 'Fournisseur supprimé.');
    } else {
        addFlashMessage('danger', 'Impossible de supprimer ce fournisseur (médicaments liés).');
    }
    header('Location: ' . BASE_URL . '/index.php?page=fournisseurs');
    exit();
}
