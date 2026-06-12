<?php
require_once __DIR__ . '/../config/database.php';

class Ordonnance {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll($filters = null) {
        $sql = "
            SELECT o.*, c.nom as client_nom 
            FROM ordonnance o
            LEFT JOIN client c ON o.id_client = c.id
            ORDER BY o.date_emission DESC
        ";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->db->prepare("
            SELECT o.*, c.nom as client_nom 
            FROM ordonnance o
            LEFT JOIN client c ON o.id_client = c.id
            WHERE o.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO ordonnance (numero_ordonnance, date_emission, date_validite, medecin_prescripteur, id_client, fichier_ordonnance) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['numero_ordonnance'],
            $data['date_emission'],
            $data['date_validite'] ?: null,
            $data['medecin_prescripteur'],
            $data['id_client'],
            $data['fichier_ordonnance'] ?? null
        ]);
    }
}
