<?php

/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2013 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

class EventgalleryLibraryManagerShipping extends  EventgalleryLibraryManagerMethode
{

    protected $_tablename = '#__eventgallery_shippingmethod';

    protected static $_instance;

    public static function getInstance()
    {
        if (self::$_instance == null) {
            self::$_instance = new EventgalleryLibraryManagerShipping();
        }
        return self::$_instance;
    }

}
