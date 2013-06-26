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

class EventgalleryLibraryFactoryOrder extends EventgalleryLibraryFactoryFactory
{


    /**
     * @param EventgalleryLibraryCart $cart
     * @return EventgalleryLibraryOrder
     */
    public function createOrder($cart) {

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
         * @var EventgalleryLibraryFactoryImagelineitem $imageLineItemFactory
         */

        $imageLineItemFactory = EventgalleryLibraryFactoryImagelineitem::getInstance();

        foreach ($cart->getLineItems() as $lineitem) {
            $imageLineItemFactory->copyLineItem($orderTable->id, $lineitem);
        }

        /**
         * @var EventgalleryLibraryServicelineitem $lineitem
         * @var EventgalleryLibraryFactoryServicelineitem $serviceLineItemFactory
         */
        $serviceLineItemFactory = EventgalleryLibraryFactoryServicelineitem::getInstance();

        foreach ($cart->getServiceLineItems() as $lineitem) {
            $serviceLineItemFactory->copyLineItem($orderTable->id, $lineitem);
        }

        /**
         * @var EventgalleryLibraryOrder $order
         */
        $order = new EventgalleryLibraryOrder($orderTable->id);
        $order->setOrderStatus(EventgalleryLibraryManagerOrderstatus::getDefaultOrderStatus());
        $order->setDocumentNumber(EventgalleryLibraryDatabaseSequence::generateNewId());

        return $order;
    }






}
