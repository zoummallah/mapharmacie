<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/utils.php';
require_once __DIR__ . '/../includes/logger.php';
require_once __DIR__ . '/../models/Client.php';

verifierConnexion();

$clientModel = new Client();
$action = isset($_GET['action']) ? sanitizeInput($_GET['action']) : 'index';

if ($action === 'index') {
    $search = $_GET['q'] ?? '';
    $clients = $clientModel->getAll($search);
    require __DIR__ . '/../views/clients/liste.php';

} elseif ($action === 'create') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = [
            'nom' => sanitizeInput($_POST['nom']),
            'telephone' => sanitizeInput($_POST['telephone']),
            'email' => sanitizeInput($_POST['email']),
            'adresse' => sanitizeInput($_POST['adresse']),
            'historique_medical' => sanitizeInput($_POST['historique_medical'])
        ];
        
        if ($clientModel->create($data)) {
            logAction('Création Client', "Création du client: " . $data['nom']);
            addFlashMessage('success', 'Client ajouté avec succès.');
            header('Location: ' . BASE_URL . '/index.php?page=clients');
            exit();
        }
    }
    require __DIR__ . '/../views/clients/form.php';

} elseif ($action === 'edit') {
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    $client = $clientModel->getById($id);
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = [
            'nom' => sanitizeInput($_POST['nom']),
            'telephone' => sanitizeInput($_POST['telephone']),
            'email' => sanitizeInput($_POST['email']),
            'adresse' => sanitizeInput($_POST['adresse']),
            'historique_medical' => sanitizeInput($_POST['historique_medical'])
        ];
        
        if ($clientModel->update($id, $data)) {
            logAction('Modification Client', "Modification du client ID: $id");
            addFlashMessage('success', 'Client mis à jour.');
            header('Location: ' . BASE_URL . '/index.php?page=clients');
            exit();
        }
    }
    require __DIR__ . '/../views/clients/form.php';
}
