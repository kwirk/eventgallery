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

class EventgalleryLibraryImagetype extends EventgalleryLibraryDatabaseObject
{

	protected $_imagetype = null;
    protected $_imagetype_id = null;

 	public function __construct($dbimagetype)
	{
        if ($dbimagetype instanceof stdClass) {
		    $this->_imagetype = $dbimagetype;
            $this->_imagetype_id = $dbimagetype->id;
        } else {
            $this->_imagetype_id = $dbimagetype;
            $this->_loadImageType();
        }
	    parent::__construct();	    	 
	}

    /**
     * Load the image type by id
     */
    protected function _loadImageType() {
        $db = JFactory::getDBO();

        $query = $db->getQuery(true);
        $query->select('*');
        $query->from('#__eventgallery_imagetype');
        $query->where('id='.$db->Quote($this->_imagetype_id));

        $db->setQuery($query);
        $this->_imagetype = $db->loadObject();

    }

    /**
     * @return mixed the id of the image type
     */
    public function getId() {
		return $this->_imagetype->id;
	}

    /**
     * @return mixed the price value of the image type
     */
    public function getPrice() {
		return $this->_imagetype->price;
	}

    /**
     * @return mixed the currency of the image type
     */
    public function getCurrency() {
		return $this->_imagetype->currency;
	}

    /**
     * @return mixed display name of the image type
     */
    public function getDisplayName() {
        return $this->_imagetype->name;
    }

}
