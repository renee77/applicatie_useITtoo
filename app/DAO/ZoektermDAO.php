<?php

namespace App\DAO;

use App\Models\Zoekterm;
use PDO;

class ZoektermDAO
{
    public function __construct(private PDO $db)
    {
    }

    /**
     * Controleert of een zoekterm al bestaat in de database.
     * Geeft true terug als de zoekterm gevonden is, anders false.
     */
    public function bestaatZoekterm(string $zoekterm): bool
    {
        // Zoek naar de zoekterm in de database
        $sql = "SELECT 1 FROM zoekterm WHERE zoekterm = :zoekterm";
        $stmt = $this->db->prepare($sql);
        // Bind de zoekterm als string parameter
        $stmt->bindValue(':zoekterm', $zoekterm);
        $stmt->execute();
        $row = $stmt->fetch();
        // Cast het resultaat naar bool: een gevonden rij is true, false bij geen resultaat
        return (bool)$row;
    }

    public function opslaanZoekterm(Zoekterm $zoekterm): void
    {
        $sql = "
            INSERT INTO zoekterm(zoekterm, aantal)
            VALUES (:zoekterm, :aantal)
       ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':zoekterm', $zoekterm->getZoekterm());
        $stmt->bindValue(':aantal', $zoekterm->getAantalKeerGezocht());
        $stmt->execute();
    }

    public function verhoogAantal(Zoekterm $zoekterm): void
    {
        // aantal + 1 voorkomt race conditions bij gelijktijdige zoekopdrachten van meerdere gebruikers
        $sql = "
            UPDATE zoekterm SET aantal = aantal +1
            WHERE zoekterm = :zoekterm";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':zoekterm', $zoekterm->getZoekterm());
        $stmt->execute();
    }
}
