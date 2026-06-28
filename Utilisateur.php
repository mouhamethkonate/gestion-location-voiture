<?php
require_once __DIR__ . '/Model.php';

class Utilisateur extends Model {
    protected string $table = 'utilisateurs';

    public function findByEmail(string $email): array|false {
        $stmt = $this->db->prepare("SELECT * FROM utilisateurs WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function create(array $data): bool {
        $stmt = $this->db->prepare("
            INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, telephone, role)
            VALUES (:nom, :prenom, :email, :mot_de_passe, :telephone, :role)
        ");
        return $stmt->execute([
            'nom'          => $data['nom'],
            'prenom'       => $data['prenom'],
            'email'        => $data['email'],
            'mot_de_passe' => password_hash($data['mot_de_passe'], PASSWORD_BCRYPT),
            'telephone'    => $data['telephone'] ?? null,
            'role'         => $data['role'] ?? 'client',
        ]);
    }

    public function update(int $id, array $data): bool {
        $stmt = $this->db->prepare("
            UPDATE utilisateurs SET nom=:nom, prenom=:prenom, telephone=:telephone, adresse=:adresse
            WHERE id=:id
        ");
        // Passer uniquement les clés attendues par PDO (pas de clés supplémentaires)
        return $stmt->execute([
            'nom'       => $data['nom']       ?? '',
            'prenom'    => $data['prenom']    ?? '',
            'telephone' => $data['telephone'] ?? null,
            'adresse'   => $data['adresse']   ?? null,
            'id'        => $id,
        ]);
    }

    public function verifyPassword(string $plain, string $hash): bool {
        return password_verify($plain, $hash);
    }

    public function updatePassword(int $id, string $newPassword): bool {
        $stmt = $this->db->prepare("UPDATE utilisateurs SET mot_de_passe = ? WHERE id = ?");
        return $stmt->execute([password_hash($newPassword, PASSWORD_BCRYPT), $id]);
    }

    public function countByRole(string $role): int {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM utilisateurs WHERE role = ?");
        $stmt->execute([$role]);
        return (int) $stmt->fetchColumn();
    }
}
