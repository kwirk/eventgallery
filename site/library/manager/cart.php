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

class EventgalleryLibraryManagerCart
{
	function __construct()
	{		   
	 
	}

	/**
	* get the cart from the database. 
	*/
    public static function getCart() {

    	/* try to get the right user id for the cart. This can also be the session id */
		$session = JFactory::getSession();
		$user_id = $session->getId();
    	
    	return new EventgalleryLibraryCart($user_id);
    }

    public static function updateCart(EventgalleryLibraryCart $cart = null) {

        if ($cart == null) {
            $cart = EventgalleryLibraryManagerCart::getCart();
        }

        /* update cart */
        /* @var EventgalleryLibraryLineitem $lineitem */
        foreach($cart->getLineItems() as $lineitem){

            /* Quantity Update*/
            $quantity = JRequest::getString( 'quantity_'.$lineitem->getId() , null );
            if ($quantity != null) {

                if ($quantity>0) {
                    $lineitem->setQuantity($quantity);
                } else {
                    $cart->deleteLineItem($lineitem->getId());
                }
            }

            /* type update */

            $imagetypeid = JRequest::getString( 'type_'.$lineitem->getId() , null );

            if (null != $imagetypeid) {
                $lineitem->setImageType($imagetypeid);
            }

        }

        $cart->doCalculation();
    }
 
}
