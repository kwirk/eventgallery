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
    protected $_ls_displayname = null;
    protected $_ls_description = null;

 	public function __construct($dbimagetype)
	{
        if ($dbimagetype instanceof stdClass) {
		    $this->_imagetype = $dbimagetype;
            $this->_imagetype_id = $dbimagetype->id;
        } else {
            $this->_imagetype_id = $dbimagetype;
            $this->_loadImageType();
        }

        $this->_ls_displayname =  new EventgalleryLibraryDatabaseLocalizablestring($this->_imagetype->displayname);
        $this->_ls_description =  new EventgalleryLibraryDatabaseLocalizablestring($this->_imagetype->description);

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
     * @return string the id of the image type
     */
    public function getId() {
		return $this->_imagetype->id;
	}

    /**
     * @return string the price value of the image type
     */
    public function getPrice() {
		return $this->_imagetype->price;
	}

    /**
     * @return string the currency of the image type
     */
    public function getCurrency() {
		return $this->_imagetype->currency;
	}

    /**
     * @return string display name of the image type
     */
    public function getName() {
        return $this->_imagetype->name;
    }

    /**
     * @return string display name of the image type
     */
    public function getDisplayName() {
        return $this->_ls_displayname->get();
    }

    /**
     * @return string display name of the image type
     */
    public function getDescription() {
        return $this->_ls_description->get();
    }

}
