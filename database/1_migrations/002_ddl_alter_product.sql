USE useITtooApplicatieDB;

ALTER TABLE `product`
    MODIFY COLUMN `verkoop_gewicht` DECIMAL(10,2) NOT NULL,
    MODIFY COLUMN `foto_url` VARCHAR(500) DEFAULT NULL,
    ADD COLUMN `eenheid` ENUM('kg', 'gr', 'stuks', 'ml') NOT NULL 
        AFTER `verkoop_gewicht`,
    ADD CONSTRAINT chk_naam_lengte CHECK (LENGTH(naam)>= 2);

