USE useITtooApplicatieDB;

ALTER TABLE `product`
    MODIFY COLUMN `verkoop_gewicht` DECIMAL(10,2) NOT NULL,
    MODIFY COLUMN `foto_url` VARCHAR(500) DEFAULT NULL,
    ADD COLUMN `eenheid` ENUM('kg', 'gr', 'stuks', 'ml') NOT NULL 
        AFTER `verkoop_gewicht`,
    ADD CONSTRAINT chk_naam_lengte CHECK (LENGTH(naam)>= 2);

UPDATE product SET foto_url = CONCAT(foto_url, '.jpg') WHERE foto_url IS NOT NULL;
UPDATE product SET foto_url = 'aardappels.jpg' where naam = 'aardappel';
UPDATE product SET foto_url = 'aubergine.jpg' where naam = 'aubergine';
UPDATE product SET foto_url = 'boerenkool.jpg' where naam = 'boerenkool';
UPDATE product SET foto_url = 'champignons.jpg' where naam = 'champignons';
UPDATE product SET foto_url = 'cherry_tomaatjes.jpg' where naam = 'cherry tomaatjes';
UPDATE product SET foto_url = 'framboos.jpg' where naam = 'frambozen';
UPDATE product SET foto_url = 'gala_appel.jpg' where naam = 'gala appel';
UPDATE product SET foto_url = 'groene_appel.jpg' where naam = 'jonagold appel';
UPDATE product SET foto_url = 'groene_asperges.jpg' where naam = 'groene asperges';
UPDATE product SET foto_url = 'kers.jpg' where naam = 'kersen';
UPDATE product SET foto_url = 'paprikas.jpg' where naam = 'paprika';
UPDATE product SET foto_url = 'pompoen.jpg' where naam = 'pompoen';
UPDATE product SET foto_url = 'placeholder.jpg' where naam = 'druiven';
UPDATE product SET foto_url = 'spinazie.jpg' where naam = 'spinazie';
UPDATE product SET foto_url = 'spruitjes.jpg' where naam = 'spruiten';
UPDATE product SET foto_url = 'tomaat.jpg' where naam = 'tomaten';
UPDATE product SET foto_url = 'ui.jpg' where naam = 'uien';