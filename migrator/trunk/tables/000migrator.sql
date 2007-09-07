# Backlink Migration Assistant
#
# This file is the SQL create for backlink migration
# Backlink migration is used to keep old links active.
#
# We use jos_ instead for migration purposes

DROP TABLE IF EXISTS #__migration_backlinks; 
CREATE TABLE #__migration_backlinks (
	`itemid` INT(11) NOT NULL,
	`name` VARCHAR(100) NOT NULL,
	`url` TEXT NOT NULL,
	`sefurl` TEXT NOT NULL,
	`newurl` TEXT NOT NULL,
	PRIMARY KEY(`itemid`)
);
