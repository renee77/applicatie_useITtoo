USE useITtooApplicatieDB;

ALTER TABLE `account`
  ADD COLUMN `type` ENUM('klant', 'beheer') NOT NULL
    AFTER `telefoon`;