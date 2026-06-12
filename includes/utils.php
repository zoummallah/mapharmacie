<?php
/**
 * Fonctions utilitaires
 */

function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    // NOTE: Ne pas appeler htmlspecialchars ici - les vues le font à l'affichage
    // et PDO/prepared statements protègent déjà contre l'injection SQL
    return $data;
}

function formatMontant($montant) {
    return number_format($montant, 0, ',', ' ') . ' FCFA';
}

function formatDate($dateStr, $format = 'd/m/Y H:i') {
    if (!$dateStr) return '';
    $date = new DateTime($dateStr);
    return $date->format($format);
}

function genererNumeroFacture() {
    return 'FACT-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));
}

function addFlashMessage($type, $message) {
    if (!isset($_SESSION['flash'])) {
        $_SESSION['flash'] = [];
    }
    $_SESSION['flash'][] = ['type' => $type, 'message' => $message];
}

function getFlashMessages() {
    if (isset($_SESSION['flash'])) {
        $messages = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $messages;
    }
    return [];
}
