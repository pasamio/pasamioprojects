DROP TABLE IF EXISTS `#__advancedtools_menu`;

CREATE TABLE `#__advancedtools_menu` (
`menuid` 	INT(11) AUTO_INCREMENT,
`name` 	VARCHAR(100) NOT NULL,
`link`	VARCHAR(255) NOT NULL,
`class` 	VARCHAR(50) NOT NULL,
`csspath`	VARCHAR(255) NOT NULL,
`cssfile` VARCHAR(20) NOT NULL,
`published` INT NOT NULL,
PRIMARY KEY (`menuid`)
);

INSERT INTO `#__advancedtools_menu` VALUES(0, 'Update Manager', 'index.php?option=com_jupdateman', 'class:install', '', '', 1);
INSERT INTO `#__advancedtools_menu` VALUES(0, 'Package Manager', 'index.php?option=com_jpackageman', 'class:module', '', '', 1);
INSERT INTO `#__advancedtools_menu` VALUES(0, 'Library Manager', 'index.php?option=com_jlibman', 'class:plugin', '', '', 1);