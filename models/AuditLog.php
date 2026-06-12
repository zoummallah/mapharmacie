<?php
require_once __DIR__ . '/../config/database.php';

class AuditLog {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll($filters = null) {
        $sql = "
            SELECT a.*, u.nom as utilisateur_nom 
            FROM historique_action a
            LEFT JOIN utilisateur u ON a.id_utilisateur = u.id
            WHERE 1=1
        ";
        $params = [];

        if (!empty($filters['id_utilisateur'])) {
            $sql .= " AND a.id_utilisateur = ?";
            $params[] = $filters['id_utilisateur'];
        }

        if (!empty($filters['date_debut'])) {
            $sql .= " AND DATE(a.date_action) >= ?";
            $params[] = $filters['date_debut'];
        }

        if (!empty($filters['date_fin'])) {
            $sql .= " AND DATE(a.date_action) <= ?";
            $params[] = $filters['date_fin'];
        }

        $sql .= " ORDER BY a.date_action DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}
