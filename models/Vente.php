<?php
require_once __DIR__ . '/../config/database.php';

class Vente {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function genererNumeroFacture() {
        return 'FACT-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));
    }

    public function validerVente($panier, $id_utilisateur, $id_client = null, $id_ordonnance = null) {
        try {
            $this->db->beginTransaction();

            $numero_facture = $this->genererNumeroFacture();
            $montant_total = 0;

            // Calcul du total
            foreach ($panier as $item) {
                $montant_total += $item['prix_unitaire'] * $item['quantite'];
            }

            // Insertion vente
            $stmt = $this->db->prepare("
                INSERT INTO vente (numero_facture, montant_total, id_utilisateur, id_client, id_ordonnance) 
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->execute([$numero_facture, $montant_total, $id_utilisateur, $id_client, $id_ordonnance]);
            $id_vente = $this->db->lastInsertId();

            // Insertion des lignes (les triggers s'occuperont de réduire le stock)
            $stmtLigne = $this->db->prepare("
                INSERT INTO ligne_vente (id_vente, id_medicament, id_lot, quantite, prix_unitaire) 
                VALUES (?, ?, ?, ?, ?)
            ");

            foreach ($panier as $item) {
                // Vérification du stock avant insertion (pour être sûr)
                if (isset($item['id_lot'])) {
                    $checkStock = $this->db->prepare("SELECT quantite FROM lot WHERE id = ? FOR UPDATE");
                    $checkStock->execute([$item['id_lot']]);
                    $stockDispo = $checkStock->fetchColumn();
                } else {
                    $checkStock = $this->db->prepare("SELECT quantite_stock FROM medicament WHERE id = ? FOR UPDATE");
                    $checkStock->execute([$item['id_medicament']]);
                    $stockDispo = $checkStock->fetchColumn();
                }

                if ($stockDispo < $item['quantite']) {
                    throw new Exception("Stock insuffisant pour un des articles.");
                }

                $stmtLigne->execute([
                    $id_vente,
                    $item['id_medicament'],
                    $item['id_lot'] ?? null,
                    $item['quantite'],
                    $item['prix_unitaire']
                ]);
            }

            $this->db->commit();
            return $id_vente;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function getHistorique($filters = null, $limit = 50, $offset = 0) {
        $sql = "
            SELECT v.*, u.nom as vendeur_nom, c.nom as client_nom 
            FROM vente v
            JOIN utilisateur u ON v.id_utilisateur = u.id
            LEFT JOIN client c ON v.id_client = c.id
            WHERE 1=1
        ";
        $params = [];

        if (!empty($filters['date_debut'])) {
            $sql .= " AND DATE(v.date_vente) >= ?";
            $params[] = $filters['date_debut'];
        }

        if (!empty($filters['date_fin'])) {
            $sql .= " AND DATE(v.date_vente) <= ?";
            $params[] = $filters['date_fin'];
        }

        $sql .= " ORDER BY v.date_vente DESC LIMIT $limit OFFSET $offset";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getDetails($id_vente) {
        $stmt = $this->db->prepare("
            SELECT lv.*, m.nom as medicament_nom, l.numero_lot
            FROM ligne_vente lv
            JOIN medicament m ON lv.id_medicament = m.id
            LEFT JOIN lot l ON lv.id_lot = l.id
            WHERE lv.id_vente = ?
        ");
        $stmt->execute([$id_vente]);
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->db->prepare("
            SELECT v.*, u.nom as vendeur_nom, c.nom as client_nom, c.adresse as client_adresse
            FROM vente v
            JOIN utilisateur u ON v.id_utilisateur = u.id
            LEFT JOIN client c ON v.id_client = c.id
            WHERE v.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}
