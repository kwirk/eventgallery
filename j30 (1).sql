
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
  `ordering` int(11) NOT NULL DEFAULT 0,
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
  `displayname` text DEFAULT NULL,
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
  `message` text DEFAULT NULL,
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
  `message` text DEFAULT NULL,
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
  `displayname` text DEFAULT NULL,
  `description` text DEFAULT NULL,
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
  `displayname` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(8,2) NOT NULL,
  `currency` varchar(3) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
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
  `displayname` text DEFAULT NULL,
  `description` text DEFAULT NULL,  
  `price` decimal(8,2) NOT NULL,
  `currency` varchar(3) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
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
  `displayname` text DEFAULT NULL,
  `description` text DEFAULT NULL,  
  `price` decimal(8,2) NOT NULL,
  `currency` varchar(3) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `rule` int(11) DEFAULT NULL,
  `modified` timestamp NULL DEFAULT NULL,
  `created` timestamp NULL DEFAULT NULL,
  
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


--
-- Daten für Tabelle `ztx1s_eventgallery_imagetype`
--

INSERT INTO `ztx1s_eventgallery_imagetype` (`id`, `type`, `size`, `price`, `currency`, `name`, `displayname`, `description`, `note`, `modified`, `created`) VALUES
(1, 'paper', '13x18', 2.00, 'EUR', 'Fotoabzug 13x18', '{"en-GB":"Print 5x7","de-DE":"Foto 13x18"}', '{"en-GB":"A print with the size of 5x7 on premium photo paper","de-DE":"Ein Abzug der Größe 13x18 auf Premium-Fotopapier"}', 'I''ll order this using Pixum.', '0000-00-00 00:00:00', NULL),
(2, 'paper', '10x15', 1.14, 'EUR', 'Fotoabzug 10x15', '{"en-GB":"Print 4x5","de-DE":"Foto 11x13"}', '{"en-GB":"A print with the size of 4x5 on premium photo paper","de-DE":"Ein Abzug der Größe 11x13 auf Premium-Fotopapier"}', 'I''ll order this using Pixum', NULL, NULL),
(3, 'digital', '20 MP', 12.40, 'EUR', 'Digitale Kopie', '{"en-GB":"Digital Copy","de-DE":"Digitale Kopie"}', '{"en-GB":"A digital copy of the original image","de-DE":"Eine Kopie des originalen Bildes."}', 'Copy from my hard drive', '0000-00-00 00:00:00', NULL);

--
-- Daten für Tabelle `ztx1s_eventgallery_imagetypeset`
--

INSERT INTO `ztx1s_eventgallery_imagetypeset` (`id`, `name`, `description`, `note`, `default`, `modified`, `created`) VALUES
(1, 'Fotos teuer', NULL, NULL, 0, '0000-00-00 00:00:00', NULL),
(2, 'Fotos billig', NULL, NULL, 0, NULL, NULL);

--
-- Daten für Tabelle `ztx1s_eventgallery_imagetypeset_imagetype_assignment`
--

INSERT INTO `ztx1s_eventgallery_imagetypeset_imagetype_assignment` (`typesetid`, `typeid`, `default`, `ordering`, `modified`, `created`) VALUES
(1, 1, 0, 1, '0000-00-00 00:00:00', NULL),
(1, 2, 0, 2, NULL, NULL),
(1, 3, 0, 3, '0000-00-00 00:00:00', NULL);

-- Daten für Tabelle `ztx1s_eventgallery_paymentmethod`
--

INSERT INTO `ztx1s_eventgallery_paymentmethod` (`id`, `name`, `displayname`, `description`, `price`, `currency`, `active`,`modified`, `created`) VALUES
(1, 'Cash on Pickup', '{"en-GB":"Cash on pickup","de-DE":"Zahlung bei Abholung"}', '{"en-GB":"Pay when you pick up your order","de-DE":"Die Bezahlung erfolgt bei Abholung"}', 0.00, 'EUR', 1, '0000-00-00 00:00:00', NULL);

--
-- Daten für Tabelle `ztx1s_eventgallery_shippingmethod`
--

INSERT INTO `ztx1s_eventgallery_shippingmethod` (`id`, `name`, `displayname`, `description`, `price`, `currency`, `active`, `modified`, `created`) VALUES
(1, 'pickup', '{"en-GB":"Pick up","de-DE":"Abholung"}', '{"en-GB":"Pick up your order at a specific address","de-DE":"Selbstabholung an einer bestimmten Adresse"}', 0.00, 'EUR', 1, '0000-00-00 00:00:00', NULL);

--
-- Daten für Tabelle `ztx1s_eventgallery_surcharge`
--

INSERT INTO `ztx1s_eventgallery_surcharge` (`id`, `name`, `displayname`, `description`, `price`, `currency`, `active`, `rule`, `modified`, `created`) VALUES
(1, 'surcharge', '{"en-GB":"Surcharge","de-DE":"Auftragspauschale"}', '{"en-GB":"Surcharge","de-DE":"Auftragspauschale"}', 0.50, 'EUR', 1, NULL, '0000-00-00 00:00:00', NULL);


-- ALTER TABLE  `ztx1s_eventgallery_file` ADD  `typesetid` text AFTER `folder`