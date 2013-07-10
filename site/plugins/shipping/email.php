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

class EventgalleryPluginsShippingEmail extends  EventgalleryLibraryMethodsShipping
{


    /**
     * Returns if this method can be used with the current cart.
     *
     * @param EventgalleryLibraryLineitemcontainer $cart
     *
     * @return bool
     */
    public function isEligible($cart)
    {

        foreach($cart->getLineItems() as $imagelineitem) {
            /**
             * @var EventgalleryLibraryImagelineitem $imagelineitem
             */
            // if at least one item in the cart is a physical good return false
            if (!$imagelineitem->getImageType()->isDigital()) {
                return false;
            }
        }

        return true;
    }

    static public  function getClassName() {
        return "Shipping: EMail";
    }
}
