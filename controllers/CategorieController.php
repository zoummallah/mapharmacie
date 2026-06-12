<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/utils.php';
require_once __DIR__ . '/../includes/logger.php';
require_once __DIR__ . '/../models/Categorie.php';

verifierConnexion();

$categorieModel = new Categorie();
$action = isset($_GET['action']) ? sanitizeInput($_GET['action']) : 'index';

if ($action === 'index') {
    $categories = $categorieModel->getAll();
    require __DIR__ . '/../views/categories/liste.php';

} elseif ($action === 'create') {
    verifierAdmin();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = [
            'nom' => sanitizeInput($_POST['nom']),
            'description' => sanitizeInput($_POST['description'])
        ];
        
        if ($categorieModel->create($data)) {
            logAction('Création Catégorie', "Catégorie: " . $data['nom']);
            addFlashMessage('success', 'Catégorie ajoutée.');
            header('Location: ' . BASE_URL . '/index.php?page=categories');
            exit();
        }
    }
    require __DIR__ . '/../views/categories/form.php';

} elseif ($action === 'edit') {
    verifierAdmin();
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    $categorie = $categorieModel->getById($id);
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = [
            'nom' => sanitizeInput($_POST['nom']),
            'description' => sanitizeInput($_POST['description'])
        ];
        
        if ($categorieModel->update($id, $data)) {
            logAction('Modification Catégorie', "Catégorie ID: $id");
            addFlashMessage('success', 'Catégorie mise à jour.');
            header('Location: ' . BASE_URL . '/index.php?page=categories');
            exit();
        }
    }
    require __DIR__ . '/../views/categories/form.php';

} elseif ($action === 'delete') {
    verifierAdmin();
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    if ($categorieModel->delete($id)) {
        logAction('Suppression Catégorie', "Catégorie ID: $id");
        addFlashMessage('success', 'Catégorie supprimée.');
    } else {
        addFlashMessage('danger', 'Impossible de supprimer cette catégorie (médicaments liés).');
    }
    header('Location: ' . BASE_URL . '/index.php?page=categories');
    exit();
}
