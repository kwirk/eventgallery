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

class EventgalleryLibraryManagerOrder extends EventgalleryLibraryManagerManager
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

        /**
         * @var EventgalleryLibraryFactoryOrder $orderFactory
         */
        $orderFactory = EventgalleryLibraryFactoryOrder::getInstance();
        $order = $orderFactory->createOrder($cart);

        // put the cart in the cart history
        $cart->setStatus(1);

        return $order;
    }

    /**
     * @param EventgalleryLibraryLineitemcontainer $lineitemcontainer
     */
    public function processOnOrderSubmit($lineitemcontainer) {

        $lineitemcontainer->getShippingMethod()->processOnOrderSubmit($lineitemcontainer);
        $lineitemcontainer->getPaymentMethod()->processOnOrderSubmit($lineitemcontainer);
        $lineitemcontainer->getSurcharge()->processOnOrderSubmit($lineitemcontainer);

    }


}
