DROP TABLE IF EXISTS `jos_jdevtools_trackerlinks`;

CREATE TABLE `jos_jdevtools_trackerlinks` (
	`lid` 			int(11) 	NOT NULL auto_increment,
	`url`			text		NOT NULL,
	`lastupdate`		datetime	NOT NULL,
	`status`			varchar(20)	NOT NULL,
	PRIMARY KEY		(`lid`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `jos_jdevtools_userlinks`;

CREATE TABLE `jos_jdevtools_userlinks` (
	`lid`			int(11)		NOT NULL,
	`uid`			int(11)		NOT NULL,
	PRIMARY KEY		(`lid`,`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;	