<?php
require_once __DIR__ . '/Model.php';

class Facture extends Model {
    protected string $table = 'factures';

    public function create(array $data): int {
        $data['numero'] = 'FAC-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
        $data['date_emission'] = date('Y-m-d');
        $stmt = $this->db->prepare("
            INSERT INTO factures (numero, date_emission, montant_ht, tva, montant_ttc, statut_paiement, mode_paiement, id_reservation)
            VALUES (:numero, :date_emission, :montant_ht, :tva, :montant_ttc, :statut_paiement, :mode_paiement, :id_reservation)
        ");
        $stmt->execute($data);
        return (int) $this->db->lastInsertId();
    }

    public function findByReservation(int $resaId): array|false {
        $stmt = $this->db->prepare("
            SELECT f.*, r.reference, r.date_debut, r.date_fin, r.nb_jours, r.montant_total,
                   CONCAT(u.prenom,' ',u.nom) AS client_nom, u.email AS client_email, u.telephone AS client_tel, u.adresse AS client_adresse,
                   CONCAT(v.marque,' ',v.modele) AS vehicule_nom, v.immatriculation
            FROM factures f
            JOIN reservations r ON f.id_reservation = r.id
            JOIN utilisateurs u ON r.id_client = u.id
            JOIN vehicules v ON r.id_vehicule = v.id
            WHERE f.id_reservation = ?
        ");
        $stmt->execute([$resaId]);
        return $stmt->fetch();
    }

    public function updateStatutPaiement(int $id, string $statut): bool {
        $stmt = $this->db->prepare("UPDATE factures SET statut_paiement = ? WHERE id = ?");
        return $stmt->execute([$statut, $id]);
    }

    public function countByStatut(string $statut): int {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM factures WHERE statut_paiement = ?");
        $stmt->execute([$statut]);
        return (int) $stmt->fetchColumn();
    }
}
