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

abstract class EventgalleryLibraryManagerMethod extends EventgalleryLibraryManagerManager
{


    /**
     * @var array
     */
    protected $_methods;

    protected $_tablename;

    /**
     * @var array
     */
    protected $_methods_active;



    function __construct()
    {

    }

    /**
     * @param bool $activeOnly
     *
     * @return array
     */
    public function getMethods($activeOnly = true)
    {

        if ($this->_methods == null) {

            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->select('*');
            $query->from($this->_tablename);
            $query->order('ordering');
            $db->setQuery($query);
            $items = $db->loadObjectList();

            $this->_methods = array();
            $this->_methods_active = array();

            foreach ($items as $item) {
                /**
                 * @var EventgalleryLibraryInterfaceMethod $itemObject
                 */
                $itemObject = new $item->classname($item);
                if ($item->active == 1) {
                    $this->_methods_active[$itemObject->getId()] = $itemObject;
                }
                $this->_methods[$itemObject->getId()] = $itemObject;
            }
        }
        if ($activeOnly) {
            return $this->_methods_active;
        } else {
            return $this->_methods;
        }
    }

    /**
     * @return EventgalleryLibraryInterfaceMethod
     */
    public function getDefaultMethod()
    {
        $methods = $this->getMethods(true);
        foreach ($methods as $method) {
            /**
             * @var EventgalleryLibraryInterfaceMethod $method
             */
            if ($method->isDefault()) {
                return $method;
            }
        }

        $array_values = array_values($methods);
        return $array_values[0];
    }

    /**
     * @param int  $methodid
     * @param bool $activeOnly
     *
     * @return EventgalleryLibraryInterfaceMethod
     */
    public function getMethod($methodid, $activeOnly)
    {

        $methods = $this->getMethods($activeOnly);


        if (isset($methods[$methodid])) {
            return $methods[$methodid];
        }

        return null;
    }







}
