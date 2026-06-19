<?php
// ============================================================
//  CarLoc – Model de base
// ============================================================

abstract class Model {
    protected PDO $db;
    protected string $table;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function findAll(string $order = 'id DESC'): array {
        $stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY {$order}");
        return $stmt->fetchAll();
    }

    public function findById(int $id): array|false {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function count(): int {
        return (int) $this->db->query("SELECT COUNT(*) FROM {$this->table}")->fetchColumn();
    }
}
