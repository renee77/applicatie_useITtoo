USE useITtooApplicatieDB;

ALTER TABLE `account`
  ADD COLUMN `type` ENUM('klant', 'beheerder') NOT NULL
    AFTER `telefoon`;