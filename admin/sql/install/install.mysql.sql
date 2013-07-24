CREATE TABLE IF NOT EXISTS `#__eventgallery_comment` (
  `id` int(11) NOT NULL auto_increment,
  `file` varchar(125) NOT NULL,
  `folder` varchar(125) NOT NULL,
  `text` text NOT NULL,
  `name` varchar(255) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `published` tinyint(4) NOT NULL default '1',
  `date` datetime NOT NULL,
  `email` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `filefolderkey` (`folder`,`file`)
);

CREATE TABLE IF NOT EXISTS `#__eventgallery_file` (
  `id` int(11) NOT NULL auto_increment,
  `folder` varchar(125) NOT NULL,
  `file` varchar(125) NOT NULL,
  `width` int(10),
  `height` int(10),
  `caption` text,
  `title` text,
  `exif` text,
  `ordering` int(10),
  `ismainimage` tinyint(4) NOT NULL default '0',
  `ismainimageonly` tinyint(4) NOT NULL default '0',
  `hits` int(11) NOT NULL default '0',
  `published` tinyint(4) NOT NULL default '1',
  `allowcomments` tinyint(4) NOT NULL default '1',
  `userid` int(11) NOT NULL,
  `lastmodified` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `file` (`folder`,`file`),
  KEY `index_file` (`file`),
  KEY `index_folder` (`folder`)
);


CREATE TABLE IF NOT EXISTS `#__eventgallery_folder` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `picasakey` varchar(125) DEFAULT NULL,
  `password` VARCHAR( 250 ) NOT NULL,
  `cartable` TINYINT( 1 ) NOT NULL DEFAULT  '1',
  `tags` text,
  `description` varchar(255) NOT NULL,
  `date` datetime NOT NULL,
  `published` tinyint(4) NOT NULL DEFAULT '1',
  `folder` varchar(125) NOT NULL,
  `text` text,
  `userid` int(11) NOT NULL,
  `lastmodified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ordering` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `folder` (`folder`)
);

