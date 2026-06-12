<?php
require_once __DIR__ . '/../config/database.php';

class Lot {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getByMedicament($id_medicament) {
        $stmt = $this->db->prepare("SELECT * FROM lot WHERE id_medicament = ? ORDER BY date_expiration ASC");
        $stmt->execute([$id_medicament]);
        return $stmt->fetchAll();
    }
    
    public function getLotsActifs() {
        $stmt = $this->db->query("
            SELECT l.*, m.nom as medicament_nom 
            FROM lot l 
            JOIN medicament m ON l.id_medicament = m.id 
            WHERE l.statut = 'actif' AND l.quantite > 0 
            ORDER BY l.date_expiration ASC
        ");
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT l.*, m.nom as medicament_nom FROM lot l JOIN medicament m ON l.id_medicament = m.id WHERE l.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO lot (id_medicament, numero_lot, date_fabrication, date_expiration, quantite, prix_achat, prix_vente) 
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['id_medicament'],
            $data['numero_lot'],
            $data['date_fabrication'] ?: null,
            $data['date_expiration'],
            $data['quantite'],
            $data['prix_achat'],
            $data['prix_vente']
        ]);
    }

    public function checkPerimes() {
        $stmt = $this->db->prepare("UPDATE lot SET statut = 'perime' WHERE date_expiration < CURDATE() AND statut = 'actif'");
        $stmt->execute();
    }

    public function getLotsPerimes() {
        $this->checkPerimes(); // Mise à jour auto des statuts avant la sélection
        $stmt = $this->db->query("
            SELECT l.*, m.nom as medicament_nom 
            FROM lot l 
            JOIN medicament m ON l.id_medicament = m.id 
            WHERE l.statut = 'perime' AND l.quantite > 0 
            ORDER BY l.date_expiration ASC
        ");
        return $stmt->fetchAll();
    }

    public function viderLot($id) {
        $stmt = $this->db->prepare("UPDATE lot SET quantite = 0, statut = 'perime' WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
