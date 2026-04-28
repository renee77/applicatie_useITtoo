-- voorkomt een foutmelding als de database al bestaat, en zorgt ervoor dat de database wordt geselecteerd voor gebruik
CREATE DATABASE IF NOT EXISTS useITtooApplicatieDB 
-- dit ondersteunt ook de juiste tekenset voor het opslaan van speciale tekens in de database, zoals emoji's, soms handig in de recepten of productomschrijvingen
CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE useITtooApplicatieDB;

-- ============================================================
-- SOFT DELETE: in plaats van rijen fysiek verwijderen, markeer je ze als verwijderd met een timestamp.
-- soft delete: NULL = actief, timestamp = verwijderd
    -- `deleted_at` TIMESTAMP NULL DEFAULT NULL
-- In je applicatie filter je dan altijd op WHERE deleted_at IS NULL om alleen actieve rijen op te halen.
-- ============================================================


CREATE TABLE `product` (
    `product_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `naam` VARCHAR(100) NOT NULL,
    `omschrijving` TEXT,
    `prijs` DECIMAL(10, 2) CHECK (`prijs` >= 0),
    `foto_url` VARCHAR(500),
    `leverancier` VARCHAR(255),
    `verkoop_gewicht` VARCHAR(100),
    -- soft delete: NULL = actief, timestamp = verwijderd
    `deleted_at` TIMESTAMP NULL DEFAULT NULL
);


-- ============================================================
-- 1-op-1 RELATIE: product <-> voorraad
-- 
-- Best practice bij een 1-op-1 relatie:
-- Plaats de FK slechts in ÉÉN tabel, niet in beide.
-- Een circulaire FK (in beide tabellen) zorgt ervoor dat je
-- nooit een eerste rij kunt aanmaken zonder de ander al te
-- hebben — dit is een kip-en-ei probleem.
--
-- De FK komt in de "afhankelijke" tabel: voorraad.
-- Een voorraad bestaat omwille van een product,
-- niet andersom.
--
-- product_id wordt hier gebruikt als PRIMARY KEY in plaats van
-- een aparte voorraad_id. Dit dwingt de 1-op-1 relatie hard af:
-- MySQL staat per definitie maar één rij per PK-waarde toe,
-- dus elk product kan maximaal één voorraad-rij hebben.
-- ============================================================

CREATE TABLE `voorraad` (
    -- product_id is zowel PK als FK: koppelt aan product én dwingt 1-op-1 af
    `product_id` INT NOT NULL PRIMARY KEY,
    `aantal` INT NOT NULL CHECK (`aantal` >= 0),
    `status` ENUM('op voorraad', 'bijna op', 'uitverkocht'),
    -- soft delete: NULL = actief, timestamp = verwijderd
    `deleted_at` TIMESTAMP NULL DEFAULT NULL,

    FOREIGN KEY (`product_id`) REFERENCES `product`(`product_id`)
);

DELIMITER //
CREATE TRIGGER product_insert_voorraad
AFTER INSERT ON `product`
FOR EACH ROW
BEGIN
    INSERT INTO `voorraad` (`product_id`, `aantal`, `status`)
    VALUES (NEW.product_id, 0, 'uitverkocht');
END//

CREATE TRIGGER product_soft_delete_voorraad
AFTER UPDATE ON `product`
FOR EACH ROW
BEGIN
  IF OLD.deleted_at IS NULL AND NEW.deleted_at IS NOT NULL THEN
    UPDATE `voorraad`
    SET deleted_at = NEW.deleted_at
    WHERE product_id = NEW.product_id
      AND deleted_at IS NULL;
  END IF;
END//
DELIMITER ;

-- hier kiezen we niet voor een aparte categorie_id als PK, 
-- omdat de categorienaam zelf al uniek is en vaak als identifier wordt gebruikt. 
-- de categorie is minder fout gevoelig dan een kortingsactie naam, dus dat is minder problematisch.
CREATE TABLE `categorie` (
  `categorie` VARCHAR(100) NOT NULL PRIMARY KEY,
  `omschrijving` TEXT,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL -- soft delete veld
);

-- crosstable voor de veel-op-veel relatie tussen producten en categorieën
CREATE TABLE `product_categorie` (
  `categorie` VARCHAR(100) NOT NULL,
  `product_id` INT NOT NULL,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL, -- soft delete veld voor deze relatie

  PRIMARY KEY (`categorie`, `product_id`),
  FOREIGN KEY (`categorie`) REFERENCES `categorie`(`categorie`),
  FOREIGN KEY (`product_id`) REFERENCES `product`(`product_id`)
);
-- hier kiezen we niet voor een aparte categorie_id als PK, 
-- omdat de categorienaam zelf al uniek is en vaak als identifier wordt gebruikt.
-- de tag is minder fout gevoelig dan een kortingsactie naam, dus dat is minder problematisch.
CREATE TABLE `tag` (
  `tag` VARCHAR(100) NOT NULL PRIMARY KEY,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL -- soft delete veld
);

-- crosstable voor de veel-op-veel relatie tussen producten en tags
CREATE TABLE `product_tag` (
  `product_id` INT NOT NULL,
  `tag` VARCHAR(100) NOT NULL,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL, -- soft delete veld voor deze relatie

  PRIMARY KEY (`product_id`, `tag`),
  FOREIGN KEY (`product_id`) REFERENCES `product`(`product_id`),
  FOREIGN KEY (`tag`) REFERENCES `tag`(`tag`)
);

CREATE TABLE `account` (
    `account_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `gebruikersnaam` VARCHAR(255) NOT NULL UNIQUE CHECK (LENGTH(gebruikersnaam) >= 3), -- gebruikersnaam moet minimaal 3 tekens lang zijn
    -- bcrypt hashes zijn altijd minimaal 60 tekens lang ($2a$, $2b$, $2y$ varianten)
    -- deze check vangt fouten op als de applicatielaag een ongehashte waarde doorgeeft
    `wachtwoord_hash` VARCHAR(500) NOT NULL CHECK (LENGTH(wachtwoord_hash) >= 60),
    `voornaam` VARCHAR(100),
    `achternaam` VARCHAR(100),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `geboortedatum` DATE NOT NULL, 
    `telefoon` VARCHAR(20),
    `deleted_at` TIMESTAMP NULL DEFAULT NULL
);

-- triggers gemaakt omdat checks met curdate() niet werken in een CHECK constraint, want die worden alleen bij het aanmaken van de tabel geëvalueerd, niet bij elke rij. Dus we gebruiken triggers om dit dynamisch te controleren bij elke insert of update.
-- trigger op invoer geboortedatum om te controleren of de gebruiker minimaal 18 jaar oud is
DELIMITER //
CREATE TRIGGER account_check_geboortedatum_ins
BEFORE INSERT ON account
FOR EACH ROW
BEGIN
  IF NEW.geboortedatum > DATE_SUB(CURDATE(), INTERVAL 18 YEAR) THEN
    SIGNAL SQLSTATE '45000'
      SET MESSAGE_TEXT = 'Persoon moet minimaal 18 jaar zijn';
  END IF;
END//

-- trigger op update geboortedatum om te controleren of de gebruiker minimaal 18 jaar oud is
CREATE TRIGGER account_check_geboortedatum_update
BEFORE UPDATE ON account
FOR EACH ROW
BEGIN
  IF NEW.geboortedatum > DATE_SUB(CURDATE(), INTERVAL 18 YEAR) THEN
    SIGNAL SQLSTATE '45000'
      SET MESSAGE_TEXT = 'Persoon moet minimaal 18 jaar zijn';
  END IF;
END//
DELIMITER ;

CREATE TABLE `klant` (
    `account_id` INT NOT NULL PRIMARY KEY,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL,
    `startdatum_lidmaatschap` DATE,

    FOREIGN KEY (`account_id`) REFERENCES `account`(`account_id`)
);

CREATE TABLE `beheer` (
    `account_id` INT NOT NULL PRIMARY KEY,
    `rol` ENUM('voorraadbeheerder', 'klantenservice') NOT NULL,
    `datum_in_dienst` DATE NOT NULL,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL,

    FOREIGN KEY (`account_id`) REFERENCES `account`(`account_id`)
);

CREATE TABLE `adres` (
    `adres_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `straat` VARCHAR(255) NOT NULL,
    `huisnummer` VARCHAR(20) NOT NULL,
    `postcode` VARCHAR(6) NOT NULL,
    `plaats` VARCHAR(255) NOT NULL,
    `land` VARCHAR(255) NOT NULL,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL
);

-- crosstabel tussen klant en adres, want een klant kan meerdere adressen hebben (bijvoorbeeld thuis en werk), en een adres kan ook aan meerdere klanten gekoppeld zijn (bijvoorbeeld gezinsleden die op hetzelfde adres wonen)
CREATE TABLE `klant_adres` (
    `account_id` INT NOT NULL,
    `adres_id` INT NOT NULL,
    `type` ENUM('prive', 'werk', 'primair') NOT NULL, 
    `deleted_at` TIMESTAMP NULL DEFAULT NULL, -- soft delete veld voor deze relatie
    PRIMARY KEY (`account_id`, `adres_id`),
    FOREIGN KEY (`account_id`) REFERENCES `klant`(`account_id`),
    FOREIGN KEY (`adres_id`) REFERENCES `adres`(`adres_id`)
);

DELIMITER //
CREATE TRIGGER klant_adres_check_primair_ins
BEFORE INSERT ON klant_adres
FOR EACH ROW
BEGIN
  IF NEW.type = 'primair' AND NEW.deleted_at IS NULL THEN
    IF EXISTS (
      SELECT 1 FROM klant_adres
      WHERE account_id = NEW.account_id
        AND type = 'primair'
        AND deleted_at IS NULL
    ) THEN
      SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Een klant kan maar één primair adres hebben';
    END IF;
  END IF;
END//

CREATE TRIGGER klant_adres_check_primair_upd
BEFORE UPDATE ON klant_adres
FOR EACH ROW
BEGIN
  IF NEW.type = 'primair' AND NEW.deleted_at IS NULL THEN
    IF EXISTS (
      SELECT 1 FROM klant_adres
      WHERE account_id = NEW.account_id
        AND type = 'primair'
        AND deleted_at IS NULL
        AND adres_id != NEW.adres_id
    ) THEN
      SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Een klant kan maar één primair adres hebben';
    END IF;
  END IF;
END//
DELIMITER ;

CREATE TABLE `afhaal_locatie` (
    `afhaal_locatie_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `naam` VARCHAR(100) NOT NULL,
    `type` ENUM('boer', 'magazijn', 'vrijwilliger') NOT NULL,
    `adres_id` INT NOT NULL,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL,

    FOREIGN KEY (`adres_id`) REFERENCES `adres`(`adres_id`)
);

-- ============================================================
-- WINKELWAGEN
-- Een klant kan producten in de winkelwagen plaatsen vóór het afrekenen.
-- De winkelwagen bestaat los van een bestelling: pas wanneer de klant op
-- "Bestellen" klikt, wordt er een bestelling aangemaakt op basis van de
-- winkelwagen-regels. Daarna worden de winkelwagen-regels verwijderd.
--
-- Stroom in de applicatielaag:
--   1. Klant voegt product toe  → INSERT in winkelwagen_regel
--   2. Klant klikt "Bestellen"  →
--        a. INSERT in bestelling   (met bezorgkosten, totaalprijs, adres)
--        b. INSERT in bestel_regel (kopieer uit winkelwagen, sla huidige prijs op)
--        c. DELETE uit winkelwagen_regel (winkelwagen leegmaken)
-- ============================================================
CREATE TABLE `winkelwagen` (
    `winkelwagen_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `account_id` INT NOT NULL,

    FOREIGN KEY (`account_id`) REFERENCES `klant`(`account_id`)
);

CREATE TABLE `winkelwagen_regel` (
  `winkelwagen_regel_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `winkelwagen_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `aantal` INT NOT NULL CHECK (`aantal` >= 1),
  `toegevoegd_op` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  -- voorkomt dat hetzelfde product twee keer als aparte rij wordt toegevoegd
  -- de applicatie moet bij een dubbel product de bestaande rij updaten (aantal + 1)
  UNIQUE (`winkelwagen_id`, `product_id`),
  FOREIGN KEY (`winkelwagen_id`) REFERENCES `winkelwagen`(`winkelwagen_id`) ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `product`(`product_id`)
);

-- ============================================================
-- BESTELLING
-- Een bestelling wordt pas aangemaakt op het moment dat de klant afrekent.
-- De bezorgkosten en totaalprijs worden op dat moment berekend en opgeslagen.
-- ============================================================

CREATE TABLE `bestelling` (
  `bestelling_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `account_id` INT NOT NULL,
  `bestel_datum` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `bezorg_kosten` DECIMAL(10, 2) NOT NULL CHECK (`bezorg_kosten` >= 0),
  `totaal_prijs_bestelling` DECIMAL(10, 2) NOT NULL CHECK (`totaal_prijs_bestelling` >= 0),
  `status` ENUM('betaald', 'in behandeling', 'verzonden', 'geleverd', 'geannuleerd', 'retour') NOT NULL,
  `adres_id` INT NULL, -- adres_id is NULL als de klant kiest voor afhalen bij een afhaal_locatie in plaats van bezorgen
  `afhaal_locatie_id` INT NULL,
  `verzendmethode` ENUM('bezorgen', 'afhalen') NOT NULL DEFAULT 'bezorgen',
  `deleted_at` TIMESTAMP NULL DEFAULT NULL, -- soft delete veld
  -- verwijst naar klant ipv account zodat beheeraccounts geen bestellingen kunnen plaatsen
  FOREIGN KEY (`account_id`) REFERENCES `klant`(`account_id`),
  FOREIGN KEY (`adres_id`) REFERENCES `adres`(`adres_id`),
  FOREIGN KEY (`afhaal_locatie_id`) REFERENCES `afhaal_locatie`(`afhaal_locatie_id`)
);

DELIMITER //
CREATE TRIGGER bestelling_check_verzendmethode_ins
BEFORE INSERT ON bestelling
FOR EACH ROW
BEGIN
  IF NEW.verzendmethode = 'bezorgen' AND NEW.adres_id IS NULL THEN
    SIGNAL SQLSTATE '45000'
      SET MESSAGE_TEXT = 'Bij bezorgen moet een adres_id opgegeven worden';
  END IF;
  IF NEW.verzendmethode = 'afhalen' AND NEW.afhaal_locatie_id IS NULL THEN
    SIGNAL SQLSTATE '45000'
      SET MESSAGE_TEXT = 'Bij afhalen moet een afhaal_locatie_id opgegeven worden';
  END IF;
END//

CREATE TRIGGER bestelling_check_verzendmethode_upd
BEFORE UPDATE ON bestelling
FOR EACH ROW
BEGIN
  IF NEW.verzendmethode = 'bezorgen' AND NEW.adres_id IS NULL THEN
    SIGNAL SQLSTATE '45000'
      SET MESSAGE_TEXT = 'Bij bezorgen moet een adres_id opgegeven worden';
  END IF;
  IF NEW.verzendmethode = 'afhalen' AND NEW.afhaal_locatie_id IS NULL THEN
    SIGNAL SQLSTATE '45000'
      SET MESSAGE_TEXT = 'Bij afhalen moet een afhaal_locatie_id opgegeven worden';
  END IF;
END//
DELIMITER ;

-- ============================================================
-- BESTEL_REGEL
-- Een bestel_regel is één productregel binnen een bevestigde bestelling.
-- bestelling_id is NOT NULL: een bestel_regel hoort altijd bij een bestelling.
-- De winkelwagen heeft zijn eigen tabel (winkelwagen_regel) zodat de twee
-- concepten — "in de kar leggen" en "besteld hebben" — gescheiden blijven.
--
-- prijs_per_stuk wordt opgeslagen op het moment van bestellen, zodat een
-- latere prijswijziging van het product de historische bestelling niet beïnvloedt.
-- ============================================================

CREATE TABLE `bestel_regel` (
  `bestel_regel_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `bestelling_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `aantal` INT NOT NULL CHECK (`aantal` >= 1), -- minimaal 1, anders heeft de bestel_regel geen zin
  -- prijs vastgelegd op moment van bestellen, los van de huidige productprijs
  `prijs_per_stuk` DECIMAL(10, 2) NOT NULL,

  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  -- voorkomt dat hetzelfde product twee keer als aparte regel in dezelfde bestelling staat
  UNIQUE (`bestelling_id`, `product_id`),
  FOREIGN KEY (`product_id`) REFERENCES `product`(`product_id`),
  FOREIGN KEY (`bestelling_id`) REFERENCES `bestelling`(`bestelling_id`)
);



CREATE TABLE `log_regel` (
  `log_regel_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `account_id` INT NOT NULL,
  `log_regel` TEXT,
  `soort_log` ENUM('inlog', 'actie', 'fout') NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (`account_id`) REFERENCES `beheer`(`account_id`)
);

CREATE TABLE `zoekterm` (
    `zoekterm` VARCHAR(500) NOT NULL PRIMARY KEY,
    `aantal` INT NOT NULL
);

CREATE TABLE `contact_formulier` (
    `contact_formulier_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `naam` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `bericht` TEXT NOT NULL,
    `verzonden_op` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `account_id` INT NULL,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL,

    FOREIGN KEY (`account_id`) REFERENCES `account`(`account_id`)
);
-- ============================================================
-- SOFT DELETE BEVEILIGING
-- Op alle tabellen met deleted_at wordt een fysieke DELETE geblokkeerd.
-- De applicatie moet in plaats daarvan UPDATE ... SET deleted_at = NOW() gebruiken.
-- Tabellen zonder beveiliging (fysieke DELETE toegestaan): winkelwagen, winkelwagen_regel, zoekterm.
-- log_regel: geen delete en geen soft delete toegestaan.
-- ============================================================
DELIMITER //

CREATE TRIGGER product_no_delete BEFORE DELETE ON product FOR EACH ROW BEGIN SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Gebruik UPDATE deleted_at voor soft delete op product'; END//
CREATE TRIGGER voorraad_no_delete BEFORE DELETE ON voorraad FOR EACH ROW BEGIN SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Gebruik UPDATE deleted_at voor soft delete op voorraad'; END//
CREATE TRIGGER categorie_no_delete BEFORE DELETE ON categorie FOR EACH ROW BEGIN SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Gebruik UPDATE deleted_at voor soft delete op categorie'; END//
CREATE TRIGGER product_categorie_no_delete BEFORE DELETE ON product_categorie FOR EACH ROW BEGIN SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Gebruik UPDATE deleted_at voor soft delete op product_categorie'; END//
CREATE TRIGGER tag_no_delete BEFORE DELETE ON tag FOR EACH ROW BEGIN SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Gebruik UPDATE deleted_at voor soft delete op tag'; END//
CREATE TRIGGER product_tag_no_delete BEFORE DELETE ON product_tag FOR EACH ROW BEGIN SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Gebruik UPDATE deleted_at voor soft delete op product_tag'; END//
CREATE TRIGGER account_no_delete BEFORE DELETE ON account FOR EACH ROW BEGIN SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Gebruik UPDATE deleted_at voor soft delete op account'; END//
CREATE TRIGGER klant_no_delete BEFORE DELETE ON klant FOR EACH ROW BEGIN SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Gebruik UPDATE deleted_at voor soft delete op klant'; END//
CREATE TRIGGER beheer_no_delete BEFORE DELETE ON beheer FOR EACH ROW BEGIN SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Gebruik UPDATE deleted_at voor soft delete op beheer'; END//
CREATE TRIGGER adres_no_delete BEFORE DELETE ON adres FOR EACH ROW BEGIN SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Gebruik UPDATE deleted_at voor soft delete op adres'; END//
CREATE TRIGGER klant_adres_no_delete BEFORE DELETE ON klant_adres FOR EACH ROW BEGIN SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Gebruik UPDATE deleted_at voor soft delete op klant_adres'; END//
CREATE TRIGGER afhaal_locatie_no_delete BEFORE DELETE ON afhaal_locatie FOR EACH ROW BEGIN SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Gebruik UPDATE deleted_at voor soft delete op afhaal_locatie'; END//
CREATE TRIGGER bestelling_no_delete BEFORE DELETE ON bestelling FOR EACH ROW BEGIN SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Gebruik UPDATE deleted_at voor soft delete op bestelling'; END//
CREATE TRIGGER bestel_regel_no_delete BEFORE DELETE ON bestel_regel FOR EACH ROW BEGIN SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Gebruik UPDATE deleted_at voor soft delete op bestel_regel'; END//
CREATE TRIGGER contact_formulier_no_delete BEFORE DELETE ON contact_formulier FOR EACH ROW BEGIN SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Gebruik UPDATE deleted_at voor soft delete op contact_formulier'; END//
CREATE TRIGGER log_regel_no_delete BEFORE DELETE ON log_regel FOR EACH ROW BEGIN SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Log regels mogen nooit verwijderd worden'; END//

DELIMITER ;

/*--------------------------
--VIEWS
--------------------------*/
-- Deze views zijn bewaard omdat ze technische valkuilen bevatten die bij
-- het herschrijven in de DAO makkelijk fout gaan. De overige views zijn
-- weggelaten en worden als gewone queries in de DAO-laag geïmplementeerd.

-- ============================================================
-- v_product_voorraad_status
-- Bewaard omdat de deleted_at filter van voorraad op de JOIN staat
-- en niet in de WHERE. Dit is bewust: een WHERE-filter zou producten
-- zonder voorraad-rij uitsluiten, terwijl een LEFT JOIN die producten
-- juist zichtbaar moet houden (met NULL-waarden voor voorraad).
-- Dit onderscheid is subtiel en foutgevoelig bij herschrijven.
-- ============================================================
CREATE VIEW v_product_voorraad_status AS
SELECT
    product.product_id,
    product.naam AS product_naam,
    product.prijs,
    voorraad.product_id AS voorraad_product_id,
    voorraad.aantal,
    voorraad.status
FROM product
LEFT JOIN voorraad
    ON voorraad.product_id = product.product_id
    AND voorraad.deleted_at IS NULL   -- ← hier, niet in WHERE
WHERE product.deleted_at IS NULL;

-- ============================================================
-- v_product_categorie_tag
-- Bewaard omdat deze view twee onafhankelijke veel-op-veel relaties
-- (product-categorie en product-tag) combineert via GROUP_CONCAT.
-- Zonder GROUP BY ontstaat een Cartesian product: een product met
-- twee categorieën én twee tags levert anders vier rijen op (2x2)
-- in plaats van één. De deleted_at filters staan op de JOIN zodat
-- soft-deleted koppelingen niet verschijnen zonder actieve records
-- te verliezen. Deze combinatie van technieken is complex genoeg
-- om herschrijven foutgevoelig te maken.
-- ============================================================
CREATE VIEW v_product_categorie_tag AS
SELECT
  product.product_id,
  product.naam AS product_naam,
  GROUP_CONCAT(DISTINCT categorie.categorie ORDER BY categorie.categorie SEPARATOR ', ') AS categorieen,
  GROUP_CONCAT(DISTINCT tag.tag ORDER BY tag.tag SEPARATOR ', ') AS tags
FROM product
LEFT JOIN product_categorie
    ON product_categorie.product_id = product.product_id
    AND product_categorie.deleted_at IS NULL
LEFT JOIN categorie
    ON categorie.categorie = product_categorie.categorie
    AND categorie.deleted_at IS NULL
LEFT JOIN product_tag
    ON product_tag.product_id = product.product_id
    AND product_tag.deleted_at IS NULL
LEFT JOIN tag
    ON tag.tag = product_tag.tag
    AND tag.deleted_at IS NULL
WHERE product.deleted_at IS NULL
GROUP BY product.product_id, product.naam;


