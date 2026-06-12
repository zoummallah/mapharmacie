<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/utils.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Medicament.php';
require_once __DIR__ . '/../models/Vente.php';
require_once __DIR__ . '/../models/Lot.php';

verifierConnexion();

$lotModel = new Lot();
$lotModel->checkPerimes();

$medicamentModel = new Medicament();
$venteModel = new Vente();
$db = Database::getInstance()->getConnection();
$action = isset($_GET['action']) ? sanitizeInput($_GET['action']) : 'index';

if ($action === 'index') {
    require __DIR__ . '/../views/rapports/index.php';

} elseif ($action === 'stock_alerte') {
    $medicaments = $medicamentModel->getStockBas();
    require __DIR__ . '/../views/rapports/stock_alerte.php';

} elseif ($action === 'expiration') {
    $medicaments = $medicamentModel->getExpirant(30); // 30 jours
    require __DIR__ . '/../views/rapports/expiration.php';

} elseif ($action === 'ventes_periode') {
    $date_debut = $_GET['date_debut'] ?? date('Y-m-01');
    $date_fin = $_GET['date_fin'] ?? date('Y-m-t');
    
    $ventes = $venteModel->getHistorique(['date_debut' => $date_debut, 'date_fin' => $date_fin], 1000);
    require __DIR__ . '/../views/rapports/ventes_periode.php';

} elseif ($action === 'inventaire') {
    // Calcul de la valeur de l'inventaire basé sur les lots actifs
    $stmt = $db->query("
        SELECT l.*, m.nom as medicament_nom, c.nom as categorie_nom 
        FROM lot l 
        JOIN medicament m ON l.id_medicament = m.id
        LEFT JOIN categorie c ON m.id_categorie = c.id
        WHERE l.statut = 'actif' AND l.quantite > 0
    ");
    $lots = $stmt->fetchAll();
    
    $valeur_totale_achat = 0;
    $valeur_totale_vente = 0;
    foreach ($lots as $lot) {
        $valeur_totale_achat += $lot['quantite'] * $lot['prix_achat'];
        $valeur_totale_vente += $lot['quantite'] * $lot['prix_vente'];
    }
    
    require __DIR__ . '/../views/rapports/inventaire.php';

} elseif ($action === 'marge') {
    verifierAdmin();
    $date_debut = $_GET['date_debut'] ?? date('Y-m-01');
    $date_fin = $_GET['date_fin'] ?? date('Y-m-t');

    // Calcul de la marge sur les ventes de la période
    $stmt = $db->prepare("
        SELECT 
            m.nom, 
            SUM(lv.quantite) as qte_vendue, 
            SUM(lv.quantite * lv.prix_unitaire) as ca,
            SUM(lv.quantite * COALESCE(l.prix_achat, m.prix_achat)) as cout,
            SUM(fn_calculer_marge(lv.prix_unitaire, COALESCE(l.prix_achat, m.prix_achat), lv.quantite)) as marge
        FROM ligne_vente lv
        JOIN vente v ON lv.id_vente = v.id
        JOIN medicament m ON lv.id_medicament = m.id
        LEFT JOIN lot l ON lv.id_lot = l.id
        WHERE DATE(v.date_vente) BETWEEN ? AND ?
        GROUP BY m.id
        ORDER BY marge DESC
    ");
    $stmt->execute([$date_debut, $date_fin]);
    $marges = $stmt->fetchAll();

    $marge_globale = 0;
    foreach ($marges as $m) {
        $marge_globale += $m['marge'];
    }

    require __DIR__ . '/../views/rapports/marge.php';
}
