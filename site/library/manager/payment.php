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

class EventgalleryLibraryManagerPayment
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

    public static function getInstance() {
        if (self::$_instance==null) {
            self::$_instance = new EventgalleryLibraryManagerPayment();
        }
        return self::$_instance;
}
    /**
     * @param bool $activeOnly
     * @return array
     */
    public function getMethodes($activeOnly = true) {

        if ($this->_methodes==null) {


            $db = JFactory::getDBO();
            $query = $db->getQuery(TRUE);
            $query->select('*');
            $query->from('#__eventgallery_paymentmethod');
            $query->order('ordering');
            $db->setQuery($query);
            $items = $db->loadObjectList();

            $this->_methodes = array();
            $this->_methodes_active = array();

            foreach($items as $item) {
                $itemObject = new EventgalleryLibraryPayment($item);
                if ($item->active==1) {
                    array_push($this->_methodes_active, $itemObject);
                }
                array_push($this->_methodes, $itemObject);
            }
        }
        if ($activeOnly) {
            return $this->_methodes_active;
        } else {
            return $this->_methodes;
        }
    }

    /**
     * @return EventgalleryLibraryPayment
     */
    public function getDefaultMethode() {
       $methodes = $this->getMethodes(true);
       foreach($methodes as $method) {
           /**
            * @var EventgalleryLibraryPayment $method
            */
           if($method->isDefault()) {
                return $method;
            }
       }

       $array_values = array_values($methodes);
       return $array_values[0] ;
    }

    /**
     * @param int $methodid
     * @param bool $activeOnly
     * @return EventgalleryLibraryPayment
     */
    public function getMethode($methodid, $activeOnly) {
        if ($activeOnly) {
            $methodes = $this->_methodes_active;
        } else {
            $methodes = $this->_methodes;
        }

        if (isset($methodes[$methodid])) {
            return $methodes[$methodid];
        }

        return null;
    }


 
}
