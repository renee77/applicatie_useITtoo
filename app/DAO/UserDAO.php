<?php

namespace App\DAO;

use App\Core\Database;
use App\Models\User;

class UserDAO
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM users");
        return array_map(
            fn($row) => new User($row['id'], $row['name'], $row['email']),
            $stmt->fetchAll(\PDO::FETCH_ASSOC)
        );
    }

    public function findById(int $id): ?User
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ? new User($row['id'], $row['name'], $row['email']) : null;
    }
}
