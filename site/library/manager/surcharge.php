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

class EventgalleryLibraryManagerSurcharge extends EventgalleryLibraryDatabaseObject
{


    /**
     * @var array
     */
    protected $_methodes;
    /**
     * @var array
     */
    protected $_methodes_active;

    protected static $_instance;

    function __construct()
    {

    }

    public static function getInstance()
    {
        if (self::$_instance == NULL) {
            self::$_instance = new EventgalleryLibraryManagerSurcharge();
        }
        return self::$_instance;
    }

    /**
     * @param bool $activeOnly
     *
     * @return array
     */
    public function getMethodes($activeOnly = true)
    {

        if ($this->_methodes == NULL) {

            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->select('*');
            $query->from('#__eventgallery_surcharge');
            $query->order('ordering');
            $db->setQuery($query);
            $items = $db->loadObjectList();

            $this->_methodes = array();
            $this->_methodes_active = array();

            foreach ($items as $item) {
                $itemObject = new EventgalleryLibrarySurcharge($item);
                if ($item->active == 1) {
                    $this->_methodes_active[$itemObject->getId()] = $itemObject;
                }
                $this->_methodes[$itemObject->getId()] = $itemObject;
            }
        }
        if ($activeOnly) {
            return $this->_methodes_active;
        } else {
            return $this->_methodes;
        }
    }

    /**
     * @return EventgalleryLibrarySurcharge
     */
    public function getDefaultMethode()
    {
        $methodes = $this->getMethodes(true);
        foreach ($methodes as $method) {
            /**
             * @var EventgalleryLibrarySurcharge $method
             */
            if ($method->isDefault()) {
                return $method;
            }
        }

        $array_values = array_values($methodes);
        return $array_values[0];
    }

    /**
     * @param int  $methodid
     * @param bool $activeOnly
     *
     * @return EventgalleryLibrarySurcharge
     */
    public function getMethode($methodid, $activeOnly)
    {

        $methodes = $this->getMethodes($activeOnly);


        if (isset($methodes[$methodid])) {
            return $methodes[$methodid];
        }

        return NULL;
    }

    /**
     * @param EventgalleryLibrarySurcharge $method
     * @param EventgalleryLibraryLineitemcontainer $lineitemcontainer
     */
    public function createServiceLineItem($method, $lineitemcontainer) {

        $quantity = 1;

        $item = array(
            'lineitemcontainerid' => $lineitemcontainer->getId(),
            'quantity' => $quantity,
            'singleprice' => $method->getPrice(),
            'price' => $quantity * $method->getPrice(),
            'currency' => $method->getCurrency(),
            'methodid' => $method->getId(),
            'type' => EventgalleryLibraryServicelineitem::TYPE_SURCHARGE,
            'name' => $method->getName()
        );


        $this->store($item, 'Servicelineitem');

    }


}
