<?php
/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2013 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

class RestController extends JControllerLegacy
{
	public function display($cachable = false, $urlparams = false)
	{			
		parent::display($cachable, $urlparams);		
	}

	public function add2cart() {


		$file = JRequest::getString( 'file' , null );
		$folder = JRequest::getString( 'folder' , null );
		$quantity = JRequest::getString( 'quantity' , 1 );
		$typeid = JRequest::getString( 'typeid' , null );
		

		$cart = new EventgalleryModelsCart();	
		$cart->addItem($folder, $file, $quantity, $typeid);
		

		$this->getCart();
		
	}

	public function getCart() {

		$cart = new EventgalleryModelsCart();
		echo $cart->getCartJSON();		
	}

	public function removeFromCart() {

		$session = JFactory::getSession();
		$lineitemid = JRequest::getString( 'lineitemid' , null );
		
		$cart = new EventgalleryModelsCart();	
		$cart->removeItem($lineitemid);
		
		$this->getCart();
	}
}
