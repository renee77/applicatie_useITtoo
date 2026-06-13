<?php

namespace App\DAO;

use App\Models\ContactFormulier;
use PDO;

class ContactFormulierDAO
{
    public function __construct(private PDO $db)
    {
    }

    public function contactFormulierOpslaan(ContactFormulier $contactFormulier): void
    {
        // afgehandeld_op en deleted_at worden weggelaten — die staan in de database
        // standaard op NULL via DEFAULT NULL en worden later via aparte methoden ingesteld.
        $sql = "INSERT INTO contact_formulier (
                voornaam,
                achternaam,
                email,
                bericht,
                verzonden_op,
                telefoonnummer,
                klant_id
            )
            VALUES (
                :voornaam,
                :achternaam,
                :email,
                :bericht,
                :verzonden_op,
                :telefoonnummer,
                :klant_id
            )";

        $stmt = $this->db->prepare($sql);

        // Variabelen tussenopslaan zodat we het PDO type kunnen bepalen.
        // telefoonnummer en klant_id kunnen null zijn — PDO moet dat expliciet weten
        // via PDO::PARAM_NULL, anders wordt null omgezet naar een lege string.
        $telefoonnummer = $contactFormulier->getTelefoonnummer();
        $klantId        = $contactFormulier->getKlantId();

        $stmt->bindValue(':voornaam', $contactFormulier->getVoornaam(), PDO::PARAM_STR);
        $stmt->bindValue(':achternaam', $contactFormulier->getAchternaam(), PDO::PARAM_STR);
        $stmt->bindValue(':email', $contactFormulier->getEmail(), PDO::PARAM_STR);
        $stmt->bindValue(':bericht', $contactFormulier->getBericht(), PDO::PARAM_STR);
        $stmt->bindValue(':verzonden_op', $contactFormulier->getVerzondenOp()->format('Y-m-d H:i:s'), PDO::PARAM_STR);
        $stmt->bindValue(':telefoonnummer', $telefoonnummer, $telefoonnummer === null ?
                PDO::PARAM_NULL : PDO::PARAM_STR);
        $stmt->bindValue(':klant_id', $klantId, $klantId === null ? PDO::PARAM_NULL : PDO::PARAM_INT);

        $stmt->execute();
    }

    public function getOpenFormulieren(): array
    {
        $sql = "SELECT * FROM contact_formulier
            WHERE afgehandeld_op IS NULL
            AND deleted_at IS NULL";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        $rows = $stmt->fetchAll();

        $openContactFormulieren = [];

        foreach ($rows as $row) {
            $openContactFormulieren[] = ContactFormulier::fromDatabase($row);
        }

        return $openContactFormulieren;
    }

    public function getFormulierById(int $id): ?ContactFormulier
    {
        $sql = "SELECT * FROM contact_formulier
            WHERE contact_formulier_id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch();

        if ($row === false) {
            return null;
        }

        return ContactFormulier::fromDatabase($row);
    }

    public function deleteContactFormulier(int $id): void
    {
        $sql = "UPDATE contact_formulier
            SET deleted_at = NOW()
            WHERE contact_formulier_id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function setAfgehandeld(int $id): void
    {
        $sql = "UPDATE contact_formulier
            SET afgehandeld_op = NOW()
            WHERE contact_formulier_id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updateEmail(int $id, string $email): void
    {
        $sql = "UPDATE contact_formulier
            SET email = :email
            WHERE contact_formulier_id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updateTelefoonnummer(int $id, ?string $telefoonnummer): void
    {
        $sql = "UPDATE contact_formulier
            SET telefoonnummer = :telefoonnummer
            WHERE contact_formulier_id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':telefoonnummer', $telefoonnummer, $telefoonnummer === null ?
            PDO::PARAM_NULL : PDO::PARAM_STR);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function getAllContactFormulieren(): array
    {
        $sql = "SELECT * FROM contact_formulier
                WHERE deleted_at IS NULL";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        $rows = $stmt->fetchAll();

        $contactFormulieren = [];

        foreach ($rows as $row) {
            $contactFormulieren[] = ContactFormulier::fromDatabase($row);
        }

        return $contactFormulieren;
    }
}
