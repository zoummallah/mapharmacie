<?php
require_once __DIR__ . '/../config/database.php';

class Utilisateur {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT id, nom, prenom, email, role, statut FROM utilisateur ORDER BY nom");
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT id, nom, prenom, email, role, statut FROM utilisateur WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $hash = password_hash($data['mot_de_passe'], PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("INSERT INTO utilisateur (nom, prenom, email, mot_de_passe, role) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['nom'],
            $data['prenom'],
            $data['email'],
            $hash,
            $data['role']
        ]);
    }

    public function update($id, $data) {
        if (!empty($data['mot_de_passe'])) {
            $hash = password_hash($data['mot_de_passe'], PASSWORD_BCRYPT);
            $stmt = $this->db->prepare("UPDATE utilisateur SET nom=?, prenom=?, email=?, role=?, statut=?, mot_de_passe=? WHERE id=?");
            return $stmt->execute([$data['nom'], $data['prenom'], $data['email'], $data['role'], $data['statut'], $hash, $id]);
        } else {
            $stmt = $this->db->prepare("UPDATE utilisateur SET nom=?, prenom=?, email=?, role=?, statut=? WHERE id=?");
            return $stmt->execute([$data['nom'], $data['prenom'], $data['email'], $data['role'], $data['statut'], $id]);
        }
    }

    public function delete($id) {
        // Au lieu de supprimer, on désactive pour garder l'intégrité
        $stmt = $this->db->prepare("UPDATE utilisateur SET statut = 0 WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
