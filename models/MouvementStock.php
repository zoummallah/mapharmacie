<?php
require_once __DIR__ . '/../config/database.php';

class MouvementStock {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function enregistrer($id_medicament, $type, $quantite, $motif, $id_utilisateur, $id_lot = null) {
        // Le trigger (after_mouvement_stock_insert) s'occupera de la mise à jour des stocks
        $stmt = $this->db->prepare("
            INSERT INTO mouvement_stock (id_medicament, id_lot, type_mouvement, quantite, motif, id_utilisateur) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([$id_medicament, $id_lot, $type, $quantite, $motif, $id_utilisateur]);
    }

    public function getHistorique($filters = null) {
        $sql = "
            SELECT ms.*, m.nom as medicament_nom, u.nom as utilisateur_nom, l.numero_lot
            FROM mouvement_stock ms
            JOIN medicament m ON ms.id_medicament = m.id
            JOIN utilisateur u ON ms.id_utilisateur = u.id
            LEFT JOIN lot l ON ms.id_lot = l.id
            WHERE 1=1
        ";
        $params = [];

        if (!empty($filters['type'])) {
            $sql .= " AND ms.type_mouvement = ?";
            $params[] = $filters['type'];
        }

        if (!empty($filters['date_debut'])) {
            $sql .= " AND DATE(ms.date_mouvement) >= ?";
            $params[] = $filters['date_debut'];
        }

        if (!empty($filters['date_fin'])) {
            $sql .= " AND DATE(ms.date_mouvement) <= ?";
            $params[] = $filters['date_fin'];
        }

        $sql .= " ORDER BY ms.date_mouvement DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}
