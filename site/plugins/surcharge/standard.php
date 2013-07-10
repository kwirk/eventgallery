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

class EventgalleryPluginsSurchargeStandard extends  EventgalleryLibraryMethodsSurcharge
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
        // if there is no rule, this method is valued
        if (!isset($this->getData()->rules)) {
            return true;
        }

        // if the minimum amount is not defined skip this
        if (isset($this->getData()->rules->minAmount)) {
            // if the subtotal is not high enough
            if ($cart->getSubTotal()<$this->getData()->rules->minAmount ) {
                return false;
            }
        }

        // if the maximum amount is not defined skip this
        if (isset($this->getData()->rules->maxAmount)) {
            // if the subtotal is too high
            if ($cart->getSubTotal()>$this->getData()->rules->maxAmount ) {
                return false;
            }
        }

        return true;
    }

    static public  function getClassName() {
        return "Surcharge: Standard";
    }
}