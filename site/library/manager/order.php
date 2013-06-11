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

    /*
    * creates a order from a cart
    */
    public function createOrder($cart) {
        $order = array();
    	$order['table'] = 'Order';
        $order['userid'] = $cart->getUserId();
    	$order = $this->store($order);

    	foreach($cart->getLineItems() as $lineitem) {
    		$data = array();
    		$data['table'] = 'Imagelineitem';
    		$data['status'] = 1;
    		$data['lineitemcontainerid'] = $order->id;    		
    		$this->store($data);
    	}

        return $order;
    }

 
}
