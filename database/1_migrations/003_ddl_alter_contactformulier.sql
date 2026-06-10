-- ============================================================
-- ALTER SCRIPT: contact_formulier
-- Dropt de bestaande tabel en maakt een nieuwe aan met
-- voornaam, achternaam, telefoonnummer en afgehandeld_op.
-- Foreign key verwijst naar klant in plaats van account.
-- ============================================================
USE useITtooApplicatieDB;
DROP TABLE IF EXISTS `contact_formulier`;

CREATE TABLE `contact_formulier` (
    `contact_formulier_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `klant_id`             INT NULL DEFAULT NULL,
    `voornaam`             VARCHAR(50)  NOT NULL,
    `achternaam`           VARCHAR(100) NOT NULL,
    `email`                VARCHAR(255) NOT NULL CHECK (`email` REGEXP '^[^@]+@[^@]+\.[^@]+$'),
    `telefoonnummer`       VARCHAR(20)  NULL DEFAULT NULL,
    `bericht`              TEXT NOT NULL,
    `verzonden_op`         TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `afgehandeld_op`       TIMESTAMP NULL DEFAULT NULL,
    `deleted_at`           TIMESTAMP NULL DEFAULT NULL,

    FOREIGN KEY (`klant_id`) REFERENCES `klant`(`account_id`) ON DELETE SET NULL
);

DELIMITER //

-- Blokkeert fysieke DELETE; applicatie moet soft delete gebruiken
CREATE TRIGGER contact_formulier_no_delete
BEFORE DELETE ON `contact_formulier`
FOR EACH ROW
BEGIN
    SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Gebruik UPDATE deleted_at voor soft delete op contact_formulier';
END//

DELIMITER ;