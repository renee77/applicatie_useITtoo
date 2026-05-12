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
            fn($row) => new User($row['id'], $row['name'], $row['email'], $row['username'], $row['password']),
            $stmt->fetchAll(\PDO::FETCH_ASSOC)
        );
    }

    public function findById(int $id): ?User
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ? new User($row['id'], $row['name'], $row['email'],  $row['username'], $row['password']) : null;
    }

    // Haal de user op op basis van de gebruikersnaam (voor de validatie van het inloggen)
    public function getByUsername(string $username): ?User 
    {
        // De informatie uit de database opvragen via een SQL query (SQL-Injectie voorkomen via bindValue)
        $stmt = $this->db->prepare("SELECT * FROM users WHERE gebruikersnaam = :username");
        $stmt->bindValue(':username', $username);
        $stmt->execute();
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        // Als er niets bestaat, een null-value terugsturen
        if (!$row) 
        {
            return null;
        }
        // Als er wel iets bestaat, wordt er een nieuwe userklasse aangemaakt met de opgehaalde informatie uit de database
        return new User($row['id'], $row['name'], $row['email'],  $row['username'], $row['password']);
    }
}
