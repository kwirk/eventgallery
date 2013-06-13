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

class EventgalleryLibraryPayment extends EventgalleryLibraryDatabaseObject
{

	protected $_object = null;
    protected $_object_id = null;
    protected $_ls_displayname = null;
    protected $_ls_description = null;

 	public function __construct($object)
	{
        if ($object instanceof stdClass) {
		    $this->_object = $object;
            $this->_object_id = $object->id;
        } else {
            $this->_object_id = $object;
            $this->_loadSurcharge();
        }

        $this->_ls_displayname =  new EventgalleryLibraryDatabaseLocalizablestring($this->_object->displayname);
        $this->_ls_description =  new EventgalleryLibraryDatabaseLocalizablestring($this->_object->description);

	    parent::__construct();	    	 
	}

    /**
     * Load the image type by id
     */
    protected function _loadSurcharge() {
        $db = JFactory::getDBO();

        $query = $db->getQuery(true);
        $query->select('p.*');
        $query->from('#__eventgallery_payment p');
        $query->where('p.id='.$db->Quote($this->_object_id));

        $db->setQuery($query);
        $this->_object = $db->loadObject();
    }

    /**
     * @return string the id
     */
    public function getId() {
		return $this->_object->id;
	}

    /**
     * @return string the price value
     */
    public function getPrice() {
		return $this->_object->price;
	}

    /**
     * @return string the currency
     */
    public function getCurrency() {
		return $this->_object->currency;
	}

    /**
     * @return string display name
     */
    public function getName() {
        return $this->_object->name;
    }

    /**
     * @return string display name
     */
    public function getDisplayName() {
        return $this->_ls_displayname->get();
    }

    /**
     * @return string display name
     */
    public function getDescription() {
        return $this->_ls_description->get();
    }

}
