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

class EventgalleryLibraryManagerOrder extends EventgalleryLibraryDatabaseObject
{

    function __construct()
    {

    }

    /**
     * creates a order from a cart
     *
     * @param EventgalleryLibraryCart $cart
     *
     * @return EventgalleryLibraryOrder
     */
    public function createOrder($cart)
    {

        $db = JFactory::getDBO();
        $data = $cart->_getInternalDataObject();

        $uuid = uniqid("", true);
        $uuid = base_convert($uuid,16,10);

        $query = $db->getQuery(true);
        $query->insert("#__eventgallery_order");
        $query->set("id=".$db->quote($uuid));
        $db->setQuery($query);
        $db->execute();

        $data['id'] = $uuid;

        /**
         * @var TableOrder $orderTable
         */
        $orderTable = $this->store($data, 'Order');

        /**
         * @var EventgalleryLibraryImagelineitem $lineitem
         */
        foreach ($cart->getLineItems() as $lineitem) {
            $data = array();
            $data['id'] = $lineitem->getId();
            $data['lineitemcontainerid'] = $orderTable->id;
            $this->store($data, 'Imagelineitem');
        }

        /**
         * @var EventgalleryLibraryServicelineitem $lineitem
         */
        foreach ($cart->getServiceLineItems() as $lineitem) {
            $data = array();
            $data['id'] = $lineitem->getId();
            $data['lineitemcontainerid'] = $orderTable->id;
            $this->store($data, 'Servicelineitem');
        }

        /**
         * @var EventgalleryLibraryOrder $order
         */
        $order = new EventgalleryLibraryOrder($orderTable->id);
        $order->setOrderStatus(EventgalleryLibraryManagerOrderstatus::getDefaultOrderStatus());


        $cart->setStatus(1);

        return $order;
    }


}
