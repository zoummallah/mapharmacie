<?php
require_once __DIR__ . '/../config/database.php';

class Fournisseur {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM fournisseur ORDER BY nom");
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM fournisseur WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO fournisseur (nom, contact_nom, adresse, email, telephone) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['nom'],
            $data['contact_nom'],
            $data['adresse'],
            $data['email'],
            $data['telephone']
        ]);
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE fournisseur SET nom=?, contact_nom=?, adresse=?, email=?, telephone=? WHERE id=?");
        return $stmt->execute([
            $data['nom'],
            $data['contact_nom'],
            $data['adresse'],
            $data['email'],
            $data['telephone'],
            $id
        ]);
    }

    public function checkLinksCount($id) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM medicament_fournisseur WHERE id_fournisseur = ?");
        $stmt->execute([$id]);
        return $stmt->fetchColumn();
    }

    public function delete($id) {
        if ($this->checkLinksCount($id) > 0) {
            return false; // Intégrité
        }
        $stmt = $this->db->prepare("DELETE FROM fournisseur WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
