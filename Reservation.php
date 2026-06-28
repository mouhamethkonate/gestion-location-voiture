<?php
require_once __DIR__ . '/Model.php';

class Reservation extends Model {
    protected string $table = 'reservations';

    public function findAllDetails(): array {
        $stmt = $this->db->query("
            SELECT r.*, 
                   CONCAT(u.prenom,' ',u.nom) AS client_nom, u.email AS client_email, u.telephone AS client_tel,
                   CONCAT(v.marque,' ',v.modele) AS vehicule_nom, v.immatriculation
            FROM reservations r
            JOIN utilisateurs u ON r.id_client = u.id
            JOIN vehicules v ON r.id_vehicule = v.id
            ORDER BY r.created_at DESC
        ");
        return $stmt->fetchAll();
    }

    public function findByClient(int $clientId): array {
        $stmt = $this->db->prepare("
            SELECT r.*, CONCAT(v.marque,' ',v.modele) AS vehicule_nom, v.immatriculation, v.image,
                   f.id AS id_facture, f.statut_paiement
            FROM reservations r
            JOIN vehicules v ON r.id_vehicule = v.id
            LEFT JOIN factures f ON f.id_reservation = r.id
            WHERE r.id_client = ?
            ORDER BY r.created_at DESC
        ");
        $stmt->execute([$clientId]);
        return $stmt->fetchAll();
    }

    public function findByIdDetails(int $id): array|false {
        $stmt = $this->db->prepare("
            SELECT r.*,
                   CONCAT(u.prenom,' ',u.nom) AS client_nom, u.email AS client_email, u.telephone AS client_tel, u.adresse AS client_adresse,
                   CONCAT(v.marque,' ',v.modele) AS vehicule_nom, v.immatriculation, v.prix_par_jour, v.image
            FROM reservations r
            JOIN utilisateurs u ON r.id_client = u.id
            JOIN vehicules v ON r.id_vehicule = v.id
            WHERE r.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create(array $data): int {
        $data['reference'] = 'RES-' . strtoupper(uniqid());
        $stmt = $this->db->prepare("
            INSERT INTO reservations (reference, date_debut, date_fin, nb_jours, montant_total, statut, notes, id_client, id_vehicule)
            VALUES (:reference, :date_debut, :date_fin, :nb_jours, :montant_total, 'en_attente', :notes, :id_client, :id_vehicule)
        ");
        $stmt->execute($data);
        return (int) $this->db->lastInsertId();
    }

    public function updateStatut(int $id, string $statut, ?int $agentId = null): bool {
        $stmt = $this->db->prepare("UPDATE reservations SET statut=?, id_agent=? WHERE id=?");
        return $stmt->execute([$statut, $agentId, $id]);
    }

    public function countByStatut(string $statut): int {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM reservations WHERE statut = ?");
        $stmt->execute([$statut]);
        return (int) $stmt->fetchColumn();
    }

    public function chiffreAffaires(): float {
        return (float) $this->db->query("SELECT COALESCE(SUM(montant_total),0) FROM reservations WHERE statut='confirmee'")->fetchColumn();
    }
}
