<?php
/**
 * Fichier de configuration global du projet
 */

// Chemin racine de l'application
define('BASE_PATH', dirname(__DIR__));
define('BASE_URL', '/gestion_pharmacie');
define('DB_PORT', '3309');
// Configuration des uploads
define('UPLOAD_DIR', BASE_PATH . '/public/uploads/');
define('UPLOAD_URL', BASE_URL . '/public/uploads/');
define('ORDONNANCES_DIR', UPLOAD_DIR . 'ordonnances/');

// Fuseau horaire
date_default_timezone_set('Europe/Paris');

// Démarrage de session s'il n'est pas déjà actif
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
