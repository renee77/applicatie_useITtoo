-- =============================================================
-- DCL useITtooApplicatieDB
-- Data Control Language: applicatiegebruiker en rechten
-- =============================================================
--
-- ARCHITECTUURKEUZE — één databasegebruiker voor de applicatie:
--
--   De backend-applicatie communiceert uitsluitend via de gebruiker
--   'useittoo_app' met de database. Authenticatie en autorisatie
--   van klanten en medewerkers worden volledig afgehandeld in de
--   applicatielaag — de database kent geen individuele gebruikers.
--
--   Rechten:
--     - SELECT, INSERT, UPDATE op alle tabellen die de app leest
--       of schrijft.
--     - DELETE uitsluitend op winkelwagen, winkelwagen_regel en
--       zoekterm. Alle andere verwijderingen verlopen via soft delete
--       (UPDATE op deleted_at), afgedwongen via BEFORE DELETE triggers
--       in het DDL.
--     - log_regel: uitsluitend SELECT en INSERT. Een audittrail is
--       alleen betrouwbaar als hij nooit gewijzigd of verwijderd
--       kan worden. Dit wordt aanvullend afgedwongen via een
--       BEFORE DELETE trigger op log_regel in het DDL.
--     - voorraad: geen INSERT — de trigger product_insert_voorraad
--       maakt automatisch een voorraad-rij aan bij elke product-INSERT.
--
-- =============================================================

USE useITtooApplicatieDB;

DROP USER IF EXISTS 'useittoo_app'@'localhost';
CREATE USER 'useittoo_app'@'localhost' IDENTIFIED BY 'WACHTWOORD_HIER_INVULLEN';

-- PRODUCTEN EN VOORRAAD
-- INSERT op voorraad is niet nodig: de trigger product_insert_voorraad
-- maakt automatisch een voorraad-rij aan bij elke nieuwe product-INSERT.
GRANT SELECT, INSERT, UPDATE ON useITtooApplicatieDB.product TO 'useittoo_app'@'localhost';
GRANT SELECT, UPDATE ON useITtooApplicatieDB.voorraad TO 'useittoo_app'@'localhost';

-- CATEGORIEËN EN TAGS
GRANT SELECT, INSERT, UPDATE ON useITtooApplicatieDB.categorie TO 'useittoo_app'@'localhost';
GRANT SELECT, INSERT, UPDATE ON useITtooApplicatieDB.product_categorie TO 'useittoo_app'@'localhost';
GRANT SELECT, INSERT, UPDATE ON useITtooApplicatieDB.tag TO 'useittoo_app'@'localhost';
GRANT SELECT, INSERT, UPDATE ON useITtooApplicatieDB.product_tag TO 'useittoo_app'@'localhost';

-- ACCOUNTS, KLANTEN EN BEHEER
GRANT SELECT, INSERT, UPDATE ON useITtooApplicatieDB.account TO 'useittoo_app'@'localhost';
GRANT SELECT, INSERT, UPDATE ON useITtooApplicatieDB.klant TO 'useittoo_app'@'localhost';
GRANT SELECT, INSERT, UPDATE ON useITtooApplicatieDB.beheer TO 'useittoo_app'@'localhost';

-- ADRESSEN
GRANT SELECT, INSERT, UPDATE ON useITtooApplicatieDB.adres TO 'useittoo_app'@'localhost';
GRANT SELECT, INSERT, UPDATE ON useITtooApplicatieDB.klant_adres TO 'useittoo_app'@'localhost';

-- AFHAALLOCATIES
GRANT SELECT, INSERT, UPDATE ON useITtooApplicatieDB.afhaal_locatie TO 'useittoo_app'@'localhost';

-- WINKELWAGEN
-- DELETE toegestaan: na het afrekenen wordt de winkelwagen fysiek leeggemaakt.
GRANT SELECT, INSERT, UPDATE, DELETE ON useITtooApplicatieDB.winkelwagen TO 'useittoo_app'@'localhost';
GRANT SELECT, INSERT, UPDATE, DELETE ON useITtooApplicatieDB.winkelwagen_regel TO 'useittoo_app'@'localhost';

-- BESTELLINGEN EN BESTELREGELS
GRANT SELECT, INSERT, UPDATE ON useITtooApplicatieDB.bestelling TO 'useittoo_app'@'localhost';
GRANT SELECT, INSERT, UPDATE ON useITtooApplicatieDB.bestel_regel TO 'useittoo_app'@'localhost';

-- CONTACTFORMULIER
GRANT SELECT, INSERT, UPDATE ON useITtooApplicatieDB.contact_formulier TO 'useittoo_app'@'localhost';

-- ZOEKTERM
-- DELETE toegestaan: zoektermen kennen geen soft delete.
GRANT SELECT, INSERT, UPDATE, DELETE ON useITtooApplicatieDB.zoekterm TO 'useittoo_app'@'localhost';

-- LOGGING
-- Geen UPDATE of DELETE: de audittrail mag nooit worden aangepast of verwijderd.
GRANT SELECT, INSERT ON useITtooApplicatieDB.log_regel TO 'useittoo_app'@'localhost';

-- VIEWS
GRANT SELECT ON useITtooApplicatieDB.v_product_voorraad_status TO 'useittoo_app'@'localhost';
GRANT SELECT ON useITtooApplicatieDB.v_product_categorie_tag TO 'useittoo_app'@'localhost';

FLUSH PRIVILEGES;