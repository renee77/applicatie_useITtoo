USE useITtooApplicatieDB;

ALTER TABLE `product`
    MODIFY COLUMN `verkoop_gewicht` DECIMAL(10,2) NOT NULL,
    ADD COLUMN `eenheid` ENUM('kg', 'gr', 'stuks', 'per bos', 'ml', 'per pot') NOT NULL 
        AFTER `verkoop_gewicht`;