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
     * @return EventgalleryLibraryOrder
     */
    public function createOrder($cart) {

        $data = $cart->_getInternalDataObject();
        unset($data['id']);

        /**
         * @var TableOrder $orderTable
         */
        $orderTable = $this->store($data, 'Order');

        /**
         * @var EventgalleryLibraryLineitem $lineitem
         */
        foreach($cart->getLineItems() as $lineitem) {
    		$data = array();
            $data['id'] = $lineitem->getId();
    		$data['status'] = 1;
    		$data['lineitemcontainerid'] = $orderTable->id;
    		$this->store($data, 'Imagelineitem');
    	}

        /**
         * @var EventgalleryLibraryOrder $order
         */
        $order = new EventgalleryLibraryOrder($orderTable->id);
        $order->setOrderStatus(EventgalleryLibraryManagerOrderstatus::getDefaultOrderStatus());
        $order->setPayment($cart->getPayment());
        $order->setShipping($cart->getShipping());
        $order->setSurcharge($cart->getSurcharge());

        $cart->setStatus(1);

        return $order;
    }

 
}
