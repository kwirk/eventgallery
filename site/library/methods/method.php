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

/**
 * Provides an abstract class with the base implementation for each method
 *
 * Class EventgalleryLibraryMethodsMethod
 */
abstract class EventgalleryLibraryMethodsMethod extends EventgalleryLibraryDatabaseObject implements EventgalleryLibraryInterfaceMethod, EventgalleryLibraryInterfaceEvents
{

    protected $_object = null;
    protected $_object_id = null;
    protected $_data = null;
    protected $_ls_displayname = null;
    protected $_ls_description = null;

    public function __construct($object)
    {
        if ($object instanceof stdClass) {
            $this->_object = $object;
            $this->_object_id = $object->id;
        } else {
            $this->_object_id = $object;
            $this->_loadMethodData();
        }

        $this->_ls_displayname = new EventgalleryLibraryDatabaseLocalizablestring($this->_object->displayname);
        $this->_ls_description = new EventgalleryLibraryDatabaseLocalizablestring($this->_object->description);

        parent::__construct();
    }

    /**
     * Load the image type by id
     */
    abstract protected function _loadMethodData();

    /**
     * @return string the id
     */
    public function getId()
    {
        return $this->_object->id;
    }

    /**
     * @return float the price value
     */
    public function getPrice()
    {
        return $this->_object->price;
    }

    /**
     * @return string the currency
     */
    public function getCurrency()
    {
        return $this->_object->currency;
    }

    /**
     * @return string display name
     */
    public function getName()
    {
        return $this->_object->name;
    }

    /**
     * @return string display name
     */
    public function getDisplayName()
    {
        return $this->_ls_displayname->get();
    }

    /**
     * @return string display name
     */
    public function getDescription()
    {
        return $this->_ls_description->get();
    }

    /**
     * @return bool
     */
    public function isDefault()
    {
        return $this->_object->default == 1 ? true : false;
    }

    /**
     * @return stdClass|null
     */
    public function getData()
    {
        if (null == $this->_data) {
            $this->_data = json_decode($this->_object->data);
        }

        return $this->_data;
    }


    /**
     * returns the amount of tax for this item
     *
     * @return float
     */
    public function getTax() {
        return $this->getPrice()*$this->getTaxrate()/100;
    }
    /**
     * @return int
     */
    public function getTaxrate() {
        return $this->_object->taxrate;
    }

    public function processOnOrderSubmit($lineitemcontainer) {
        return true;
    }
}
