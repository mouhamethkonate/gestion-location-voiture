<?php
require_once __DIR__ . '/Model.php';

class Vehicule extends Model {
    protected string $table = 'vehicules';

    public function findAllWithCategory(): array {
        $stmt = $this->db->query("
            SELECT v.*, c.nom AS categorie_nom
            FROM vehicules v
            LEFT JOIN categories c ON v.id_categorie = c.id
            ORDER BY v.created_at DESC
        ");
        return $stmt->fetchAll();
    }

    public function findDisponibles(string $dateDebut = '', string $dateFin = ''): array {
        if ($dateDebut && $dateFin) {
            $stmt = $this->db->prepare("
                SELECT v.*, c.nom AS categorie_nom
                FROM vehicules v
                LEFT JOIN categories c ON v.id_categorie = c.id
                WHERE v.statut = 'disponible'
                AND v.id NOT IN (
                    SELECT id_vehicule FROM reservations
                    WHERE statut NOT IN ('annulee','terminee')
                    AND NOT (date_fin < :debut OR date_debut > :fin)
                )
                ORDER BY v.prix_par_jour ASC
            ");
            $stmt->execute(['debut' => $dateDebut, 'fin' => $dateFin]);
        } else {
            $stmt = $this->db->query("
                SELECT v.*, c.nom AS categorie_nom
                FROM vehicules v
                LEFT JOIN categories c ON v.id_categorie = c.id
                WHERE v.statut = 'disponible'
                ORDER BY v.prix_par_jour ASC
            ");
        }
        return $stmt->fetchAll();
    }

    public function findByIdWithCategory(int $id): array|false {
        $stmt = $this->db->prepare("
            SELECT v.*, c.nom AS categorie_nom
            FROM vehicules v
            LEFT JOIN categories c ON v.id_categorie = c.id
            WHERE v.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create(array $data): bool {
        $stmt = $this->db->prepare("
            INSERT INTO vehicules (immatriculation, marque, modele, annee, couleur, places, prix_par_jour, statut, image, description, id_categorie)
            VALUES (:immatriculation, :marque, :modele, :annee, :couleur, :places, :prix_par_jour, :statut, :image, :description, :id_categorie)
        ");
        return $stmt->execute($data);
    }

    public function update(int $id, array $data): bool {
        $data['id'] = $id;
        $stmt = $this->db->prepare("
            UPDATE vehicules SET immatriculation=:immatriculation, marque=:marque, modele=:modele,
            annee=:annee, couleur=:couleur, places=:places, prix_par_jour=:prix_par_jour,
            statut=:statut, image=:image, description=:description, id_categorie=:id_categorie
            WHERE id=:id
        ");
        return $stmt->execute($data);
    }

    public function updateStatut(int $id, string $statut): bool {
        $stmt = $this->db->prepare("UPDATE vehicules SET statut = ? WHERE id = ?");
        return $stmt->execute([$statut, $id]);
    }

    public function countByStatut(string $statut): int {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM vehicules WHERE statut = ?");
        $stmt->execute([$statut]);
        return (int) $stmt->fetchColumn();
    }
}
