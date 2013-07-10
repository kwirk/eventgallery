DROP TABLE IF EXISTS `ztx1s_eventgallery_sequence`;
CREATE TABLE IF NOT EXISTS `ztx1s_eventgallery_sequence` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `value` int(1) DEFAULT NULL,
   PRIMARY KEY (`id`)
);
--
-- Tabellenstruktur für Tabelle `ztx1s_eventgallery_imagelineitem`
--
DROP TABLE IF EXISTS `ztx1s_eventgallery_imagelineitem`;
CREATE TABLE IF NOT EXISTS `ztx1s_eventgallery_imagelineitem` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `folder` varchar(255) NOT NULL,
  `file` varchar(255) NOT NULL,
  `quantity` int(10) unsigned NOT NULL DEFAULT '1',
  `imagetypeid` int(11) DEFAULT NULL,
  `taxrate` int(3) DEFAULT 0,
  `price` decimal(8,2) NOT NULL,
  `singleprice` decimal(8,2) NOT NULL,
  `currency` varchar(3) NOT NULL,
  `lineitemcontainerid` varchar(50) DEFAULT NULL,
  `modified` timestamp NULL DEFAULT NULL,
  `created` timestamp NULL DEFAULT NULL,  
  PRIMARY KEY (`id`),
  KEY `id_idx1` (`lineitemcontainerid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Tabellenstruktur für Tabelle `ztx1s_eventgallery_servicelineitem`
--
DROP TABLE IF EXISTS `ztx1s_eventgallery_servicelineitem`;
CREATE TABLE IF NOT EXISTS `ztx1s_eventgallery_servicelineitem` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `methodid` int(4) DEFAULT NULL,
  `lineitemcontainerid` varchar(50) DEFAULT NULL,
  `type` int(4) DEFAULT NULL,
  `quantity` int(10) unsigned NOT NULL DEFAULT '1',
  `name` varchar(45) DEFAULT NULL,
  `displayname` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `data` text DEFAULT NULL,
  `taxrate` int(3) DEFAULT 0,
  `price` decimal(8,2) NOT NULL,
  `singleprice` decimal(8,2) NOT NULL,
  `currency` varchar(3) NOT NULL,
  `ordering` int(11) NOT NULL DEFAULT 0,
  `modified` timestamp NULL DEFAULT NULL,
  `created` timestamp NULL DEFAULT NULL,  
  PRIMARY KEY (`id`),
  KEY `id_idx1` (`lineitemcontainerid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------
DROP TABLE IF EXISTS `ztx1s_eventgallery_imagetypeset`;
CREATE TABLE IF NOT EXISTS `ztx1s_eventgallery_imagetypeset` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `note` text DEFAULT NULL,
  `default` int(1) NOT NULL DEFAULT 0,
  `ordering` int NULL DEFAULT NULL,
  `published` int(1) NULL DEFAULT NULL,
  `modified` timestamp NULL DEFAULT NULL,
  `created` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `ztx1s_eventgallery_useraddress`;
CREATE TABLE IF NOT EXISTS `ztx1s_eventgallery_useraddress` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` varchar(45) NOT NULL,
  `firstname` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `address1` varchar(255) DEFAULT NULL,
  `address2` varchar(255) DEFAULT NULL,
  `address3` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `zip` varchar(10) DEFAULT NULL,
  `default` BOOLEAN NOT NULL DEFAULT FALSE,
  `modified` timestamp NULL DEFAULT NULL,
  `created` timestamp NULL DEFAULT NULL,

  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `ztx1s_eventgallery_staticaddress`;
CREATE TABLE IF NOT EXISTS `ztx1s_eventgallery_staticaddress` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `address1` varchar(255) DEFAULT NULL,
  `address2` varchar(255) DEFAULT NULL,
  `address3` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `zip` varchar(10) DEFAULT NULL,
  `valid` int(1) DEFAULT 0,
  `modified` timestamp NULL DEFAULT NULL,
  `created` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `ztx1s_eventgallery_imagetypeset_imagetype_assignment`;
CREATE TABLE IF NOT EXISTS `ztx1s_eventgallery_imagetypeset_imagetype_assignment` (
  `imagetypesetid` int(11) NOT NULL,
  `imagetypeid` int(11) NOT NULL,
  `default` int(1) NOT NULL DEFAULT 0,
  `ordering` int(11) NOT NULL DEFAULT 0,
  `modified` timestamp NULL DEFAULT NULL,
  `created` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`imagetypesetid`,`imagetypeid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;


--
-- Tabellenstruktur für Tabelle `ztx1s_eventgallery_imagetype`
--
DROP TABLE IF EXISTS `ztx1s_eventgallery_imagetype`;
CREATE TABLE IF NOT EXISTS `ztx1s_eventgallery_imagetype` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(45) DEFAULT NULL,
  `isdigital` int(1) DEFAULT 0,
  `size` varchar(45) DEFAULT NULL,
  `taxrate` int(3) DEFAULT 0,
  `price` decimal(8,2) DEFAULT NULL,
  `currency` varchar(3) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `displayname` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `published` int(1) NULL DEFAULT NULL,
  `note` text DEFAULT NULL,
  `modified` timestamp NULL DEFAULT NULL,
  `created` timestamp NULL DEFAULT NULL,

  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ztx1s_eventgallery_cart`
--
DROP TABLE IF EXISTS `ztx1s_eventgallery_cart`;
CREATE TABLE IF NOT EXISTS `ztx1s_eventgallery_cart` (
  `id` varchar(50) NOT NULL ,
  `documentno` int(11) DEFAULT NULL,
  `userid` varchar(45) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `statusid` int(11) DEFAULT NULL,
  `subtotal` decimal(8,2) DEFAULT NULL,
  `subtotalcurrency` varchar(3) NOT NULL,
  `total` decimal(8,2) DEFAULT NULL,
  `totalcurrency` varchar(3) NOT NULL,
  `billingaddressid` int(11) DEFAULT NULL,
  `shippingaddressid` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `modified` timestamp NULL DEFAULT NULL,
  `created` timestamp NULL DEFAULT NULL,

  PRIMARY KEY (`id`),
  KEY `id_idx` (`statusid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `ztx1s_eventgallery_order`;
CREATE TABLE IF NOT EXISTS `ztx1s_eventgallery_order` (
  `id` varchar(50) NOT NULL ,
  `documentno` varchar(45) DEFAULT NULL,
  `orderstatusid` int(11) DEFAULT NULL,
  `paymentstatusid` int(11) DEFAULT 0,
  `shippingstatusid` int(11) DEFAULT 0,
  `userid` varchar(45) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `subtotal` decimal(8,2) DEFAULT NULL,
  `subtotalcurrency` varchar(3) NOT NULL,
  `total` decimal(8,2) DEFAULT NULL,
  `totalcurrency` varchar(3) NOT NULL,
  `billingaddressid` int(11) DEFAULT NULL,
  `shippingaddressid` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `modified` timestamp NULL DEFAULT NULL,
  `created` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_idx` (`orderstatusid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ztx1s_eventgallery_orderstatus`
--
DROP TABLE IF EXISTS `ztx1s_eventgallery_orderstatus`;
CREATE TABLE IF NOT EXISTS `ztx1s_eventgallery_orderstatus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `default` int(1) NOT NULL DEFAULT 0,
  `type` int(2) NOT NULL DEFAULT 0,
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
DROP TABLE IF EXISTS `ztx1s_eventgallery_paymentmethod`;
CREATE TABLE IF NOT EXISTS `ztx1s_eventgallery_paymentmethod` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `displayname` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `data` text DEFAULT NULL,
  `classname` varchar(255) DEFAULT NULL,
  `taxrate` int(3) DEFAULT 0,
  `price` decimal(8,2) NOT NULL,
  `currency` varchar(3) NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `default` int(1) NOT NULL DEFAULT 0,
  `ordering` int(11) NOT NULL DEFAULT 0,
  `modified` timestamp NULL DEFAULT NULL,
  `created` timestamp NULL DEFAULT NULL,  
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ztx1s_eventgallery_shippingmethod`
--
DROP TABLE IF EXISTS `ztx1s_eventgallery_shippingmethod`;
CREATE TABLE IF NOT EXISTS `ztx1s_eventgallery_shippingmethod` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `supportsdigital` int(1) DEFAULT 0,
  `displayname` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `data` text DEFAULT NULL,
  `classname` varchar(255) DEFAULT NULL,
  `taxrate` int(3) DEFAULT 0,
  `price` decimal(8,2) NOT NULL,
  `currency` varchar(3) NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `default` int(1) NOT NULL DEFAULT 0,
  `ordering` int(11) NOT NULL DEFAULT 0,
  `modified` timestamp NULL DEFAULT NULL,
  `created` timestamp NULL DEFAULT NULL,
  
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ztx1s_eventgallery_surcharge`
--
DROP TABLE IF EXISTS `ztx1s_eventgallery_surcharge`;
CREATE TABLE IF NOT EXISTS `ztx1s_eventgallery_surcharge` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `displayname` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `data` text DEFAULT NULL, 
  `classname` varchar(255) DEFAULT NULL,
  `taxrate` int(3) DEFAULT 0,
  `price` decimal(8,2) NOT NULL,
  `currency` varchar(3) NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `ordering` int(11) NOT NULL DEFAULT 0,
  `default` int(1) NOT NULL DEFAULT 0,
  `rule` int(11) DEFAULT NULL,
  `modified` timestamp NULL DEFAULT NULL,
  `created` timestamp NULL DEFAULT NULL,
  
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;



--
-- Daten für Tabelle `ztx1s_eventgallery_imagetype`
--

INSERT INTO `ztx1s_eventgallery_imagetype` (`id`, `published`, `type`, `isdigital`, `size`, `taxrate`, `price`, `currency`, `name`, `displayname`, `description`, `note`, `modified`, `created`) VALUES
(1, 1, 'paper', 0, '13x18', 19, 0.70, 'EUR', 'Fotoabzug 13x18', '{"en-GB":"Print 5x7","de-DE":"Foto 13x18"}', '{"en-GB":"A print with the size of 5x7 on premium photo paper","de-DE":"Ein Abzug der Größe 13x18 auf Premium-Fotopapier"}', 'I''ll order this using Pixum.', '0000-00-00 00:00:00', NULL),
(2, 1, 'paper', 0, '10x15', 19, 0.90, 'EUR', 'Fotoabzug 10x15', '{"en-GB":"Print 4x5","de-DE":"Foto 11x13"}', '{"en-GB":"A print with the size of 4x5 on premium photo paper","de-DE":"Ein Abzug der Größe 11x13 auf Premium-Fotopapier"}', 'I''ll order this using Pixum', NULL, NULL),
(3, 1, 'digital', 1, '20 MP', 19, 12.40, 'EUR', 'Digitale Kopie', '{"en-GB":"Digital Copy","de-DE":"Digitale Kopie"}', '{"en-GB":"A digital copy of the original image","de-DE":"Eine Kopie des originalen Bildes."}', 'Copy from my hard drive', '0000-00-00 00:00:00', NULL),
(4, 1, 'paper', 0, '13x18', 19, 2.00, 'EUR', 'Fotoabzug Premium 13x18', '{"en-GB":"Premium Print 5x7","de-DE":"Premium Foto 13x18"}', '{"en-GB":"A print with the size of 5x7 on premium photo paper","de-DE":"Ein Abzug der Größe 13x18 auf Premium-Fotopapier"}', 'I''ll order this using Pixum.', '0000-00-00 00:00:00', NULL),
(5, 1, 'paper', 0, '10x15', 19, 2.50, 'EUR', 'Fotoabzug 10x15', '{"en-GB":"Premium Print 4x5","de-DE":"Foto 11x13"}', '{"en-GB":"A print with the size of 4x5 on premium photo paper","de-DE":"Ein Abzug der Größe 11x13 auf Premium-Fotopapier"}', 'I''ll order this using Pixum', NULL, NULL),
(6, 1, 'digital exp', 1, '20 MP', 19, 25.00, 'EUR', 'Digitale Kopie', '{"en-GB":"Digital Copy","de-DE":"Digitale Kopie"}', '{"en-GB":"A digital copy of the original image","de-DE":"Eine Kopie des originalen Bildes."}', 'Copy from my hard drive', '0000-00-00 00:00:00', NULL);
--
-- Daten für Tabelle `ztx1s_eventgallery_imagetypeset`
--

INSERT INTO `ztx1s_eventgallery_imagetypeset` (`id`, `name`, `description`, `note`, `default`, `published`, `modified`, `created`) VALUES
(1, 'Cheap images', NULL, NULL, 0, 1, '0000-00-00 00:00:00', NULL),
(2, 'Expencive images', NULL, NULL, 1, 1, NULL, NULL);

--
-- Daten für Tabelle `ztx1s_eventgallery_imagetypeset_imagetype_assignment`
--

INSERT INTO `ztx1s_eventgallery_imagetypeset_imagetype_assignment` (`imagetypesetid`, `imagetypeid`, `default`, `ordering`, `modified`, `created`) VALUES
(1, 1, 0, 1, '0000-00-00 00:00:00', NULL),
(1, 2, 1, 2, NULL, NULL),
(1, 3, 0, 4, '0000-00-00 00:00:00', NULL),
(1, 4, 0, 3, '0000-00-00 00:00:00', NULL),
(2, 4, 0, 1, '0000-00-00 00:00:00', NULL),
(2, 5, 0, 2, NULL, NULL),
(2, 6, 1, 3, '0000-00-00 00:00:00', NULL);

--
-- Daten für Tabelle `ztx1s_eventgallery_paymentmethod`
--

INSERT INTO `ztx1s_eventgallery_paymentmethod` (`id`, `classname`, `name`, `displayname`, `description`, `taxrate`, `price`, `currency`, `published`, `default`, `ordering`, `modified`, `created`, `data`) VALUES
(1, 'EventgalleryPluginsPaymentCod', 'Cash on Pickup', '{"en-GB":"Cash on pickup","de-DE":"Zahlung bei Abholung"}', '{"en-GB":"Pay when you pick up your order","de-DE":"Die Bezahlung erfolgt bei Abholung"}', 19, 0.00, 'EUR', '1', '0', '1', '0000-00-00 00:00:00', NULL, ''),
(2, 'EventgalleryPluginsPaymentCod', 'COD', '{"en-GB":"Cash on Delivery","de-DE":"Nachnahme"}', '{"en-GB":"Pay per Cash on Delivery","de-DE":"Zahlung per Nachnahme"}', 19, 2.00, 'EUR', '1','0', '2',  '0000-00-00 00:00:00', NULL, ''),
(3, 'EventgalleryPluginsPaymentPaypalPayment', 'Paypal', '{"en-GB":"Paypal","de-DE":"Paypal"}', '{"en-GB":"Bezahlung mit Paypal","de-DE":"Bezahlung mit Paypal"}', 19, 0.50, 'EUR', '1', '1', '3',  '0000-00-00 00:00:00', NULL, '{"receiver": { "email": "svenbluege-facilitator@gmail.com"}, "credentials": { "userid":"svenbluege-facilitator_api1.gmail.com", "password":"1372090428", "signature":"AJPnC-kx7R-Q9kRWBdP2GlSUumB6AxXvJxK5X6XYD9B0dr6yo0-0X8dI", "appid":"APP-80W284485P519543T"}, "endpoints" : {"api":"https://svcs.sandbox.paypal.com/AdaptivePayments/Pay", "webscr":"https://www.sandbox.paypal.com/cgi-bin/webscr" }}'),
(4, 'EventgalleryPluginsPaymentCod', 'Amazon', '{"en-GB":"Amazon","de-DE":"Amazon"}', '{"en-GB":"Bezahlung mit Amazon","de-DE":"Bezahlung mit Amazon"}', 19, 0.70, 'EUR', '0', '0', '4',  '0000-00-00 00:00:00', NULL, '');

	
   
--
-- Daten für Tabelle `ztx1s_eventgallery_shippingmethod`
--

INSERT INTO `ztx1s_eventgallery_shippingmethod` (`id`, `classname`, `name`, `displayname`, `description`, `taxrate`, `price`, `currency`, `published`, `default`, `ordering`, `modified`, `created`) VALUES
(1, 'EventgalleryPluginsShippingDhl', 'pickup', '{"en-GB":"Pick up","de-DE":"Abholung"}', '{"en-GB":"Pick up your order at a specific address","de-DE":"Selbstabholung an einer bestimmten Adresse"}', 19, 0.00, 'EUR', '1', '0', '1', '0000-00-00 00:00:00', NULL),
(2, 'EventgalleryPluginsShippingDhl', 'Hermes', '{"en-GB":"Hermes","de-DE":"Hermes"}', '{"en-GB":"Shipping by Hermes","de-DE":"Versand mit Hermes"}', 19, 5.00, 'EUR','1', '1', '2',  '0000-00-00 00:00:00', NULL),
(3, 'EventgalleryPluginsShippingDhl', 'DHL', '{"en-GB":"DHL","de-DE":"DHL"}', '{"en-GB":"Shipping by DHL","de-DE":"Versand mit DHL"}', 19, 6.00, 'EUR','1', '0', '3',  '0000-00-00 00:00:00', NULL),
(4, 'EventgalleryPluginsShippingEmail', 'email', '{"en-GB":"eMail","de-DE":"E-Mail"}', '{"en-GB":"Shipping by email","de-DE":"Versand mit Email"}', 19, 0.00, 'EUR','1', '0', '4',  '0000-00-00 00:00:00', NULL),
(5, 'EventgalleryPluginsShippingDhl', 'UPS', '{"en-GB":"UPS","de-DE":"UPS"}', '{"en-GB":"Shipping by UPS","de-DE":"Versand mit UPS"}', 19, 8.00, 'EUR','0', '0', '5',  '0000-00-00 00:00:00', NULL);

--
-- Daten für Tabelle `ztx1s_eventgallery_surcharge`
--

INSERT INTO `ztx1s_eventgallery_surcharge` (`id`, `ordering`, `classname`, `name`, `displayname`, `description`, `taxrate`, `price`, `currency`, `published`, `rule`, `modified`, `created`, `data`) VALUES
(1, 1, 'EventgalleryPluginsSurchargeStandard', 'surcharge', '{"en-GB":"Surcharge 1","de-DE":"Auftragspauschale 1"}', '{"en-GB":"Surcharge to cover expenses for this order.","de-DE":"Auftragspauschale zur Deckung von Zusatzkosten für diese Bestellung."}', 19, 2.00, 'EUR', 1, NULL, '0000-00-00 00:00:00', NULL, '{"rules":{"maxAmount":10}}'),
(2, 2, 'EventgalleryPluginsSurchargeStandard', 'surcharge', '{"en-GB":"Surcharge 2","de-DE":"Auftragspauschale 2"}', '{"en-GB":"Surcharge to cover expenses for this order.","de-DE":"Auftragspauschale zur Deckung von Zusatzkosten für diese Bestellung."}', 19, 0.50, 'EUR', 1, NULL, '0000-00-00 00:00:00', NULL, '{"rules":{"minAmount":10, "maxAmount":50}}'),
(3, 3, 'EventgalleryPluginsSurchargeStandard', 'surcharge', '{"en-GB":"Surcharge 3","de-DE":"Auftragspauschale 3"}', '{"en-GB":"Surcharge to cover expenses for this order.","de-DE":"Auftragspauschale zur Deckung von Zusatzkosten für diese Bestellung."}', 19, 0.00, 'EUR', 1, NULL, '0000-00-00 00:00:00', NULL, '{"rules":{"minAmount":50, "maxAmount":100}}');


--
-- Daten für Tabelle `ztx1s_eventgallery_orderstatus`
--

INSERT INTO `ztx1s_eventgallery_orderstatus` (`id`, `type`, `name`, `default`, `displayname`, `description`, `modified`, `created`) VALUES
(1, '0', 'new', 1, '{"en-GB":"New","de-DE":"Neu"}', '{"en-GB":"New","de-DE":"Neu"}', '0000-00-00 00:00:00', NULL),
(2, '0', 'refused', 0, '{"en-GB":"Refused","de-DE":"Abgelehnt"}', '{"en-GB":"Refused by merchant","de-DE":"Vom Anbieter abgelehnt"}', NULL, NULL),
(3, '0', 'canceled', 0, '{"en-GB":"Canceled","de-DE":"Storniert"}', '{"en-GB":"Canceled by customer","de-DE":"Durch Nutzer storniert"}', '0000-00-00 00:00:00', NULL),
(4, '0', 'in progress', 0, '{"en-GB":"In progress","de-DE":"In Bearbeitung"}', '{"en-GB":"In progress","de-DE":"In Bearbeitung"}', NULL, NULL),
(5, '0', 'completed', 0, '{"en-GB":"Completed","de-DE":"Abgeschlossen"}', '{"en-GB":"Order is completed","de-DE":"Die Bestellung ist abgeschlossen."}', '0000-00-00 00:00:00', NULL),
(6, '1', 'not shipped', 1, '{"en-GB":"In progress","de-DE":"In Bearbeitung"}', '{"en-GB":"Shipping of the order id pending.","de-DE":"Die Bestellung wurde noch nicht verschickt."}', NULL, NULL),
(7, '1', 'shipped', 0, '{"en-GB":"In progress","de-DE":"In Bearbeitung"}', '{"en-GB":"The order has been shipped.","de-DE":"Die Bestellung wurde versandt."}', NULL, NULL),
(8, '2', 'not payed', 1, '{"en-GB":"Not payed","de-DE":"Nicht bezahlt"}', '{"en-GB":"The order is not payed yet.","de-DE":"Die Bestellung wurde noch nicht bezahlt"}', NULL, NULL),
(9, '2', 'payed', 0, '{"en-GB":"Payed","de-DE":"Bezahlt"}', '{"en-GB":"The order is payed.","de-DE":"Die Bestellung wurde bezahlt."}', NULL, NULL);


-- ALTER TABLE  `ztx1s_eventgallery_folder` ADD  `imagetypesetid` int AFTER `folder`;