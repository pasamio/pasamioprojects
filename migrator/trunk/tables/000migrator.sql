# Migration Assistant
#
# This file is the SQL create for the Migration Assistant
# This file needs to included first to ensure that the migration assistant table is created first.
# 
# The migration packages will be run on the 1.5 install to basically auto install new migration
# packages on the site. 
CREATE TABLE #__migration_packages (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(100) NOT NULL,
	`url` TEXT NOT NULL,
	PRIMARY KEY(`id`)
);