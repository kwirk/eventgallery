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

class EventgalleryLibraryManagerOrderstatus extends EventgalleryLibraryManagerManager
{

    function __construct()
    {

    }

    public static function getDefaultOrderStatus()
    {

        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('s.*');
        $query->from('#__eventgallery_orderstatus s');
        $query->where('s.default=1');
        $db->setQuery($query);
        $items = $db->loadObjectList();

        if (count($items) == 0) {
            return NULL;
        }

        $item = $items[0];

        return new EventgalleryLibraryOrderstatus($item);
    }


}
