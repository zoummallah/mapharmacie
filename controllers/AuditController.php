<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/utils.php';
require_once __DIR__ . '/../models/AuditLog.php';

verifierAdmin(); // Seul l'administrateur peut voir les logs

$auditModel = new AuditLog();
$filters = [];

if (isset($_GET['id_utilisateur']) && !empty($_GET['id_utilisateur'])) {
    $filters['id_utilisateur'] = $_GET['id_utilisateur'];
}
if (isset($_GET['date_debut']) && !empty($_GET['date_debut'])) {
    $filters['date_debut'] = $_GET['date_debut'];
}
if (isset($_GET['date_fin']) && !empty($_GET['date_fin'])) {
    $filters['date_fin'] = $_GET['date_fin'];
}

$logs = $auditModel->getAll($filters);

require __DIR__ . '/../views/audit/journal.php';
