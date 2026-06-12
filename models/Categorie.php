<?php
require_once __DIR__ . '/../config/database.php';

class Categorie {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM categorie ORDER BY nom");
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM categorie WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO categorie (nom, description) VALUES (?, ?)");
        return $stmt->execute([$data['nom'], $data['description']]);
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE categorie SET nom = ?, description = ? WHERE id = ?");
        return $stmt->execute([$data['nom'], $data['description'], $id]);
    }

    public function checkMedCount($id) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM medicament WHERE id_categorie = ?");
        $stmt->execute([$id]);
        return $stmt->fetchColumn();
    }

    public function delete($id) {
        if ($this->checkMedCount($id) > 0) {
            return false; // Intégrité référentielle
        }
        $stmt = $this->db->prepare("DELETE FROM categorie WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
