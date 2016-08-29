ALTER TABLE tivoli2016_teams
    DROP COLUMN `numberofmembers`,
    ADD COLUMN `numberofmembers` VARCHAR(20) NOT NULL AFTER `kreds`;