<?php
require_once __DIR__ . '/../models/Medicament.php';

$medicamentModel = new Medicament();
$resultats = [];
$search = '';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['q'])) {
    $search = sanitizeInput($_GET['q']);
    if (!empty($search)) {
        $resultats = $medicamentModel->checkDisponibilite($search);
    }
}

require __DIR__ . '/../views/public/index.php';
