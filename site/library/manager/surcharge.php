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

class EventgalleryLibraryManagerSurcharge
{
	
	function __construct()
	{		
	 
	}

    public static function getSurcharges($activeOnly = true) {

        $db = JFactory::getDBO();
        $query = $db->getQuery(TRUE);
        $query->select('s.*');
        $query->from('#__eventgallery_surcharge s');
        if ($activeOnly) {
            $query->where('s.active=1');
        }
        $db->setQuery($query);
        $items = $db->loadObjectList();

        $surcharges = array();
        foreach($items as $item) {
            array_push($surcharges, new EventgalleryLibrarySurcharge($item));
        }

        return $surcharges;


    }

 
}
