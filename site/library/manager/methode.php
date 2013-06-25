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

abstract class EventgalleryLibraryManagerMethode extends EventgalleryLibraryDatabaseObject
{


    /**
     * @var array
     */
    protected $_methodes;

    protected $_tablename;

    /**
     * @var array
     */
    protected $_methodes_active;



    function __construct()
    {

    }

    public static function getInstance() {

    }

    /**
     * @param bool $activeOnly
     *
     * @return array
     */
    public function getMethodes($activeOnly = true)
    {

        if ($this->_methodes == null) {

            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->select('*');
            $query->from($this->_tablename);
            $query->order('ordering');
            $db->setQuery($query);
            $items = $db->loadObjectList();

            $this->_methodes = array();
            $this->_methodes_active = array();

            foreach ($items as $item) {
                /**
                 * @var EventgalleryLibraryInterfaceMethode $itemObject
                 */
                $itemObject = new $item->classname($item);
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
     * @return EventgalleryLibraryInterfaceMethode
     */
    public function getDefaultMethode()
    {
        $methodes = $this->getMethodes(true);
        foreach ($methodes as $method) {
            /**
             * @var EventgalleryLibraryInterfaceMethode $method
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
     * @return EventgalleryLibraryInterfaceMethode
     */
    public function getMethode($methodid, $activeOnly)
    {

        $methodes = $this->getMethodes($activeOnly);


        if (isset($methodes[$methodid])) {
            return $methodes[$methodid];
        }

        return null;
    }

    /**
     * @param EventgalleryLibraryInterfaceMethode $method
     * @param EventgalleryLibraryLineitemcontainer $lineitemcontainer
     */
    public function createServiceLineItem($method, $lineitemcontainer) {

        $quantity = 1;

        $item = array(
            'lineitemcontainerid' => $lineitemcontainer->getId(),
            'quantity' => $quantity,
            'singleprice' => $method->getPrice(),
            'price' => $quantity * $method->getPrice(),
            'taxrate' => $method->getTaxrate(),
            'currency' => $method->getCurrency(),
            'methodid' => $method->getId(),
            'type' => $method->getTypeCode(),
            'name' => $method->getName()
        );


        $this->store($item, 'Servicelineitem');


    }





}
