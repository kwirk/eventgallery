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

    /**
     * Returns all orderstatuses of the given type ordered.
     *
     * @param $typeid
     * @return array|null
     */
    public static function getOrderStatuses($typeid) {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('s.*');
        $query->from('#__eventgallery_orderstatus s');
        $query->where('type='.$db->quote($typeid));
        $query->order('ordering');
        $db->setQuery($query);
        $items = $db->loadObjectList();

        if (count($items) == 0) {
            return NULL;
        }

        $orderstatuses = array();
        foreach($items as $item) {
            $orderstatuses[] = new EventgalleryLibraryOrderstatus($item);
        }


        return $orderstatuses;
    }

    /**
     * returns the default order status for the given type
     *
     * @param $typeid
     * @return EventgalleryLibraryOrderstatus|null
     */
    public static function getDefaultOrderStatus($typeid)
    {

        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('s.*');
        $query->from('#__eventgallery_orderstatus s');
        $query->where('type='.$db->quote($typeid));
        $query->order($db->quoteName('default') .' DESC');
        $query->order('ordering');
        $db->setQuery($query);
        $items = $db->loadObjectList();

        if (count($items) == 0) {
            return NULL;
        }

        $item = $items[0];

        return new EventgalleryLibraryOrderstatus($item);
    }


}
