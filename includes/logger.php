<?php
/**
 * Enregistrement des logs d'audit
 */
require_once __DIR__ . '/../config/database.php';

function logAction($action, $details = '') {
    if (!isset($_SESSION['user_id'])) {
        return; // Ne pas logger si non connecté
    }

    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare("
        INSERT INTO historique_action (id_utilisateur, action, entite, details) 
        VALUES (:id_utilisateur, :action, :entite, :details)
    ");
    
    $stmt->execute([
        'id_utilisateur' => $_SESSION['user_id'],
        'action' => substr($action, 0, 50),
        'entite' => 'system',
        'details' => $details
    ]);
}
