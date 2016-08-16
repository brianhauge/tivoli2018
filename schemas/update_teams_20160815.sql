ALTER TABLE tivoli2016_teams
    ADD COLUMN `mobile` varchar(16) NOT NULL AFTER `name`,
    ADD COLUMN `email` varchar(255) NOT NULL AFTER `mobile`,
    ADD COLUMN `kreds` varchar(255) NOT NULL AFTER `email`,
    ADD COLUMN `leader` VARCHAR(255) NOT NULL AFTER `name`;