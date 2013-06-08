
--
-- Tabellenstruktur für Tabelle `ztx1s_eventgallery_imagelineitem`
--

CREATE TABLE IF NOT EXISTS `ztx1s_eventgallery_imagelineitem` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` int(2) NOT NULL DEFAULT 0,
  `folder` varchar(255) NOT NULL,
  `file` varchar(255) NOT NULL,
  `quantity` int(10) unsigned NOT NULL DEFAULT '1',
  `typeid` int(11) DEFAULT NULL,
  `price` decimal(8,2) NOT NULL,
  `singleprice` decimal(8,2) NOT NULL,
  `currency` varchar(3) NOT NULL,
  `lineitemcontainerid` int(11) DEFAULT NULL,
  `modified` timestamp NULL DEFAULT NULL,
  `created` timestamp NULL DEFAULT NULL,  
  PRIMARY KEY (`id`),
  KEY `id_idx` (`typeid`),
  KEY `id_idx1` (`lineitemcontainerid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `ztx1s_eventgallery_imagetypeset` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `note` text DEFAULT NULL,
  `default` BOOLEAN NOT NULL DEFAULT FALSE,
  `modified` timestamp NULL DEFAULT NULL,
  `created` timestamp NULL DEFAULT NULL,

  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `ztx1s_eventgallery_imagetypeset_imagetype_assignment` (
  `typesetid` int(11) NOT NULL,
  `typeid` int(11) NOT NULL,
  `default` int(1) NOT NULL DEFAULT 0,
  `modified` timestamp NULL DEFAULT NULL,
  `created` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`typesetid`,`typeid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;


--
-- Tabellenstruktur für Tabelle `ztx1s_eventgallery_imagetype`
--

CREATE TABLE IF NOT EXISTS `ztx1s_eventgallery_imagetype` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(45) DEFAULT NULL,
  `size` varchar(45) DEFAULT NULL,
  `price` decimal(8,2) DEFAULT NULL,
  `currency` varchar(3) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `note` text DEFAULT NULL,
  `modified` timestamp NULL DEFAULT NULL,
  `created` timestamp NULL DEFAULT NULL,

  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ztx1s_eventgallery_order`
--
CREATE TABLE IF NOT EXISTS `ztx1s_eventgallery_cart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` varchar(45) NOT NULL,
  `statusid` int(11) DEFAULT NULL,
  `subtotal` decimal(8,2) DEFAULT NULL,
  `subtotalcurrency` varchar(3) NOT NULL,
  `total` decimal(8,2) DEFAULT NULL,
  `totalcurrency` varchar(3) NOT NULL,
  `surchargeid` int(11) DEFAULT NULL,
  `paymentmethodid` int(11) DEFAULT NULL,
  `shippingmethodid` int(11) DEFAULT NULL,
  `modified` timestamp NULL DEFAULT NULL,
  `created` timestamp NULL DEFAULT NULL,

  PRIMARY KEY (`id`),
  KEY `id_idx` (`statusid`),
  KEY `id_idx1` (`paymentmethodid`),
  KEY `id_idx2` (`shippingmethodid`),
  KEY `id_idx3` (`surchargeid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `ztx1s_eventgallery_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `statusid` int(11) DEFAULT NULL,
  `userid` varchar(45) NOT NULL,
  `subtotal` decimal(8,2) DEFAULT NULL,
  `subtotalcurrency` varchar(3) NOT NULL,
  `total` decimal(8,2) DEFAULT NULL,
  `totalcurrency` varchar(3) NOT NULL,
  `surchargeid` int(11) DEFAULT NULL,
  `paymentmethodid` int(11) DEFAULT NULL,
  `shippingmethodid` int(11) DEFAULT NULL,
  `modified` timestamp NULL DEFAULT NULL,
  `created` timestamp NULL DEFAULT NULL,

  PRIMARY KEY (`id`),
  KEY `id_idx` (`statusid`),
  KEY `id_idx1` (`paymentmethodid`),
  KEY `id_idx2` (`shippingmethodid`),
  KEY `id_idx3` (`surchargeid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ztx1s_eventgallery_orderstatus`
--

CREATE TABLE IF NOT EXISTS `ztx1s_eventgallery_orderstatus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `modified` timestamp NULL DEFAULT NULL,
  `created` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ztx1s_eventgallery_paymentmethod`
--

CREATE TABLE IF NOT EXISTS `ztx1s_eventgallery_paymentmethod` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `price` decimal(8,2) NOT NULL,
  `currency` varchar(3) NOT NULL,
  `modified` timestamp NULL DEFAULT NULL,
  `created` timestamp NULL DEFAULT NULL,
  
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ztx1s_eventgallery_shippingmethod`
--

CREATE TABLE IF NOT EXISTS `ztx1s_eventgallery_shippingmethod` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `price` decimal(8,2) NOT NULL,
  `currency` varchar(3) NOT NULL,
  `modified` timestamp NULL DEFAULT NULL,
  `created` timestamp NULL DEFAULT NULL,
  
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ztx1s_eventgallery_surcharge`
--

CREATE TABLE IF NOT EXISTS `ztx1s_eventgallery_surcharge` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `price` decimal(8,2) NOT NULL,
  `currency` varchar(3) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `rule` int(11) DEFAULT NULL,
  `modified` timestamp NULL DEFAULT NULL,
  `created` timestamp NULL DEFAULT NULL,
  
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

