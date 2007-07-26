# $Id: uninstall.jpackage.sql 134 2006-07-28 13:21:16Z schmalls $ #

###########################
##      Delete data      ##
###########################
DELETE FROM `#__jpackage_sites`;

###########################
##     Drop Table(s)     ##
###########################
DROP TABLE `#__jpackage_sites`;