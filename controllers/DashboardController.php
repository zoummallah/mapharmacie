<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/utils.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Lot.php';

verifierConnexion();

$lotModel = new Lot();
$lotModel->checkPerimes();

$db = Database::getInstance()->getConnection();

// Statistiques rapides
$statistiques = [
    'ventes_jour' => 0,
    'ca_jour' => 0,
    'medicaments_actifs' => 0,
    'clients_total' => 0
];

// Ventes du jour (Pour l'Admin uniquement dans la vue, mais on peut le récupérer globalement)
$stmt = $db->query("SELECT COUNT(*) as nb, SUM(montant_total) as total FROM vente WHERE DATE(date_vente) = CURDATE()");
$row = $stmt->fetch();
$statistiques['ventes_jour'] = $row['nb'] ?? 0;
$statistiques['ca_jour'] = $row['total'] ?? 0;

// Total Médicaments
$stmt = $db->query("SELECT COUNT(*) FROM medicament WHERE est_actif = 1");
$statistiques['medicaments_actifs'] = $stmt->fetchColumn();

// Total Clients
$stmt = $db->query("SELECT COUNT(*) FROM client");
$statistiques['clients_total'] = $stmt->fetchColumn();

// Alertes : Stock bas
$stmt = $db->query("SELECT nom, quantite_stock, stock_minimum FROM medicament WHERE quantite_stock <= stock_minimum AND est_actif = 1 LIMIT 5");
$alertes_stock = $stmt->fetchAll();

// Alertes : Produits expirés et expirant dans les 30 jours (triés par urgence)
$stmt = $db->query("
    SELECT 
        m.nom, 
        l.numero_lot as lot_numero,
        l.date_expiration,
        CASE WHEN l.date_expiration < CURDATE() THEN 'expire' ELSE 'proche' END as statut_exp
    FROM lot l
    JOIN medicament m ON l.id_medicament = m.id
    WHERE m.est_actif = 1 
    AND l.quantite > 0
    AND l.date_expiration <= DATE_ADD(CURDATE(), INTERVAL 30 DAY)
    ORDER BY l.date_expiration ASC
    LIMIT 8
");
$alertes_expiration = $stmt->fetchAll();

// Données pour le graphique (7 derniers jours)
$chart_labels = [];
$chart_ca = [];
$chart_ventes_nb = []; // Pour le pharmacien

if (estAdmin()) {
    // ---- STATISTIQUES FINANCIÈRES GLOBALES (Basées sur les lots actifs) ----
    $stmtFinances = $db->query("
        SELECT 
            COALESCE(SUM(l.quantite * l.prix_achat), 0) as valeur_achat,
            COALESCE(SUM(l.quantite * l.prix_vente), 0) as valeur_vente,
            COALESCE(SUM(l.quantite * (l.prix_vente - l.prix_achat)), 0) as benefice_potentiel
        FROM lot l
        JOIN medicament m ON l.id_medicament = m.id
        WHERE m.est_actif = 1 
        AND l.statut = 'actif' 
        AND l.quantite > 0
    ");
    $finances = $stmtFinances->fetch();
    $statistiques['valeur_stock_achat'] = $finances['valeur_achat'];
    $statistiques['valeur_stock_vente'] = $finances['valeur_vente'];
    $statistiques['benefice_potentiel'] = $finances['benefice_potentiel'];

    $stmtChart = $db->query("
        SELECT DATE(date_vente) as date_v, SUM(montant_total) as ca 
        FROM vente 
        WHERE date_vente >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
        GROUP BY DATE(date_vente)
        ORDER BY date_v ASC
    ");
    $donnees_brutes = $stmtChart->fetchAll(PDO::FETCH_KEY_PAIR);
    
    // Remplir les jours manquants avec 0
    for ($i = 6; $i >= 0; $i--) {
        $date = date('Y-m-d', strtotime("-$i days"));
        $chart_labels[] = date('d/m', strtotime("-$i days"));
        $chart_ca[] = $donnees_brutes[$date] ?? 0;
    }

    // Top 5 médicaments vendus (30 derniers jours)
    $stmtTopMed = $db->query("
        SELECT m.nom, SUM(lv.quantite) as total_vendu
        FROM ligne_vente lv
        JOIN medicament m ON lv.id_medicament = m.id
        JOIN vente v ON lv.id_vente = v.id
        WHERE v.date_vente >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
        GROUP BY m.id
        ORDER BY total_vendu DESC
        LIMIT 5
    ");
    $top_medicaments = $stmtTopMed->fetchAll(PDO::FETCH_ASSOC);

    // Ventes par Pharmacien (30 derniers jours)
    $stmtVentesPharma = $db->query("
        SELECT u.nom, COUNT(v.id) as total_transactions
        FROM vente v
        JOIN utilisateur u ON v.id_utilisateur = u.id
        WHERE v.date_vente >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
        GROUP BY u.id
        ORDER BY total_transactions DESC
    ");
    $ventes_pharmacien = $stmtVentesPharma->fetchAll(PDO::FETCH_ASSOC);

    // Répartition des stocks par catégorie (lots actifs uniquement)
    $stmtStockCat = $db->query("
        SELECT c.nom, COALESCE(SUM(l.quantite), 0) as total_stock
        FROM lot l
        JOIN medicament m ON l.id_medicament = m.id
        JOIN categorie c ON m.id_categorie = c.id
        WHERE m.est_actif = 1 
        AND l.statut = 'actif' 
        AND l.quantite > 0
        GROUP BY c.id
        ORDER BY total_stock DESC
    ");
    $stock_categorie = $stmtStockCat->fetchAll(PDO::FETCH_ASSOC);

    // Mouvements de stock récents (8 derniers - ventes + mouvements manuels)
    $stmtMouv = $db->query("
        SELECT 
            'vente' as source,
            'sortie' as type_mouvement,
            lv.quantite,
            v.date_vente as date_mouvement,
            m.nom as medicament,
            CONCAT(u.prenom, ' ', u.nom) as utilisateur
        FROM ligne_vente lv
        JOIN vente v ON lv.id_vente = v.id
        JOIN medicament m ON lv.id_medicament = m.id
        JOIN utilisateur u ON v.id_utilisateur = u.id

        UNION ALL

        SELECT 
            'manuel' as source,
            ms.type_mouvement,
            ms.quantite,
            ms.date_mouvement,
            m.nom as medicament,
            CONCAT(u.prenom, ' ', u.nom) as utilisateur
        FROM mouvement_stock ms
        JOIN medicament m ON ms.id_medicament = m.id
        JOIN utilisateur u ON ms.id_utilisateur = u.id

        ORDER BY date_mouvement DESC
        LIMIT 8
    ");
    $mouvements_recents = $stmtMouv->fetchAll(PDO::FETCH_ASSOC);

} else {
    // ---- STATISTIQUES PHARMACIEN (100% Médical, 0% Financier) ----

    // 1. Médicaments par catégorie (Doughnut)
    $stmtStockCat = $db->query("
        SELECT c.nom, COUNT(m.id) as total_meds
        FROM medicament m
        JOIN categorie c ON m.id_categorie = c.id
        WHERE m.est_actif = 1
        GROUP BY c.id
        ORDER BY total_meds DESC
    ");
    $pharma_stock_cat = $stmtStockCat->fetchAll(PDO::FETCH_ASSOC);

    // 2. Évolution des entrées en stock (7 derniers jours) - Au lieu des ventes
    $stmtMouvSemaine = $db->query("
        SELECT DATE(date_mouvement) as date_m, SUM(quantite) as qte
        FROM mouvement_stock
        WHERE type_mouvement = 'entree' AND date_mouvement >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
        GROUP BY DATE(date_mouvement)
        ORDER BY date_m ASC
    ");
    $donnees_brutes = $stmtMouvSemaine->fetchAll(PDO::FETCH_KEY_PAIR);
    
    // Remplir les jours manquants avec 0
    $chart_labels = [];
    $chart_entrees_stock = [];
    for ($i = 6; $i >= 0; $i--) {
        $date = date('Y-m-d', strtotime("-$i days"));
        $chart_labels[] = date('d/m', strtotime("-$i days"));
        $chart_entrees_stock[] = $donnees_brutes[$date] ?? 0;
    }

    // 3. Ordonnances récentes (les 5 dernières)
    $stmtOrd = $db->query("
        SELECT o.numero_ordonnance, o.date_emission, o.medecin_prescripteur, c.nom as client_nom 
        FROM ordonnance o
        JOIN client c ON o.id_client = c.id
        ORDER BY o.date_emission DESC
        LIMIT 5
    ");
    $ordonnances_recentes = $stmtOrd->fetchAll(PDO::FETCH_ASSOC);
    
    // Nombre total d'ordonnances
    $statistiques['ordonnances_total'] = $db->query("SELECT COUNT(*) FROM ordonnance")->fetchColumn();
}

require __DIR__ . '/../views/dashboard/index.php';
