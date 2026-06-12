<?php
require_once __DIR__ . '/../config/database.php';

class Client {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll($search = null) {
        $sql = "SELECT * FROM client";
        $params = [];

        if ($search) {
            $sql .= " WHERE nom LIKE ? OR telephone LIKE ?";
            $searchParam = "%$search%";
            $params[] = $searchParam;
            $params[] = $searchParam;
        }
        
        $sql .= " ORDER BY nom ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM client WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO client (nom, telephone, email, adresse, historique_medical) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['nom'],
            $data['telephone'],
            $data['email'],
            $data['adresse'],
            $data['historique_medical']
        ]);
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE client SET nom=?, telephone=?, email=?, adresse=?, historique_medical=? WHERE id=?");
        return $stmt->execute([
            $data['nom'],
            $data['telephone'],
            $data['email'],
            $data['adresse'],
            $data['historique_medical'],
            $id
        ]);
    }
}
