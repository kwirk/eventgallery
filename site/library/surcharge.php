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

class EventgalleryLibrarySurcharge extends EventgalleryLibraryMethod
{

    /**
     * Load the image type by id
     */
    protected function _loadMethodData()
    {
        $db = JFactory::getDBO();

        $query = $db->getQuery(true);
        $query->select('s.*');
        $query->from('#__eventgallery_surcharge s');
        $query->where('s.id=' . $db->Quote($this->_object_id));

        $db->setQuery($query);
        $this->_object = $db->loadObject();
    }

    public function createMethod($method, $cart) {



    }

}
