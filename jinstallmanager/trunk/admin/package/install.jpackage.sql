# $Id: install.jpackage.sql 202 2006-08-20 07:05:55Z schmalls $ #

###########################
## JPackage Sites Table  ##
###########################
CREATE TABLE IF NOT EXISTS `#__jpackage_sites` (
	`site_id` int(11) NOT NULL auto_increment,
	`name` text NOT NULL default '',
	`url` text NOT NULL default '',
	`username` varchar(255) NOT NULL default '',
	`password` varchar(255) NOT NULL default '',
	`method` varchar(255) NOT NULL default '',
	PRIMARY KEY  (`site_id`)
) ENGINE=MyISAM;

###########################
##  JPackage Sites Data  ##
###########################
INSERT INTO `#__jpackage_sites`
	VALUES (1, 'Test Server 1', 'php5.test.countercubed.com/joomla/xmlrpc/', '', '', 'http')
	ON DUPLICATE KEY UPDATE `url` = 'php5.test.countercubed.com/joomla/xmlrpc/';

INSERT INTO `#__jpackage_sites`
	VALUES (2, 'Test Server 1', 'test.countercubed.com/joomla/xmlrpc/', '', '', 'http')
	ON DUPLICATE KEY UPDATE `url` = 'test.countercubed.com/joomla/xmlrpc/';