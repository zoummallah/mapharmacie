<?php
require_once __DIR__ . '/../config/database.php';

class Medicament {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll($filters = null, $search = null) {
        $sql = "
            SELECT m.*, c.nom as categorie_nom, 
                   GROUP_CONCAT(DISTINCT f.nom SEPARATOR ', ') as fournisseur_nom
            FROM medicament m
            LEFT JOIN categorie c ON m.id_categorie = c.id
            LEFT JOIN medicament_fournisseur mf ON m.id = mf.id_medicament
            LEFT JOIN fournisseur f ON mf.id_fournisseur = f.id
            LEFT JOIN lot l ON m.id = l.id_medicament
            WHERE m.est_actif = 1
        ";
        $params = [];

        if ($search) {
            $sql .= " AND (m.nom LIKE ? OR m.principe_actif LIKE ? OR l.numero_lot LIKE ? OR m.lot_numero LIKE ?)";
            $searchParam = "%$search%";
            $params[] = $searchParam;
            $params[] = $searchParam;
            $params[] = $searchParam;
            $params[] = $searchParam;
        }

        if ($filters) {
            if (!empty($filters['id_categorie'])) {
                $sql .= " AND m.id_categorie = ?";
                $params[] = $filters['id_categorie'];
            }
        }

        $sql .= " GROUP BY m.id ORDER BY m.nom ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->db->prepare("
            SELECT m.*, c.nom as categorie_nom
            FROM medicament m
            LEFT JOIN categorie c ON m.id_categorie = c.id
            WHERE m.id = ?
        ");
        $stmt->execute([$id]);
        $medicament = $stmt->fetch();

        if ($medicament) {
            // Récupérer les ID des fournisseurs liés
            $stmtF = $this->db->prepare("SELECT id_fournisseur FROM medicament_fournisseur WHERE id_medicament = ?");
            $stmtF->execute([$id]);
            $fournisseurs = $stmtF->fetchAll(PDO::FETCH_COLUMN);
            $medicament['fournisseurs'] = $fournisseurs;
        }

        return $medicament;
    }

    public function checkDisponibilite($nom) {
        $stmt = $this->db->prepare("
            SELECT m.nom, c.nom as categorie, m.quantite_stock 
            FROM medicament m 
            LEFT JOIN categorie c ON m.id_categorie = c.id
            WHERE m.nom LIKE ? AND m.est_actif = 1
        ");
        $stmt->execute(["%$nom%"]);
        $result = $stmt->fetchAll();
        
        $response = [];
        foreach ($result as $row) {
            $response[] = [
                'nom' => $row['nom'],
                'categorie' => $row['categorie'],
                'statut' => $row['quantite_stock'] > 0 ? 'En stock' : 'Indisponible'
            ];
        }
        return $response;
    }

    public function create($data) {
        $this->db->beginTransaction();
        try {
            $stmt = $this->db->prepare("
                INSERT INTO medicament (
                    nom, id_categorie, principe_actif, dosage, forme, 
                    prix_achat, prix_vente, stock_minimum, date_fabrication, 
                    date_expiration, lot_numero
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $data['nom'], $data['id_categorie'], $data['principe_actif'],
                $data['dosage'], $data['forme'], $data['prix_achat'],
                $data['prix_vente'], $data['stock_minimum'],
                $data['date_fabrication'] ?: null, $data['date_expiration'],
                $data['lot_numero']
            ]);
            
            $id_medicament = $this->db->lastInsertId();

            if (!empty($data['fournisseurs']) && is_array($data['fournisseurs'])) {
                $stmtF = $this->db->prepare("INSERT INTO medicament_fournisseur (id_medicament, id_fournisseur) VALUES (?, ?)");
                foreach ($data['fournisseurs'] as $id_fournisseur) {
                    $stmtF->execute([$id_medicament, $id_fournisseur]);
                }
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function update($id, $data) {
        $this->db->beginTransaction();
        try {
            $stmt = $this->db->prepare("
                UPDATE medicament SET 
                    nom=?, id_categorie=?, principe_actif=?, dosage=?, forme=?, 
                    prix_achat=?, prix_vente=?, stock_minimum=?, date_fabrication=?, 
                    date_expiration=?, lot_numero=?
                WHERE id=?
            ");
            $stmt->execute([
                $data['nom'], $data['id_categorie'], $data['principe_actif'],
                $data['dosage'], $data['forme'], $data['prix_achat'],
                $data['prix_vente'], $data['stock_minimum'],
                $data['date_fabrication'] ?: null, $data['date_expiration'],
                $data['lot_numero'], $id
            ]);

            // Mise à jour des fournisseurs
            $this->db->prepare("DELETE FROM medicament_fournisseur WHERE id_medicament = ?")->execute([$id]);

            if (!empty($data['fournisseurs']) && is_array($data['fournisseurs'])) {
                $stmtF = $this->db->prepare("INSERT INTO medicament_fournisseur (id_medicament, id_fournisseur) VALUES (?, ?)");
                foreach ($data['fournisseurs'] as $id_fournisseur) {
                    $stmtF->execute([$id, $id_fournisseur]);
                }
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function delete($id) {
        // Suppression logique
        $stmt = $this->db->prepare("UPDATE medicament SET est_actif = 0 WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getStockBas() {
        $stmt = $this->db->query("SELECT * FROM medicament WHERE quantite_stock <= stock_minimum AND est_actif = 1");
        return $stmt->fetchAll();
    }

    public function getExpirant($jours = 30) {
        $stmt = $this->db->prepare("
            SELECT 
                m.nom, 
                l.numero_lot as lot_numero, 
                l.date_expiration, 
                l.quantite as quantite_stock
            FROM lot l
            JOIN medicament m ON l.id_medicament = m.id
            WHERE m.est_actif = 1 
            AND l.quantite > 0
            AND l.date_expiration <= DATE_ADD(CURDATE(), INTERVAL ? DAY)
            ORDER BY l.date_expiration ASC
        ");
        $stmt->execute([$jours]);
        return $stmt->fetchAll();
    }
}
